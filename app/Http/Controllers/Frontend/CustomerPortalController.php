<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerPortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $customerId = (int) $request->session()->get('frontend_customer_id');
        $customer = Customer::findOrFail($customerId);

        $orders = Order::with('orderItems.menuItem')
            ->where('customer_id', $customerId)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('frontend.dashboard', compact('customer', 'orders'));
    }

    public function track(Request $request, Order $order)
    {
        $customerId = (int) $request->session()->get('frontend_customer_id');
        abort_unless($order->customer_id === $customerId, 403);
        abort_unless($order->order_type === 'delivery', 404);

        $customer = Customer::findOrFail($customerId);

        return view('frontend.track', compact('order', 'customer'));
    }
}

