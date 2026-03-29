@extends('admin.layouts.app')
@section('title', 'Order Details')

@section('content')
<div class="mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Order Details</h3>
            <p class="text-sm text-gray-500 mt-1">
                Viewing order #{{ $orders->order_no }}
                @if($orders->created_at && $orders->created_at->diffInHours(now()) < 12)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-500 text-white ml-2">NEW</span>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.all') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Orders
            </a>
            
            <a href="{{ route('admin.orders.receipt', $orders->id) }}"
               target="_blank"
               class="ml-4 px-4 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-print"></i>
                Print Receipt
            </a>
            
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Order Info Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                <h5 class="text-lg font-semibold text-gray-900 mb-6 border-b pb-4">Order Information</h5>
                
                <div class="space-y-5">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Order Number</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $orders->order_no }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Status</p>
                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full
                            {{ $orders->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $orders->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $orders->status === 'delivered' ? 'bg-indigo-100 text-indigo-800' : '' }}
                            {{ $orders->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $orders->status === 'refunded' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $orders->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($orders->status) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Order Type</p>
                        <p class="text-sm text-gray-900 font-medium uppercase">{{ str_replace('_', ' ', $orders->order_type ?? 'N/A') }}</p>
                    </div>

                    

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Customer</p>
                        <p class="text-sm text-gray-900 font-medium">
                            {{ $orders->customer?->name ?? 'Walk-in' }}
                            @if($orders->customer?->phone)
                                <span class="text-gray-500">({{ $orders->customer->phone }})</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Created By</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $orders->user?->name ?? 'System' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Created At</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $orders->created_at->format('M d, Y h:i A') }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-1">Special Notes</p>
                        <p class="text-sm text-gray-900">{{ $orders->notes ?? 'No special requests' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Items Card --}}
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6 border-b pb-4">
                    @if($orders->status === 'pending')

                    <h5 class="text-lg font-semibold text-gray-900">Ordered Items</h5>
                    <form action="{{ route('admin.orders.items.store', $orders->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <select name="menu_item_id" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500" required>
                            <option value="">Select item</option>
                            @foreach($menuItems as $menuItem)
                                <option value="{{ $menuItem->id }}">{{ $menuItem->name }} (${{ number_format($menuItem->price, 2) }})</option>
                            @endforeach
                        </select>
                        <input type="number" name="quantity" min="1" value="1" class="w-16 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500" required>
                        <button type="submit" class="px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors">
                            Add
                        </button>
                    </form>
                    @endif
                </div>

                @if($orders->orderItems->isEmpty())
                    <div class="text-center py-12 text-gray-500">
                        <p class="text-lg">No items added yet</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($orders->orderItems as $item)
                                    <tr>
                                        <td class="px-4 py-4 text-sm text-gray-900">{{ $item->menuItem?->name ?? 'Unknown Item' }}</td>
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <form action="{{ route('admin.order-items.qty', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="action" value="decrease">
                                                    <button type="submit" class="w-6 h-6 text-xs border border-gray-300 rounded hover:bg-gray-100">-</button>
                                                </form>
                                                <span class="text-sm text-gray-900 font-medium">{{ $item->quantity }}</span>
                                                <form action="{{ route('admin.order-items.qty', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="action" value="increase">
                                                    <button type="submit" class="w-6 h-6 text-xs border border-gray-300 rounded hover:bg-gray-100">+</button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900 text-right">${{ number_format($item->price, 2) }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900 font-medium text-right">${{ number_format($item->subtotal ?? $item->price * $item->quantity, 2) }}</td>
                                        <td class="px-4 py-4 text-center">
                                            <form action="{{ route('admin.order-items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remove this item?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Totals --}}
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Payment Method</span>
                        <span class="text-gray-900 font-medium uppercase">{{ $orders->payment_method ?? 'N/A' }}</span>
                    </div>
                    
                    @if($orders->payments->isNotEmpty())
                        @foreach($orders->payments as $payment)
                            <div class="bg-gray-50 rounded-lg p-4 mb-3">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-medium text-gray-500 uppercase">Payment #{{ $payment->id }}</span>
                                    <form action="{{ route('admin.payment.update-status', $payment->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        @if($payment->status === 'pending' || $payment->status === 'failed' || $payment->status === 'refunded')
                                        <select name="status" class=" px-2 py-1 text-xs border border-gray-300 rounded focus:ring-amber-500 focus:border-amber-500" {{ $payment->status === 'paid' || $payment->status === 'refunded' ? 'disabled' : '' }}>
                                            <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ $payment->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="failed" {{ $payment->status === 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="refunded" {{ $payment->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                        <button type="submit" class="px-3 py-1 bg-amber-600 text-white text-xs font-medium rounded hover:bg-amber-700 transition-colors">
                                            Update
                                        </button>
                                        @endif
                                    </form>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <span class="text-gray-500">Amount:</span>
                                        <span class="text-gray-900 font-medium ml-1">${{ number_format($payment->paid_amount, 2) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Status:</span>
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full
                                            {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $payment->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $payment->status === 'refunded' ? 'bg-purple-100 text-purple-800' : '' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Paid at:</span>
                                        <span class="text-gray-900">{{ $payment->paid_at ? $payment->paid_at->format('M d, Y h:i A') : 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Method:</span>
                                        <span class="text-gray-900 font-medium uppercase">{{ $payment->payment_method }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-sm text-gray-500 italic mt-2">No payment recorded yet</div>
                    @endif
                    
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="text-gray-900 font-medium">${{ number_format($orders->subtotal ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Tax (10%)</span>
                        <span class="text-gray-900 font-medium">${{ number_format($orders->tax ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-4 border-t border-gray-200 mt-4">
                        <span class="text-gray-900">Grand Total</span>
                        <span class="text-amber-600">${{ number_format($orders->total_amount ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Status Update Card --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 mt-6">
                <h5 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-4">Update Status</h5>
                <form action="{{ route('admin.orders.updateStatus', $orders->id) }}" method="POST" class="flex items-center gap-4">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="flex-1 px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500" {{ $orders->status === 'completed' ? 'disabled' : '' }}>
                        
                            <option value="pending" {{ $orders->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $orders->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $orders->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="refunded" {{ $orders->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            <option value="cancelled" {{ $orders->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        
                    </select>
                    <button type="submit" class="px-6 py-2.5 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
