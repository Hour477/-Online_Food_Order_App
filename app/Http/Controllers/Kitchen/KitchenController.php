<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;

class KitchenController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems.menuItem', 'table'])
            ->whereIn('status', ['pending', 'preparing', 'ready', 'completed'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('orders.kitchen', compact('orders'));
    }
}
