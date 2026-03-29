<?php
// admin can view all orders, filter by status, and update order status (e.g., mark as completed or cancelled).


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Table;
use App\Services\BakongService;
use Barryvdh\DomPDF\Facade\Pdf;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderController extends Controller
{
    public function __construct(protected BakongService $bakongService)
    {
    }

    public function index(Request $request)
    {
        return $this->renderOrderListing($request, 'admin.orders.index', null, 'All Orders');
    }

    public function pending(Request $request)
    {
        return $this->renderOrderListing($request, 'admin.orders.pending', 'pending', 'Pending');
    }

    public function confirmed(Request $request)
    {
        return $this->renderOrderListing($request, 'admin.orders.confirmed', 'confirmed', 'Confirmed');
    }

    

    public function completed(Request $request)
    {
        return $this->renderOrderListing($request, 'admin.orders.completed', 'completed', 'Completed');
    }

    public function refunded(Request $request)
    {
        return $this->renderOrderListing($request, 'admin.orders.refunded', 'refunded', 'Refunded');
    }

    public function cancelled(Request $request)
    {
        return $this->renderOrderListing($request, 'admin.orders.cancelled', 'cancelled', 'Cancelled');
    }

    protected function renderOrderListing(Request $request, string $view, ?string $forcedStatus, string $pageTitle)
    {
        $keyword = trim((string) $request->get('search', ''));
        $customerName = trim((string) $request->get('customer_name', ''));
        $amount = $request->get('amount');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $statusFilter = $forcedStatus ?? $request->get('status');

        $orders = Order::with('customer', 'payments')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('order_no', 'like', '%' . $keyword . '%')
                        ->orWhereHas('customer', function ($c) use ($keyword) {
                            $c->where('name', 'like', '%' . $keyword . '%');
                        });
                });
            })
            ->when($customerName !== '', function ($query) use ($customerName) {
                $query->whereHas('customer', function ($q) use ($customerName) {
                    $q->where('name', 'like', '%' . $customerName . '%');
                });
            })
            ->when($amount !== null && $amount !== '', function ($query) use ($amount) {
                $normalized = str_replace(',', '', trim((string) $amount));
                if ($normalized === '') {
                    return;
                }
                if (is_numeric($normalized)) {
                    $query->where('total_amount', $normalized);
                }
            })
            ->when(filled($startDate), function ($query) use ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when(filled($endDate), function ($query) use ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->when(filled($statusFilter), function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view($view, compact('orders', 'pageTitle'));
    }

    /**
     * Check for new orders since a given timestamp (for real-time notifications)
     */
    public function checkNewOrders(Request $request)
    {
        $lastId = (int) $request->get('last_id', 0);
        $lastCheck = $request->get('last_check');

        // We only want to notify about online orders (delivery or takeaway)
        $query = Order::with('customer')
            ->whereIn('order_type', ['delivery', 'takeaway']);

        // Use ID-based filtering for maximum reliability if lastId is provided.
        // This ensures that an order already in the database when the page loaded is not treated as "new".
        if ($lastId > 0) {
            $query->where('id', '>', $lastId);
        } elseif ($lastCheck) {
            $query->where('created_at', '>', $lastCheck);
        } else {
            // No filtering parameters provided, return no results.
            return response()->json(['count' => 0, 'orders' => [], 'timestamp' => now()->format('Y-m-d H:i:s'), 'max_id' => 0]);
        }

        $newOrders = $query->latest()->get();

        return response()->json([
            'count' => $newOrders->count(),
            'orders' => $newOrders,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'max_id' => $newOrders->max('id') ?? $lastId
        ]);
    }

    public function create()
    {
        return view('admin.orders.create', $this->getOrderCreationData());
    }

    public function createKhqr()
    {
        $data = $this->getOrderCreationData();
        $total = ($data['subtotal'] * 1.10);
        $reference = 'ADMIN-' . now()->format('YmdHis');
        $khqrString = $this->bakongService->generateLocalKHQR($reference, $total);
        $qrImageUrl = $this->bakongService->getQRImageURL($khqrString);

        return view('admin.orders.qr.index', array_merge($data, [
            'khqrString' => $khqrString,
            'qrImageUrl' => $qrImageUrl,
            'qrReference' => $reference,
            'tax' => $data['subtotal'] * 0.10,
            'total' => $total,
        ]));
    }

    protected function getOrderCreationData(): array
    {
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);

        return [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'categories' => Category::where('status', 1)->get(),
            'menuItems' => MenuItem::where('status', 'available')->with('category')->get(),
            'customers' => Customer::all(),
            'order_types' => ['dine_in', 'takeaway', 'delivery'],
        ];
    }



    public function store(Request $request)
    {

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            
            'order_type'  => 'required|in:dine_in,takeaway,delivery',
            'items'       => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.price'        => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'paid_amount'    => 'nullable|numeric|min:0',
        ]);
        try {
            $order = DB::transaction(function () use ($validated, $request) {

                

                // Create the order record with temporary totals (created_at/updated_at set by Eloquent)
                $order = Order::create([
                    'order_no'     => 'ORD-' . strtoupper(Str::random(6)),
                    'customer_id'  => $validated['customer_id'] ?? null,
                    
                    'order_type'   => $validated['order_type'],  // 
                    'user_id'      => Auth::id(),
                    'status'       => 'pending', // default to pending status
                    'subtotal'     => 0.00,
                    'tax'          => 0.00,
                    'total_amount' => 0.00,
                    'created_at'   => now()->format('Y-m-d H:i:s'),
                ]);

                // Add each item from the request

                // call OrderItemsController to handle item creation and subtotal calculation



                $subtotal = 0;
                foreach ($validated['items'] as $itemData) {
                    $price = $itemData['price'];
                    $qty   = $itemData['quantity'];
                    $subtotal += $price * $qty;

                    OrderItem::create([
                        'order_id'     => $order->id,
                        'menu_item_id' => $itemData['menu_item_id'],
                        'quantity'     => $qty,
                        'price'        => $price,
                        'subtotal'     => $price * $qty,
                    ]);
                }

                // compute taxes & totals
                $tax = $subtotal * 0.10; // hardcoded 10% rate
                $total = $subtotal + $tax;
                // summary cart have in the Table order_items
                $order->update([
                    'subtotal'     => $subtotal,
                    'tax'          => $tax,
                    'total_amount' => $total,
                ]);

                if ($request->filled('payment_method')) {
                    $paidAmount = $request->input('paid_amount', $total);
                    $changeAmount = $paidAmount - $total;
                    
                    $isDeliveryCash = ($order->order_type === 'delivery' && $request->input('payment_method') === 'cash');

                    Payment::create([
                        'order_id'       => $order->id,
                        'payment_method' => $request->input('payment_method'),
                        'total_amount'   => $total,
                        'paid_amount'    => $paidAmount,
                        'change_amount'  => $changeAmount,
                        'paid_at'        => $isDeliveryCash && $paidAmount < $total ? null : now(),
                        'status'         => $isDeliveryCash && $paidAmount < $total ? 'pending' : 'paid',
                    ]);

                    // Update order status if paid in full
                    if ($paidAmount >= $total) {
                        $newStatus = ($order->order_type === 'dine_in') ? 'completed' : 'confirmed';
                        $order->update(['status' => $newStatus]);
                    }
                }

                return $order;
            });
            ToastMagic::success('Order #' . $order->order_no . ' created successfully!');
            // Redirect to receipt view (which opens in new tab thanks to target="_blank" on the form)
            return redirect()->route('admin.orders.receipt', $order->id);
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            return back()
                ->withInput();
        }
    }

    public function show($id)
    {
        $order_items = OrderItem::where('order_id', $id)->first();
        $orders = Order::with(['orderItems.menuItem', 'customer', 'user', 'payments'])->findOrFail($id);
        $total_amount = $orders->total_amount;
        $menuItems = MenuItem::where('status', 'available')
            ->orderBy('name')
            ->get(['id', 'name', 'price']);
        // payment
        // $payment = Payment::where('order_id', $id)->first();
        return view('admin.orders.show', compact('orders', 'order_items', 'total_amount', 'menuItems'));
    }



    public function updateStatus(Order $order, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,delivered,completed,refunded,cancelled',
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order->update([
                'status' => $validated['status'],
            ]);

            $payment = $order->payment;

            if (!$payment) {
                return;
            }

            if ($validated['status'] === 'completed') {
                $payment->update([
                    'status' => 'paid',
                    'paid_amount' => $payment->paid_amount > 0 ? $payment->paid_amount : $order->total_amount,
                    'change_amount' => $payment->change_amount ?? 0,
                    'paid_at' => $payment->paid_at ?? now(),
                ]);
            }

            if ($validated['status'] === 'refunded') {
                $payment->update([
                    'status' => 'refunded',
                ]);
            }

            if ($validated['status'] === 'cancelled' && $payment->status !== 'paid') {
                $payment->update([
                    'status' => 'failed',
                ]);
            }
        });

        ToastMagic::success('Order status updated to ' . $validated['status']);
        return redirect()->route('admin.orders.show', $order->id);
    }


    public function checkout(Order $order)
    {
        $order->load('orderItems.menuItem');

        $paymentMethods = [
            'cash',
            'card',
            'qr'
        ];
        return view('admin.orders.checkout', compact('order', 'paymentMethods'));
    }

    public function processPayment(Order $order, Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string'
        ]);

        DB::transaction(function () use ($order, $validated) {

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'total_amount' => $order->total_amount,
                'paid_amount' => $order->total_amount,
                'change_amount' => 0,
                'paid_at' => now(),
                'status' => 'paid'
            ]);

            $newStatus = ($order->order_type === 'dine_in') ? 'completed' : 'confirmed';
            $order->update([
                'status' => $newStatus
            ]);
        });

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Payment successful');
    }

    /**
     * Generate receipt for orders
     */
    public function generateReceipt(Order $order)
    {
        $order->load(['orderItems.menuItem', 'customer', 'user', 'payments']);
        
        $settings = Setting::query()
            ->whereIn('key', ['resturant_name', 'logo', 'address', 'phone', 'email'])
            ->pluck('value', 'key');

        // Return the HTML view directly (printable)
        return view('admin.orders.receipt', compact('order', 'settings'));
    }

    public function destroy(Order $order)
    {
        $order->delete(); // soft delete

        ToastMagic::success('Order Deleted Successfully');
        return redirect()->route('admin.orders.index');
    }
}
