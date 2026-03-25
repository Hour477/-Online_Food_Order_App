<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Reports Hub - Overview of all reporting metrics
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfDay()->format('Y-m-d'));

        // Overall Stats for the period
        $stats = [
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->whereIn('status', ['completed', 'delivered'])
                ->sum('total_amount'),
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])->count(),
            'avg_order_value' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->whereIn('status', ['completed', 'delivered'])
                ->avg('total_amount') ?? 0,
            'cancelled_orders' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->where('status', 'cancelled')
                ->count(),
        ];

        // Orders by Status
        $ordersByStatus = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Revenue Trend (Daily)
        $revenueTrend = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->whereIn('status', ['completed', 'delivered'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly Sales for Current Year vs Previous Year (Multiple Lines)
        $currentYear = now()->year;
        $prevYear = $currentYear - 1;

        $monthlySales = Order::whereIn('status', ['completed', 'delivered'])
            ->whereIn(DB::raw('YEAR(created_at)'), [$currentYear, $prevYear])
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Format for Chart.js (Array of 12 months for each year)
        $monthlyTrend = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'current' => array_fill(0, 12, 0),
            'previous' => array_fill(0, 12, 0)
        ];

        foreach ($monthlySales as $sale) {
            $monthIndex = $sale->month - 1;
            if ($sale->year == $currentYear) {
                $monthlyTrend['current'][$monthIndex] = (float)$sale->revenue;
            } else {
                $monthlyTrend['previous'][$monthIndex] = (float)$sale->revenue;
            }
        }

        return view('admin.reports.index', compact('stats', 'ordersByStatus', 'revenueTrend', 'monthlyTrend', 'startDate', 'endDate'));
    }

    /**
     * Sales Report - Detailed breakdown of item and category sales
     */
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Top Selling Items
        $topItems = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.subtotal) as total_sales'))
            ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->whereIn('orders.status', ['completed', 'delivered'])
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Sales by Category
        $categorySales = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->join('categories', 'menu_items.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as total_sales'))
            ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->whereIn('orders.status', ['completed', 'delivered'])
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sales')
            ->get();

        return view('admin.reports.sales', compact('topItems', 'categorySales', 'startDate', 'endDate'));
    }

    /**
     * Orders Report - Breakdown by type and status
     */
    public function orders(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $ordersByType = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select('order_type', DB::raw('count(*) as count'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('order_type')
            ->get();

        $recentOrders = Order::with('customer')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->latest()
            ->paginate(15);

        return view('admin.reports.orders', compact('ordersByType', 'recentOrders', 'startDate', 'endDate'));
    }

    /**
     * Income Report - Financial breakdown
     */
    public function income(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $paymentMethods = Payment::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->where('status', 'paid')
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('SUM(paid_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        $dailyIncome = Payment::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->where('status', 'paid')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(paid_amount) as total'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.reports.income', compact('paymentMethods', 'dailyIncome', 'startDate', 'endDate'));
    }

    /**
     * Export Report to PDF
     */
    public function exportPdf(Request $request, $type)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Data gathering logic depends on type (sales, orders, income)
        $data = $this->getReportData($type, $startDate, $endDate);
        $data['type'] = $type;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        $pdf = Pdf::loadView('admin.reports.pdf.' . $type, $data);
        return $pdf->download($type . '-report-' . $startDate . '-to-' . $endDate . '.pdf');
    }

    /**
     * Export Report to CSV
     */
    public function exportCsv(Request $request, $type)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $data = $this->getReportData($type, $startDate, $endDate);

        $filename = $type . '-report-' . $startDate . '-to-' . $endDate . '.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Logic to write CSV headers and rows based on type
        $this->writeCsv($handle, $type, $data);

        fclose($handle);
        exit;
    }

    /**
     * Helper to gather data for exports
     */
    private function getReportData($type, $startDate, $endDate)
    {
        switch ($type) {
            case 'sales':
                return [
                    'topItems' => DB::table('order_items')
                        ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.subtotal) as total_sales'))
                        ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
                        ->whereIn('orders.status', ['completed', 'delivered'])
                        ->groupBy('menu_items.id', 'menu_items.name')
                        ->orderByDesc('total_qty')
                        ->get(),
                    'categorySales' => DB::table('order_items')
                        ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                        ->join('categories', 'menu_items.category_id', '=', 'categories.id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->select('categories.name', DB::raw('SUM(order_items.subtotal) as total_sales'))
                        ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
                        ->whereIn('orders.status', ['completed', 'delivered'])
                        ->groupBy('categories.id', 'categories.name')
                        ->get()
                ];
            case 'orders':
                return [
                    'ordersByType' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                        ->select('order_type', DB::raw('count(*) as count'), DB::raw('SUM(total_amount) as revenue'))
                        ->groupBy('order_type')
                        ->get(),
                    'ordersByStatus' => Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                        ->select('status', DB::raw('count(*) as count'))
                        ->groupBy('status')
                        ->get()
                ];
            case 'income':
                return [
                    'paymentMethods' => Payment::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                        ->where('status', 'paid')
                        ->select('payment_method', DB::raw('count(*) as count'), DB::raw('SUM(paid_amount) as total'))
                        ->groupBy('payment_method')
                        ->get(),
                    'dailyIncome' => Payment::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                        ->where('status', 'paid')
                        ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(paid_amount) as total'))
                        ->groupBy('date')
                        ->orderBy('date', 'desc')
                        ->get()
                ];
        }
        return [];
    }

    /**
     * Helper to write CSV data
     */
    private function writeCsv($handle, $type, $data)
    {
        switch ($type) {
            case 'sales':
                fputcsv($handle, ['Item Name', 'Quantity Sold', 'Total Sales']);
                foreach ($data['topItems'] as $item) {
                    fputcsv($handle, [$item->name, $item->total_qty, number_format($item->total_sales, 2)]);
                }
                break;
            case 'orders':
                fputcsv($handle, ['Order Type', 'Count', 'Revenue']);
                foreach ($data['ordersByType'] as $typeData) {
                    fputcsv($handle, [ucfirst($typeData->order_type), $typeData->count, number_format($typeData->revenue, 2)]);
                }
                break;
            case 'income':
                fputcsv($handle, ['Date', 'Total Income']);
                foreach ($data['dailyIncome'] as $day) {
                    fputcsv($handle, [$day->date, number_format($day->total, 2)]);
                }
                break;
        }
    }
}