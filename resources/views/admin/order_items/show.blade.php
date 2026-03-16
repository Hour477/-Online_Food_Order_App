<h3 class="font-bold mt-6 mb-4 text-lg">Order Items</h3>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                <th class="px-6 py-3 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($order->orderItems as $item)
            <tr>
                <td class="px-6 py-4">{{ $item->menuItem->name }}</td>
                <td class="px-6 py-4">{{ $item->quantity }}</td>
                <td class="px-6 py-4">${{ number_format($item->price, 2) }}</td>
                <td class="px-6 py-4 font-medium">${{ number_format($item->subtotal, 2) }}</td>
                <td class="px-6 py-4 text-right">
                    <form action="{{ route('admin.order-items.destroy', $item->id) }}" method="POST" class="inline">
                        @csrf 
                        @method('DELETE')
                        <button class="text-red-600 hover:text-red-900 text-sm font-medium">Remove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-50">
             <tr>
                <td colspan="3" class="px-6 py-4 text-right font-bold">Total:</td>
                <td class="px-6 py-4 font-bold text-indigo-600">${{ number_format($order->total_amount, 2) }}</td>
                <td></td>
             </tr>
        </tfoot>
    </table>
</div>

<div class="mt-4">
    <a href="{{ route('admin.order-items.create', $order->id) }}"
       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
       + Add Items
    </a>
</div>