<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiOrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
         return Order::query()->get(); 

        return response()->json($orders);
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'table_id' => 'nullable|exists:tables,id',
            'order_type' => 'required|in:dine_in,takeaway,delivery',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {

            $order = DB::transaction(function () use ($validated) {

                $table = null;

                if ($validated['order_type'] === 'dine_in') {

                    if (empty($validated['table_id'])) {
                        throw ValidationException::withMessages([
                            'table_id' => 'Table is required for dine-in order'
                        ]);
                    }

                    $table = Table::lockForUpdate()->findOrFail($validated['table_id']);

                    if ($table->status === 'occupied') {
                        throw ValidationException::withMessages([
                            'table_id' => 'Table already occupied'
                        ]);
                    }
                }

                $order = Order::create([
                    'order_no' => 'ORD-' . strtoupper(Str::random(6)),
                    'customer_id' => $validated['customer_id'] ?? null,
                    'table_id' => $validated['table_id'] ?? null,
                    'order_type' => $validated['order_type'],
                    'user_id' => Auth::id(),
                    'status' => 'pending',
                    'subtotal' => 0,
                    'tax' => 0,
                    'total_amount' => 0,
                ]);

                $subtotal = 0;

                foreach ($validated['items'] as $item) {

                    $itemSubtotal = $item['price'] * $item['quantity'];

                    $subtotal += $itemSubtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $item['menu_item_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $itemSubtotal,
                    ]);
                }

                $tax = $subtotal * 0.10;
                $total = $subtotal + $tax;

                $order->update([
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total_amount' => $total,
                ]);

                if ($validated['order_type'] === 'dine_in' && $table) {

                    $table->update([
                        'status' => 'occupied',
                        'occupied_by' => $order->id,
                        'occupied_since' => now(),
                    ]);
                }

                return $order->load(['orderItems.menuItem','customer','table']);
            });

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order
            ],201);

        } catch (ValidationException $e) {

            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ],422);

        } catch (\Exception $e) {

            Log::error('Order creation failed: '.$e->getMessage());

            return response()->json([
                'message' => 'Order creation failed'
            ],500);
        }
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Order::with([
            'orderItems.menuItem',
            'customer',
            'table',
            'user'
        ])->findOrFail($id);

        return response()->json($order);
    }

    /**
     * Update order status
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);

        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Order status updated',
            'order' => $order
        ]);
    }

    /**
     * Delete order
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        DB::transaction(function () use ($order) {

            OrderItem::where('order_id',$order->id)->delete();

            $order->delete();
        });

        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }
}