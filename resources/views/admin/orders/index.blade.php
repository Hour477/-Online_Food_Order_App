@extends('admin.layouts.app')

@section('title', 'Orders Management')

@section('content')
<div class="mx-auto">
    <!-- Table Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Orders Management</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Track and manage customer orders and deliveries</p>
            </div>

            <a href="{{ route('admin.orders.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                <i class="fa fa-plus mr-2"></i>
                New Order
            </a>
        </div>

        {{-- Advanced Filter --}}
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-600">
            <form action="{{ route('admin.orders.index') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div>
                        <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order No</label>
                        <input type="text" name="order_no" value="{{ request('order_no') }}" placeholder="Search Order #"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all">
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</label>
                        <input type="text" name="customer_name" value="{{ request('customer_name') }}" placeholder="Search Name"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all">
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</label>
                        <input type="text" name="amount" value="{{ request('amount') }}" placeholder="Min Amount"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all">
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all">
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('admin.orders.index') }}"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <x-table.base-table>
            {{-- HEADER --}}
            <x-slot name="head">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order Info</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payment</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </x-slot>

            {{-- BODY --}}
            <x-slot name="body">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-sm font-bold text-gray-900 dark:text-white hover:text-amber-600 transition-colors">
                                    #{{ $order->order_no }}
                                    @if($order->created_at && $order->created_at->diffInHours(now()) < 12)
                                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-500 text-white animate-pulse">NEW</span>
                                    @endif
                                </a>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-0.5">
                                    {{ str_replace('_', ' ', $order->order_type ?? 'N/A') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-[11px] font-semibold text-gray-600 dark:text-gray-300 uppercase">
                                {{ $order->payment_method ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            {{ $order->customer?->name ?? 'Walk-in' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-emerald-600 dark:text-emerald-400">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-300',
                                    'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-300',
                                    'delivered' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800/30 dark:text-indigo-300',
                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-300',
                                    'refunded' => 'bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-300',
                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-300',
                                ];
                                $currentStatus = strtolower($order->status);
                                $class = $statusClasses[$currentStatus] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-300';
                            @endphp
                            <span class="inline-flex px-2.5 py-1 text-[11px] font-bold rounded-full {{ $class }} uppercase tracking-wide">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="{{ action_btn_class('view') }}"
                               title="View Details">
                                <i class="{{ action_btn_icon('view') }}"></i>
                            </a>
                            {{-- <a href="{{ route('admin.orders.status', $order->id) }}"
                               class="{{ action_btn_class('status') }}"
                               title="Update Status">
                                <i class="{{ action_btn_icon('status') }}"></i>
                            </a> --}}
                            <button type="button" 
                                onclick="showDeleteModal('{{ route('admin.orders.destroy', $order->id) }}', 'Are you sure you want to delete order #{{ $order->order_no }}?')"
                                class="{{ action_btn_class('delete') }}"
                                title="Delete">
                                <i class="fas text-lg fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fa-solid fa-receipt text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No orders found</p>
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table.base-table>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="pt-4 pb-2 border-t border-gray-200 dark:border-gray-700 text-center sm:text-right">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

