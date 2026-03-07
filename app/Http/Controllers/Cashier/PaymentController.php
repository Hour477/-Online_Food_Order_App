<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        $payment = Payment::create($validated);
        
        // Update order status to paid/completed
        $order = Order::find($validated['order_id']);
        $order->update(['status' => 'completed']);
        
        // Free up the table
        if($order->table) {
            $order->table->update(['status' => 'available']);
        }

        return redirect()->route('orders.index')->with('success', 'Payment processed successfully!');
    }

    public function checkout(Order $order){
        return view('payments.checkout' , compact('order'));
    }
}