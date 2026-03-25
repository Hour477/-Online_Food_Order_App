<?php

namespace App\Http\Controllers\CustomerOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Payment;
use App\Events\OrderPlaced;
use App\Services\BakongService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class customerCheckoutController extends Controller
{
    protected BakongService $bakongService;

    public function __construct(BakongService $bakongService)
    {
        $this->bakongService = $bakongService;
    }

    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customerOrder.cart.index')->with('error', 'Your basket is empty.');
        }
        
        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
        $tax = $subtotal * 0.10; // Match admin's 10% tax
        $total = $subtotal + $tax;

        // Get current customer if logged in
        $customer = Customer::where('user_id', Auth::id())->first();

        return view('customerOrder.checkout.index', compact('cart', 'subtotal', 'tax', 'total', 'customer'));
    }

    public function place(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name'  => 'required|string|max:60',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:200',
            'city'       => 'required|string|max:60',
            'zip'        => 'required|string|max:20',
            'payment'    => 'required|in:cash,khqr',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customerOrder.cart.index')->with('error', 'Your basket is empty.');
        }

        try {
            $order = DB::transaction(function () use ($request, $cart) {
                $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
                $tax = $subtotal * 0.10;
                $total = $subtotal + $tax;

                // 1. Find or Create Customer
                // Always link to logged-in user (auth middleware is active)
                $customer = Customer::updateOrCreate(
                    ['user_id' => Auth::id()],
                    [
                        'name'    => $request->first_name . ' ' . $request->last_name,
                        'email'   => Auth::user()->email,
                        'phone'   => $request->phone,
                        'address' => $request->address,
                        'city'    => $request->city
                    ]
                );

                // 2. Create Order
                $order = Order::create([
                    'order_no'     => 'ORD-' . strtoupper(Str::random(6)),
                    'customer_id'  => $customer->id,
                    'order_type'   => 'delivery',
                    'user_id'      => Auth::id(),
                    'status'       => 'pending',
                    'notes'        => $request->notes . "\nDelivery to: {$request->first_name} {$request->last_name}, {$request->address}, {$request->city} {$request->zip}, Phone: {$request->phone}",
                    'subtotal'     => $subtotal,
                    'tax'          => $tax,
                    'total_amount' => $total,
                ]);

                // 3. Create Order Items
                foreach ($cart as $item) {
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'menu_item_id' => $item['id'],
                        'quantity'     => $item['qty'],
                        'price'        => $item['price'],
                        'subtotal'     => $item['price'] * $item['qty'],
                    ]);
                }

                // 4. Create Payment record
                $paymentData = [
                    'order_id'       => $order->id,
                    'payment_method' => $request->payment,
                    'total_amount'   => $total,
                    'status'         => $request->payment === 'khqr' ? 'pending' : 'paid',
                ];

                // For cash payment, mark as paid and update order status to confirmed
                if ($request->payment === 'cash') {
                    $paymentData['paid_amount'] = $total;
                    $paymentData['change_amount'] = 0;
                    $paymentData['paid_at'] = now();
                    
                    // Update order status to confirmed since it's paid
                    $order->update(['status' => 'confirmed']);
                }

                // For KHQR, generate QR code
                if ($request->payment === 'khqr') {
                    $khqrResult = $this->bakongService->generateKHQR($order->order_no, $total);
                    
                    if ($khqrResult['success']) {
                        $paymentData['khqr_md5'] = $khqrResult['md5'];
                        $paymentData['khqr_string'] = $khqrResult['qr_string'];
                        $paymentData['khqr_expires_at'] = now()->addMinutes(15); // QR expires in 15 minutes
                    } else {
                        // Fallback: generate local KHQR
                        $qrString = $this->bakongService->generateLocalKHQR($order->order_no, $total);
                        $paymentData['khqr_string'] = $qrString;
                        $paymentData['khqr_expires_at'] = now()->addMinutes(15);
                    }
                }

                Payment::create($paymentData);

                return $order;
            });

            // Trigger the OrderPlaced event for real-time notification
            event(new OrderPlaced($order));

            // Store current order for confirmation page
            $order->load(['orderItems.menuItem', 'payments']);
            session(['last_order' => $order]);

            // Clear cart
            session()->forget('cart');

            // If KHQR payment, return JSON for modal or redirect
            if ($request->payment === 'khqr') {
                $qrImageUrl = $this->bakongService->getQRImageURL($order->payment->khqr_string);
                
                // If AJAX request, return JSON
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'order_id' => $order->id,
                        'order_no' => $order->order_no,
                        'amount' => $order->total_amount,
                        'qr_image' => $qrImageUrl,
                        'expires_at' => $order->payment->khqr_expires_at,
                    ]);
                }
                
                // Otherwise redirect to KHQR payment page
                return redirect()->route('customerOrder.checkout.khqr-payment', $order->id);
            }

            return redirect()->route('customerOrder.checkout.confirmation');

        } catch (\Exception $e) {
            Log::error('Customer checkout failed: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while placing your order. ' . $e->getMessage());
        }
    }

    /**
     * Show KHQR payment page with QR code
     */
    public function khqrPayment(Order $order)
    {
        $order->load(['payments', 'orderItems.menuItem']);
        
        if (!$order->payment || $order->payment->payment_method !== 'khqr') {
            return redirect()->route('customerOrder.checkout.confirmation');
        }

        // Check if QR has expired
        if ($order->payment->isKHQRExpired()) {
            return redirect()->route('customerOrder.checkout.index')
                ->with('error', 'QR code has expired. Please try again.');
        }

        // Generate QR image URL
        $qrImageUrl = $this->bakongService->getQRImageURL($order->payment->khqr_string);

        return view('customerOrder.checkout.khqr-payment', compact('order', 'qrImageUrl'));
    }

    /**
     * Check KHQR payment status (AJAX)
     */
    public function checkKHQRStatus(Order $order)
    {
        $order->load('payments');
        
        if (!$order->payment || !$order->payment->khqr_md5) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Invalid payment',
            ]);
        }

        // Check if expired
        if ($order->payment->isKHQRExpired()) {
            return response()->json([
                'success' => true,
                'status' => 'expired',
                'message' => 'QR code has expired',
            ]);
        }

        // Check with Bakong API
        $result = $this->bakongService->checkPaymentStatus($order->payment->khqr_md5);

        if ($result['success'] && $result['status'] === 'success') {
            // Update payment status
            $order->payment->update([
                'status' => 'paid',
                'paid_amount' => $order->total_amount,
                'paid_at' => now(),
                'khqr_transaction_id' => $result['transaction_id'] ?? null,
            ]);

            // Update order status to confirmed
            $order->update(['status' => 'confirmed']);

            return response()->json([
                'success' => true,
                'status' => 'paid',
                'redirect' => route('customerOrder.checkout.confirmation'),
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 'pending',
        ]);
    }

    /**
     * Cancel KHQR payment and return to checkout
     */
    public function cancelKHQRPayment(Order $order)
    {
        $order->load('payments');
        
        if ($order->payment && $order->payment->payment_method === 'khqr') {
            $order->payment->update(['status' => 'cancelled']);
        }

        return redirect()->route('customerOrder.menu.index')
            ->with('info', 'Payment cancelled. Your order has been saved.');
    }

    public function confirmation()
    {
        $order = session('last_order');
        if (!$order) {
            return redirect()->route('customerOrder.menu.index');
        }
        return view('customerOrder.checkout.confirmation', compact('order'));
    }
}
