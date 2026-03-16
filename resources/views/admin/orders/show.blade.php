@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                Order #{{ $orders->order_no }}
                @if($orders->created_at && $orders->created_at->diffInHours(now()) < 12)
                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-red-500 text-white animate-pulse">
                        NEW
                    </span>
                @endif
            </h1>
            <div class="mt-2 flex items-center gap-4 text-gray-600 dark:text-gray-400">
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                    {{ $orders->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300' : '' }}
                    {{ $orders->status === 'preparing' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300' : '' }}
                    {{ $orders->status === 'ready' ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : '' }}
                    {{ $orders->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : '' }}
                    {{ $orders->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' : '' }}">
                    {{ ucfirst($orders->status) }}
                </span>
                <span>Created {{ $orders->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition">
                &larr; Back to Orders
            </a>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left: Order Info + Items -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Order Information -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-info-circle text-indigo-600"></i>
                    Order Information
                </h2>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Table</dt>
                        <dd class="mt-1 text-lg font-medium text-gray-900 dark:text-white">
                            Table {{ $orders->table?->table_number ?? '-' }}
                            ({{ $orders->table?->capacity ?? '-' }} seats)
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</dt>
                        <dd class="mt-1 text-lg font-medium text-gray-900 dark:text-white">
                            {{ $orders->customer?->name ?? 'Walk-in' }}
                            @if($orders->customer?->phone)
                                ({{ $orders->customer->phone }})
                            @endif
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created By</dt>
                        <dd class="mt-1 text-lg font-medium text-gray-900 dark:text-white">
                            {{ $orders->user?->name ?? 'System' }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Special Notes</dt>
                        <dd class="mt-1 text-gray-700 dark:text-gray-300 whitespace-pre-line">
                            {{ $orders->notes ?? 'No special requests' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Ordered Items -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-3">
                        <i class="fas fa-receipt text-indigo-600"></i>
                        Ordered Items
                    </h2>

                    <form action="{{ route('admin.orders.items.store', $orders->id) }}" method="POST" class="flex flex-wrap items-center gap-2">
                        @csrf
                        <select name="menu_item_id" class="px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Select item</option>
                            @foreach($menuItems as $menuItem)
                                <option value="{{ $menuItem->id }}">{{ $menuItem->name }} (${{ number_format($menuItem->price, 2) }})</option>
                            @endforeach
                        </select>
                        <input type="number" name="quantity" min="1" value="1" class="w-20 px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50 rounded-lg transition-colors">
                            Add Item
                        </button>
                    </form>
                </div>

                @if($orders->orderItems->isEmpty())
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-receipt text-6xl opacity-30 mb-4"></i>
                        <p class="text-lg">No items added yet</p>
                    </div>
                @else
                    <div class="space-y-5">
                        @foreach($orders->orderItems as $item)
                            <div class="flex justify-between items-start border-b border-gray-200 dark:border-gray-700 pb-5 last:border-b-0 last:pb-0">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $item->menuItem?->name ?? 'Unknown Item' }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        ${{ number_format($item->price, 2) }} x {{ $item->quantity }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-3">
                                        <form action="{{ route('admin.order-items.qty', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="px-2 py-1 text-xs border border-gray-300 rounded dark:border-gray-600">-</button>
                                        </form>
                                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ $item->quantity }}</span>
                                        <form action="{{ route('admin.order-items.qty', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="px-2 py-1 text-xs border border-gray-300 rounded dark:border-gray-600">+</button>
                                        </form>
                                        <form action="{{ route('admin.order-items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remove this item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 text-xs text-red-600 border border-red-300 rounded dark:border-red-700">Remove</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="text-right font-bold text-emerald-600 dark:text-emerald-400">
                                    ${{ number_format($item->subtotal ?? $item->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Totals & Actions -->
        <div class="space-y-6">

            <!-- Totals Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    Payment Summary
                </h3>

                <dl class="space-y-4">
                    <div class="flex justify-between text-lg">
                        <dt class="text-gray-600 dark:text-gray-400">Payment Method</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">
                            <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-xs font-medium uppercase">
                                {{ $orders->payment_method }}
                            </span>
                        </dd>
                    </div>

                    <div class="flex justify-between text-lg">
                        <dt class="text-gray-600 dark:text-gray-400">Subtotal</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">
                            ${{ number_format($orders->subtotal ?? 0, 2) }}
                        </dd>
                    </div>

                    <div class="flex justify-between text-lg">
                        <dt class="text-gray-600 dark:text-gray-400">Tax (10%)</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">
                            ${{ number_format($orders->tax ?? 0, 2) }}
                        </dd>
                    </div>

                    <div class="flex justify-between text-2xl font-bold pt-4 border-t border-gray-200 dark:border-gray-700">
                        <dt class="text-gray-900 dark:text-white">Grand Total</dt>
                        <dd class="text-emerald-600 dark:text-emerald-400">
                            ${{ number_format($orders->total_amount ?? 0, 2) }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                    Actions
                </h3>

                <div class="grid grid-cols-1 gap-4">
                    <form action="{{ route('admin.orders.updateStatus', $orders->id) }}" method="POST" class="flex items-center gap-4">
                        @csrf
                        @method('PATCH')

                        <select name="status" id="status" class="flex-grow block w-full px-4 py-3 text-base text-gray-900 border border-gray-300 rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="pending" {{ $orders->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="preparing" {{ $orders->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="ready" {{ $orders->status === 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ $orders->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $orders->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>

                        <button type="submit" class="py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition">
                            Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
