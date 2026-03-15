<?php
// admin can view all orders, filter by status, and update order status (e.g., mark as completed or cancelled).


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\MenuItem;    
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orderNo = $request->get('order_no');
        $customerName = $request->get('customer_name');
        $amount = $request->get('amount');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $orders = Order::with('customer', 'table')
            ->when($orderNo, function ($query, $orderNo) {
                $query->where('order_no', 'like', '%' . $orderNo . '%');
            })
            ->when($customerName, function ($query, $customerName) {
                $query->whereHas('customer', function ($q) use ($customerName) {
                    $q->where('name', 'like', '%' . $customerName . '%');
                });
            })
            ->when($amount !== null && $amount !== '', function ($query) use ($amount) {
                $normalizedAmount = str_replace(',', '', trim((string) $amount));
                $query->where('total_amount', 'like', '%' . $normalizedAmount . '%');
            })
            ->when($startDate, function ($query, $startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        // when starting a new order there is no existing order record yet.
        // we only need categories, menu items, tables, customers and any
        // persisted cart data (if using session/localStorage for draft orders).
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        
        // active categories and menu items
        $categories = Category::where('status', 1)->get();
        $menuItems  = MenuItem::where('status', 'available')->with('category')->get();

        $tables    = Table::where('status', 'available')->get();
        $customers = Customer::all();
        $order_types = ['dine_in', 'takeaway', 'delivery'];
        
       
        
        return view('admin.orders.create', compact('categories', 'menuItems', 'tables', 'customers', 'cart', 'subtotal', 'order_types'));
    }

    

    public function store(Request $request)
    {

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'table_id'    => 'nullable|exists:tables,id',
            'order_type'  => 'required|in:dine_in,takeaway,delivery',
            'items'       => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.price'        => 'required|numeric|min:0',
        ]);
        try {
            $order = DB::transaction(function () use ($validated) {

                $orderType = $validated['order_type'];
                    // For dine-in orders, table_id is required and must be available
                if ($orderType === 'dine_in') {
                    
                    if (empty($validated['table_id'])) {
                        throw ValidationException::withMessages([
                            'table_id' => 'Table selection is required for dine-in orders.',
                        ]);
                    }
                    
                    // Lock the table row to prevent race conditions (only for dine-in)
                    $table = Table::lockForUpdate()->findOrFail($validated['table_id']);

                    // Check table status
                    if ($table->status === 'occupied') {
                        throw ValidationException::withMessages([
                            'table_id' => 'This table is already occupied by another order.',
                        ]);
                    }

                    if ($table->status === 'reserved' && $table->reserved_until && now()->lt($table->reserved_until)) {
                        throw ValidationException::withMessages([
                            'table_id' => 'This table is currently reserved.',
                        ]);
                    }
                } else {
                    // For takeout or delivery orders, table_id must be null
                    if (!empty($validated['table_id'])) {
                        throw ValidationException::withMessages([
                            'table_id' => 'Table selection is not allowed for takeout or delivery orders.',
                        ]);
                    }
                    $table = null; // No table for non-dine-in orders
                }

                // Create the order record with temporary totals (created_at/updated_at set by Eloquent)
                $order = Order::create([
                    'order_no'     => 'ORD-'. strtoupper(Str::random(6)),
                    'customer_id'  => $validated['customer_id'] ?? null,
                    'table_id'     => $validated['table_id']?? null,
                    'order_type'   => $validated['order_type'],
                    'user_id'      => Auth::id(),
                    'status'       => 'pending', // default to pending status
                    'subtotal'     => 0.00,
                    'tax'          => 0.00,
                    'total_amount' => 0.00,
                    'created_at'   =>now()->format('Y-m-d H:i:s'),
                    'updated_at'   =>now()->format('Y-m-d H:i:s'),
                    

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

                // Mark table as occupied (only for dine-in orders)
                if ($orderType === 'dine_in' && $table) {
                    $table->update([
                        'status'         => 'occupied',
                        'occupied_by'    => $order->id,
                        'occupied_since' => now(),
                    ]);
                }

                return $order;
            });

            return redirect()
                ->route('admin.orders.show', $order->id)
                ->with('success', 'Order #' . $order->order_no . ' created successfully!');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Cannot create order: ' . implode(', ', Arr::flatten($e->errors())));
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the order. Please try again.');
        }
    }

    public function show($id)
    {
        $order_items = OrderItem::where('order_id', $id)->first();
        $orders = Order::with(['orderItems.menuItem', 'table', 'customer', 'user'])->findOrFail($id);
        $total_amount = $orders->total_amount;
        $menuItems = MenuItem::where('status', 'available')
            ->orderBy('name')
            ->get(['id', 'name', 'price']);

        return view('admin.orders.show', compact('orders', 'order_items', 'total_amount', 'menuItems'));
    }
       
    

    public function updateStatus(Order $orders, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled',
        ]);

        $orders->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.orders.show', $orders->id)
            ->with('success', 'Order status updated to ' . $validated['status']);
    }
}
