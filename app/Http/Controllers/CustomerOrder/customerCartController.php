<?php

namespace App\Http\Controllers\CustomerOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class customerCartController extends Controller
{
    /** Full catalogue (mirrors MenuController — in a real app use a shared service / model) */
    private function catalogue(): array
    {
        return [
            1  => ['id'=>1,'category'=>'meals',   'name'=>'Classic Beef Burger',    'price'=>12.99,'image'=>'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&q=80'],
            2  => ['id'=>2,'category'=>'meals',   'name'=>'Margherita Pizza',        'price'=>14.99,'image'=>'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=300&q=80'],
            3  => ['id'=>3,'category'=>'meals',   'name'=>'Grilled Salmon Bowl',     'price'=>16.49,'image'=>'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=300&q=80'],
            4  => ['id'=>4,'category'=>'meals',   'name'=>'Chicken Caesar Wrap',     'price'=>10.99,'image'=>'https://images.unsplash.com/photo-1550304943-4f24f54ddde9?w=300&q=80'],
            5  => ['id'=>5,'category'=>'meals',   'name'=>'Pasta Carbonara',         'price'=>13.49,'image'=>'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=300&q=80'],
            6  => ['id'=>6,'category'=>'meals',   'name'=>'Veggie Buddha Bowl',      'price'=>11.99,'image'=>'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&q=80'],
            7  => ['id'=>7,'category'=>'drinks',  'name'=>'Mango Lassi',             'price'=>5.49, 'image'=>'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&q=80'],
            8  => ['id'=>8,'category'=>'drinks',  'name'=>'Cold Brew Coffee',        'price'=>4.99, 'image'=>'https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=300&q=80'],
            9  => ['id'=>9,'category'=>'drinks',  'name'=>'Watermelon Mint Cooler',  'price'=>4.49, 'image'=>'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=300&q=80'],
            10 => ['id'=>10,'category'=>'drinks', 'name'=>'Classic Lemonade',        'price'=>3.99, 'image'=>'https://images.unsplash.com/photo-1621263764928-df1444c5e859?w=300&q=80'],
            11 => ['id'=>11,'category'=>'desserts','name'=>'Chocolate Lava Cake',    'price'=>7.99, 'image'=>'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=300&q=80'],
            12 => ['id'=>12,'category'=>'desserts','name'=>'New York Cheesecake',    'price'=>6.99, 'image'=>'https://images.unsplash.com/photo-1524351199678-941a58a3df50?w=300&q=80'],
            13 => ['id'=>13,'category'=>'desserts','name'=>'Crème Brûlée',           'price'=>6.49, 'image'=>'https://images.unsplash.com/photo-1470124182917-cc6e71b22ecc?w=300&q=80'],
            14 => ['id'=>14,'category'=>'desserts','name'=>'Tiramisu',               'price'=>6.99, 'image'=>'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=300&q=80'],
        ];
    }

    public function index()
    {
        $cart = session('cart', []);
        return view('customerOrder.cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate(['item_id' => 'required|integer', 'quantity' => 'required|integer|min:1|max:99']);

        $id  = $request->item_id;
        $qty = $request->quantity;
        $cat = $this->catalogue();

        if (!isset($cat[$id])) {
            return back()->with('error', 'Item not found.');
        }

        $cart = session('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty'] = min(99, $cart[$id]['qty'] + $qty);
        } else {
            $cart[$id] = array_merge($cat[$id], ['qty' => $qty]);
        }

        session(['cart' => $cart]);
        return back()->with('success', "{$cat[$id]['name']} added to your basket!");
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
        $orders  = session('orders', []);
        $orderId = $request->order_id;
        $order   = collect($orders)->firstWhere('id', $orderId);

        if (!$order) return back()->with('error', 'Order not found.');

        $cart = [];
        foreach ($order['items'] as $item) {
            $cart[$item['id']] = $item;
        }
        session(['cart' => $cart]);
        return redirect()->route('customerOrder.cart.index')->with('success', 'Items added to your basket!');
    }
}
