<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;


class ReportController extends Controller
{
    public function index()
    {
        return view('payments.index');
    }

    public function orders(){
        return view('reports.orders', [
            'orders' => Order::latest()->get()
        ]);
    }

    public function income(){
        $income = Payment::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        return view('reports.income', compact('income'));
    }
    
}