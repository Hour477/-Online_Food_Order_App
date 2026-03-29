<?php

namespace App\Http\Controllers\CustomerOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class customerCartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        return view('customerOrder.cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate(['item_id' => 'required|exists:menu_items,id', 'quantity' => 'required|integer|min:1|max:99']);

        $id  = $request->item_id;
        $qty = $request->quantity;
        
        $menuItem = MenuItem::findOrFail($id);

        $cart = session('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty'] = min(99, $cart[$id]['qty'] + $qty);
        } else {
            $cart[$id] = [
                'id' => $menuItem->id,
                'name' => $menuItem->name,
                'price' => $menuItem->price,
                'image' => $menuItem->image,
                'display_image' => $menuItem->display_image,
                'category' => $menuItem->category->name,
                'qty' => $qty
            ];
        }

        session(['cart' => $cart]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$menuItem->name} added to your basket!",
                'cart_count' => array_sum(array_column($cart, 'qty'))
            ]);
        }

        return back()->with('success', "{$menuItem->name} added to your basket!");
    }

    public function update(Request $request)
    {
        $id     = $request->item_id;
        $action = $request->action; // 'inc' | 'dec'
        $cart   = session('cart', []);

        if (!isset($cart[$id])) return back();

        if ($action === 'inc') {
            $cart[$id]['qty'] = min(99, $cart[$id]['qty'] + 1);
        } else {
            $cart[$id]['qty']--;
            if ($cart[$id]['qty'] <= 0) unset($cart[$id]);
        }

        session(['cart' => $cart]);
        return back();
    }

    public function remove(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->item_id]);
        session(['cart' => $cart]);
        return back()->with('success', 'Item removed from basket.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Basket cleared.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order_id' => 'required|integer|exists:orders,id']);

        $customer = Customer::where('user_id', Auth::id())->first();

        if (!$customer) {
            return back()->with('error', 'Customer record not found.');
        }

        // Load the order from DB, verifying it belongs to this customer
        $order = Order::with('orderItems.menuItem')
            ->where('id', $request->order_id)
            ->where('customer_id', $customer->id)
            ->where('status', 'completed')
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found or cannot be reordered.');
        }

        $cart = session('cart', []);
        foreach ($order->orderItems as $item) {
            $menuItem = $item->menuItem;
            if (!$menuItem) continue;

            $id = $menuItem->id;
            if (isset($cart[$id])) {
                $cart[$id]['qty'] = min(99, $cart[$id]['qty'] + $item->quantity);
            } else {
                $cart[$id] = [
                    'id'            => $menuItem->id,
                    'name'          => $menuItem->name,
                    'price'         => $menuItem->price,
                    'image'         => $menuItem->image,
                    'display_image' => $menuItem->display_image,
                    'category'      => $menuItem->category?->name ?? '',
                    'qty'           => $item->quantity,
                ];
            }
        }

        session(['cart' => $cart]);
        return redirect()->route('customerOrder.cart.index')->with('success', 'Items added to your basket!');
    }
}
