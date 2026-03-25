<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;

class OrderItemsController extends Controller
{
    public function create(Order $order)
    {
        
       $categories = Category::where('status', 1)->get();
        $order_types = ['dine_in', 'takeaway', 'delivery'];
        $customers = Customer::all();
        
         
        $menuItems = MenuItem::where('status', 'available')->get();
        return view('admin.order_items.create', compact('order', 'menuItems', 'categories', 'order_types', 'customers'));
        
    }

    public function store(Request $request, Order $order)
    {

        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $menu = MenuItem::findOrFail($request->menu_item_id);

        OrderItem::create([
            'order_id' => $order->id,
            'menu_item_id' => $menu->id,
            'quantity' => $request->quantity, // Fixed naming
            'price' => $menu->price,
            'subtotal' => $menu->price * $request->quantity,
        ]);

        $this->updateOrderTotal($order);

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Item added');
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderId = $orderItem->order_id;
        $orderItem->delete();
        
        $this->updateOrderTotal(Order::find($orderId));
        
        return back()->with('success', 'Item removed');
    }

    private function updateOrderTotal(Order $order)
    {
        $subtotal = $order->orderItems()->sum('subtotal');
        $tax = $subtotal * 0.10;
        $total = $subtotal + $tax;

        $order->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total_amount' => $total,
        ]);
    }

    public function updateQty(Request $request, OrderItem $orderItem)
    {
        $request->validate([
            'action' => 'required|in:increase,decrease',
        ]);

        if ($request->action === 'increase') {
            $orderItem->quantity += 1;
        }

        if ($request->action === 'decrease' && $orderItem->quantity > 1) {
            $orderItem->quantity -= 1;
        }

        $orderItem->subtotal = $orderItem->price * $orderItem->quantity;
        $orderItem->save();
        $this->updateOrderTotal($orderItem->order);

        return back()->with('success', 'Item quantity updated');
    }

    // Backward-compatible entrypoint if old routes/forms still point to update().
    public function update(Request $request, OrderItem $orderItem)
    {
        return $this->updateQty($request, $orderItem);
    }
   
}
