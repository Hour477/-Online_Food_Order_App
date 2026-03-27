<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Models\Order; // Added for Order model
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr; // Added for Arr::flatten
use Illuminate\Support\Facades\DB; // Added for DB transactions
use Illuminate\Support\Facades\Log; // Added for logging errors
use Illuminate\Validation\ValidationException; // Added for validation exceptions

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderNo = request()->get('order_no');
        $paymentMethod = request()->get('payment_method');
        $status = request()->get('status');

        $payments = Payment::with('order.customer')
            ->when($orderNo, function ($query, $orderNo) {
                $query->whereHas('order', function ($q) use ($orderNo) {
                    $q->where('order_no', 'like', '%' . $orderNo . '%');
                });
            })
            ->when($paymentMethod, function ($query, $paymentMethod) {
                $query->where('payment_method', $paymentMethod);
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            });

        $payments = $payments->latest()->paginate(10)->withQueryString();

        // Get status counts for sidebar badges
        $statusCounts = [
            'all'      => Payment::count(),
            'pending'  => Payment::where('status', 'pending')->count(),
            'paid'     => Payment::where('status', 'paid')->count(),
            'failed'   => Payment::where('status', 'failed')->count(),
            'refunded' => Payment::where('status', 'refunded')->count(),
        ];

        // Calculate total revenue (only paid payments)
        $totalRevenue = Payment::where('status', 'paid')->sum('paid_amount');

        // For filtering options in the view
        $availablePaymentMethods = Payment::select('payment_method')->distinct()->pluck('payment_method');

        return view('admin.payments.index', compact(
            'payments', 'orderNo', 'paymentMethod', 'status',
            'availablePaymentMethods', 'statusCounts', 'totalRevenue'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get orders that are not yet completed or cancelled
        $orders = Order::whereNotIn('status', ['completed', 'cancelled'])
            ->with('customer') // Eager load customer for display
            ->latest()
            ->get();

        // Define available payment methods
        $paymentMethods = [
            'cash'   => 'Cash',
            'card'   => 'Card',
            'aba'    => 'ABA Bank',
            'wing'   => 'Wing Money',
            'bakong' => 'Bakong',
            'qr'     => 'QR Code', // Added 'qr' as seen in customerCheckoutController
        ];

        return view('admin.payments.create', compact('orders', 'paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id'       => 'required|exists:orders,id',
            'payment_method' => 'required|string|max:255',
            'paid_amount'    => 'required|numeric|min:0', // The amount the customer is paying in this transaction
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $order = Order::findOrFail($validated['order_id']);

                // Check if the order is already completed
                if ($order->status === 'completed') {
                    // If the order is completed, we might still allow recording an "overpayment"
                    // or simply prevent further payments. For now, let's prevent.
                    throw new \Exception('This order is already completed and fully paid. No further payments can be recorded.');
                }

                // Calculate the total amount already paid for this order
                $existingPaidAmount = $order->payments()->sum('paid_amount');

                // Calculate the remaining amount due for the order
                $remainingDue = $order->total_amount - $existingPaidAmount;

                // The amount being paid in this transaction
                $currentPaymentAmount = $validated['paid_amount'];

                if ($currentPaymentAmount < 0) {
                    throw new \Exception('Paid amount cannot be negative.');
                }

                $changeAmount = 0;
                if ($currentPaymentAmount > $remainingDue) {
                    $changeAmount = $currentPaymentAmount - $remainingDue;
                }

                Payment::create([
                    'order_id'       => $order->id,
                    'payment_method' => $validated['payment_method'],
                    'total_amount'   => $order->total_amount, // Consistent with other payment creations
                    'paid_amount'    => $currentPaymentAmount,
                    'change_amount'  => $changeAmount,
                    'paid_at'        => now(),
                    'status'         => 'paid'
                ]);

                // Recalculate total paid after this new payment
                $newTotalPaidForOrder = $order->payments()->sum('paid_amount');

                // Update order status if the new total paid amount covers the order's total amount
                if ($newTotalPaidForOrder >= $order->total_amount) {
                    $newStatus = ($order->order_type === 'dine_in') ? 'completed' : 'confirmed';
                    $order->update(['status' => $newStatus]);
                }
            });

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Payment recorded successfully!');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validation failed: ' . implode(', ', Arr::flatten($e->errors())));
        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Something went wrong while recording the payment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
