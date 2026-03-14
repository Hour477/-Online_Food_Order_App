<?php

namespace App\Http\Controllers\CustomerOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class customerOrderController extends Controller
{
    public function history()
    {
        // Retrieve from session (swap for DB query in production)
        $orders = array_reverse(session('orders', []));
        return view('customerOrder.orders.history', compact('orders'));
    }
}
