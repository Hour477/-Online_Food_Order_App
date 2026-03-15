<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Reports hub – links to Orders and Income reports.
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Payments list (used by resource route admin.payment.index).
     */
    public function paymentIndex()
    {
        $payments = Payment::with('order')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function orders(){
        return view('admin.reports.orders', [
            'orders' => Order::latest()->get()
        ]);
    }

    public function income(){
        $income = Payment::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        return view('admin.reports.income', compact('income'));
    }
    
}