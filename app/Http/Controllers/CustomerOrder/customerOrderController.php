<?php

namespace App\Http\Controllers\CustomerOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\MenuItemRating;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class customerOrderController extends Controller
{
    /**
     * Submit a rating for a menu item.
     */
    public function rate(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'menu_item_id' => 'required|exists:menu_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        $customer = Customer::where('user_id', Auth::id())->first();

        if (!$customer) {
            ToastMagic::error('Customer record not found.');
            return back();
        }

        // Verify the customer actually ordered this item in this order
        $order = Order::where('id', $validated['order_id'])
            ->where('customer_id', $customer->id)
            ->where('status', 'completed') // Only completed orders can be rated
            ->first();

        if (!$order) {
            ToastMagic::error('Order not found or not eligible for rating.');
            return back();
        }

        $orderedItem = $order->orderItems()->where('menu_item_id', $validated['menu_item_id'])->first();
        if (!$orderedItem) {
            ToastMagic::error('Item not found in this order.');
            return back();
        }

        // Create or update the rating
        $rating = MenuItemRating::updateOrCreate(
            [
                'order_id' => $validated['order_id'],
                'menu_item_id' => $validated['menu_item_id'],
                'customer_id' => $customer->id
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment']
            ]
        );

        // Update the average rating in MenuItem
        $menuItem = MenuItem::find($validated['menu_item_id']);
        $menuItem->updateAverageRating();

        ToastMagic::success(__('app.thank_you_rating'));
        return back();
    }

    /**
     * Show the dedicated rating page for a single completed order.
     */
    public function showRatePage(Order $order)
    {
        $customer = Customer::where('user_id', Auth::id())->first();

        if (!$customer || $order->customer_id !== $customer->id) {
            abort(403, 'Unauthorized.');
        }

        if ($order->status !== 'completed') {
            return redirect()->route('customerOrder.orders.history')
                ->with('error', 'Only completed orders can be rated.');
        }

        $order->load([
            'orderItems.menuItem',
            'menuItemRatings' => fn($q) => $q->where('customer_id', $customer->id),
        ]);

        $ratingsByMenuItem = $order->menuItemRatings->keyBy('menu_item_id');

        $items = $order->orderItems->map(function ($item) use ($ratingsByMenuItem) {
            $existingRating = $ratingsByMenuItem->get($item->menu_item_id);
            return [
                'id'            => $item->menu_item_id,
                'name'          => $item->menuItem->name ?? 'Unknown Item',
                'qty'           => $item->quantity,
                'price'         => $item->price,
                'display_image' => $item->menuItem?->display_image ?? asset('assets/img/placeholder.png'),
                'rating'        => $existingRating?->rating,
                'comment'       => $existingRating?->comment,
                'has_rating'    => $existingRating !== null,
            ];
        });

        $orderData = [
            'db_id'      => $order->id,
            'id'         => $order->order_no,
            'status'     => $order->status,
            'subtotal'   => $order->subtotal,
            'total'      => $order->total_amount,
            'created_at' => $order->created_at,
            'items'      => $items,
        ];

        return view('customerOrder.orders.rate', ['order' => $orderData]);
    }

    public function history()
    {
        // Get the authenticated user's customer record
        $customer = Customer::where('user_id', Auth::id())->first();

        if (!$customer) {
            $orders = [];
            return view('customerOrder.orders.history', compact('orders'));
        }

        // Fetch orders from database
        $dbOrders = Order::where('customer_id', $customer->id)
            ->with([
                'orderItems.menuItem',
                'payments',
                'menuItemRatings' => fn ($query) => $query->where('customer_id', $customer->id),
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform orders for the view
        $orders = $dbOrders->map(function ($order) {
            // Get the payment method with status consideration (from model attribute)
            $paymentMethod = $order->payment_method;

            // Extract delivery address from notes
            $notes = $order->notes ?? '';
            $address = '';
            if (preg_match('/Delivery to: (.+)/', $notes, $matches)) {
                $address = $matches[1];
            }

            $ratingsByMenuItem = $order->menuItemRatings
                ->keyBy('menu_item_id');

            // Transform order items
            $items = $order->orderItems->map(function ($item) use ($ratingsByMenuItem) {
                $existingRating = $ratingsByMenuItem->get($item->menu_item_id);

                return [
                    'id' => $item->menu_item_id,
                    'name' => $item->menuItem->name ?? 'Unknown Item',
                    'qty' => $item->quantity,
                    'price' => $item->price,
                    'image' => $item->menuItem->image ?? null,
                    'display_image' => $item->menuItem?->display_image ?? asset('assets/img/placeholder.png'),
                    'rating' => $existingRating?->rating,
                    'comment' => $existingRating?->comment,
                    'has_rating' => $existingRating !== null,
                ];
            })->toArray();

            return [
                'db_id' => $order->id,
                'id' => $order->order_no,
                'status' => $order->status,
                'subtotal' => $order->subtotal,
                'total' => $order->total_amount,
                'items' => $items,
                'address' => $address,
                'payment' => $paymentMethod,
                'created_at' => $order->created_at,
            ];
        })->toArray();

        return view('customerOrder.orders.history', compact('orders'));
    }
}
