<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Payment;
use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {

        $cart = session('cart', []);
        // Check if cart is empty before proceeding
        if (empty($cart)) {
            return redirect()->route('admin.cart.index')->with('error', 'Your basket is empty.');
        }
        
        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
        $tax = $subtotal * 0.10; // Match admin's 10% tax
        $total = $subtotal + $tax;

        // Get current customer if logged in
        // $customer = Customer::where('user_id', Auth::id())->first();
        // Admin order for guest customer

        $user = Auth::user();
        
        return view('admin.checkout.index', compact('cart', 'subtotal', 'tax', 'total', 'user'));
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
        // customer defualt walk-in order
        

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('admin.cart.index')->with('error', 'Your basket is empty.');
        }

        try {
            $order = DB::transaction(function () use ($request, $cart) {
                $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
                $tax = $subtotal * 0.10;
                $total = $subtotal + $tax;

                // 1. Find or Create Customer
                // Always link to logged-in user (auth middleware is active)
                $customer = Customer::updateOrCreate(
                    ['user_id' => Auth::id()],
                    [
                        'name'    => $request->first_name . ' ' . $request->last_name,
                        'email'   => Auth::user()->email,
                        'phone'   => $request->phone,
                        'address' => $request->address,
                        'city'    => $request->city
                    ]
                );

                // 2. Create Order
                $order = Order::create([
                    'order_no'     => 'ORD-' . strtoupper(Str::random(6)),
                    'customer_id'  => $customer->id,
                    'order_type'   => 'takeaway',
                    'user_id'      => Auth::id(),
                    'status'       => 'pending',
                    'notes'        => $request->notes . "\nDelivery to: {$request->first_name} {$request->last_name}, {$request->address}, {$request->city} {$request->zip}, Phone: {$request->phone}",
                    'subtotal'     => $subtotal,
                    'tax'          => $tax,
                    'total_amount' => $total,
                ]);

                // 3. Create Order Items
                foreach ($cart as $item) {
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'menu_item_id' => $item['id'],
                        'quantity'     => $item['qty'],
                        'price'        => $item['price'],
                        'subtotal'     => $item['price'] * $item['qty'],
                    ]);
                }

                // 4. Create Payment record
                // Map frontend payment method to backend enum: cod -> cash, card -> card, wallet -> qr
                $methodMap = [
                    'cod'    => 'cash',
                    'card'   => 'card',
                    'wallet' => 'qr'
                ];
                
                Payment::create([
                    'order_id'       => $order->id,
                    'payment_method' => $methodMap[$request->payment],
                    'total_amount'   => $total,
                    'paid_amount'    => $total, // For guest checkout we assume full payment if not COD
                    'change_amount'  => 0,
                    'paid_at'        => now()
                ]);

                return $order;
            });

            // Trigger the OrderPlaced event for real-time notification
            event(new OrderPlaced($order));

            // Store current order for confirmation page
            session(['last_order' => $order->load('orderItems.menuItem')]);

            // Clear cart
            session()->forget('cart');

            return redirect()->route('admin.checkout.confirmation');

        } catch (\Exception $e) {
            Log::error('Admin checkout failed: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while placing your order. ' . $e->getMessage());
        }
    }

    public function confirmation()
    {
        $order = session('last_order');
        if (!$order) {
            return redirect()->route('admin.menu.index');
        }
        return view('admin.checkout.confirmation', compact('order'));
    }
}
