<?php

namespace App\Http\Controllers\CustomerOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class customerCheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customerOrder.cart.index')->with('error', 'Your basket is empty.');
        }
        return view('customerOrder.checkout.index', compact('cart'));
    }

    public function place(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name'  => 'required|string|max:60',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:200',
            'city'       => 'required|string|max:60',
            'zip'        => 'required|string|max:20',
            'payment'    => 'required|in:cod,card,wallet',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customerOrder.cart.index')->with('error', 'Your basket is empty.');
        }

        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));
        $total    = $subtotal + 2.99 + ($subtotal * 0.08);

        $order = [
            'id'         => strtoupper(substr(uniqid(), -6)),
            'items'      => array_values($cart),
            'subtotal'   => $subtotal,
            'total'      => $total,
            'address'    => "{$request->address}, {$request->city} {$request->zip}",
            'payment'    => $request->payment,
            'notes'      => $request->notes,
            'status'     => 'pending',
            'created_at' => now()->toDateTimeString(),
        ];

        // Persist to session-based order history
        $orders   = session('orders', []);
        $orders[] = $order;
        session(['orders' => $orders]);

        // Store current order for confirmation page
        session(['last_order' => $order]);

        // Clear cart
        session()->forget('cart');

        return redirect()->route('customerOrder.checkout.confirmation');
    }

    public function confirmation()
    {
        $order = session('last_order');
        if (!$order) {
            return redirect()->route('menu.index');
        }
        return view('customerOrder.checkout.confirmation', compact('order'));
    }
}
