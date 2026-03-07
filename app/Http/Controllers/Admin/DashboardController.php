<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;

class DashboardController extends Controller
{
    /**
     * Display the dashboard index.
     */
    public function index()
    {
        $totalOrders = Order::where('status', 'completed')->count();
        
        // Today Orders
        $ordersToday = Order::whereDate('created_at', today())->count();

        $totalAvailableTable = Table::where('status', 'available')->count();

        return view('dashboard.index', compact('totalOrders', 'ordersToday', 'totalAvailableTable'));
    }

    // Total Order
    public function totalOrders(){

    }
    
}

