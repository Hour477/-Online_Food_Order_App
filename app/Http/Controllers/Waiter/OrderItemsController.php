<?php

namespace App\Http\Controllers\Waiter;

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
        $tables = Table::where('status', 'available')->get();
         
        $menuItems = MenuItem::where('status', 'available')->get();
        return view('order_items.create', compact('order', 'menuItems', 'categories', 'order_types', 'customers', 'tables'));
        
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

        return redirect()->route('orders.show', $order->id)->with('success', 'Item added');
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
        $total = $order->orderItems()->sum('subtotal');
        $order->update(['total_amount' => $total]);
    }

    public function update(Request $request, OrderItem $id)
    {
        $item = OrderItem::findOrFail($id);

            if($request->action == 'inrease')
                {
                    $item->quantity += 1;
                }

            if($request->action == 'decrease' && $item->quantity > 1)
                {
                    $item->quantity -= 1;
                }
            $item->save();
            $item->refresh(); // Ensure the item is reloaded with updated quantity
            $item->update(['subtotal' => $item->price * $item->quantity]);
            $this->updateOrderTotal($item->order);

            return back()->with('success', 'Item quantity updated');
    }
   
}