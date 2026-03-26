<?php

namespace App\Http\Controllers\CustomerOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class customerOrderController extends Controller
{
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
            ->with(['orderItems.menuItem', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform orders for the view
        $orders = $dbOrders->map(function ($order) {
            // Get the first payment method
            $paymentMethod = $order->payments->first()?->payment_method ?? 'cash';

            // Extract delivery address from notes
            $notes = $order->notes ?? '';
            $address = '';
            if (preg_match('/Delivery to: (.+)/', $notes, $matches)) {
                $address = $matches[1];
            }

            // Transform order items
            $items = $order->orderItems->map(function ($item) {
                return [
                    'id' => $item->menu_item_id,
                    'name' => $item->menuItem->name ?? 'Unknown Item',
                    'qty' => $item->quantity,
                    'price' => $item->price,
                    'image' => $item->menuItem->image ?? null,
                    'display_image' => $item->menuItem->display_image ?? \App\Helpers\DisplayImageHelper::get(null),
                ];
            })->toArray();

            return [
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
