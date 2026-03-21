<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard index.
     */
    public function index()
    {
        // Today's stats
        $ordersToday = Order::whereDate('created_at', today())->count();
        $incomeToday = Order::whereDate('created_at', today())
            ->whereIn('status', ['completed', 'delivered'])
            ->sum('total_amount');
        
        // Total stats
        $totalOrders = Order::where('status', 'completed')->count();
        $totalRevenue = Order::whereIn('status', ['completed', 'delivered'])->sum('total_amount');
        $totalCustomers = Customer::count();
        $totalMenuItems = MenuItem::where('status', 'available')->count();
        
        // Tables
        $totalAvailableTable = Table::where('status', 'available')->count();
        $totalTables = Table::count();
        $occupiedTables = Table::where('status', 'occupied')->count();
        
        // Pending orders count
        $pendingOrders = Order::whereIn('status', ['pending', 'confirmed'])->count();
        
        // Recent orders (last 5)
        $recentOrders = Order::with(['customer', 'orderItems.menuItem'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Top selling items this week
        $topSellingItems = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->whereBetween('order_items.created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
        
        // Orders by status for quick overview
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
        
        // Weekly revenue trend (last 7 days)
        $weeklyRevenue = Order::whereBetween('created_at', [now()->subDays(7), now()])
            ->whereIn('status', ['completed', 'delivered'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('admin.dashboard.index', compact(
            'totalOrders',
            'ordersToday',
            'incomeToday',
            'totalRevenue',
            'totalCustomers',
            'totalMenuItems',
            'totalAvailableTable',
            'totalTables',
            'occupiedTables',
            'pendingOrders',
            'recentOrders',
            'topSellingItems',
            'ordersByStatus',
            'weeklyRevenue'
        ));
    }

    /**
     * Get total orders statistics.
     */
    public function totalOrders(){
        $stats = [
            'today' => Order::whereDate('created_at', today())->count(),
            'this_week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Order::whereMonth('created_at', now()->month)->count(),
            'total' => Order::count(),
        ];
        
        return response()->json($stats);
    }
}

