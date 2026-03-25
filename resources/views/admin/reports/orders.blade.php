@extends('admin.layouts.app')

@section('content')
<div class="mx-auto">
    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Order Reports</h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Analysis of order types, statuses, and transaction logs</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.export.pdf', ['type' => 'orders', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('admin.reports.export.csv', ['type' => 'orders', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium flex items-center gap-2">
                <i class="fas fa-file-excel"></i> CSV
            </a>
        </div>
    </div>

    {{-- Order Type Breakdown --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @foreach($ordersByType as $type)
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ str_replace('_', ' ', $type->order_type) }}</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $type->count }} <span class="text-sm font-normal text-gray-400 ml-1">Orders</span></h3>
                    <p class="text-sm font-semibold text-emerald-600">${{ number_format($type->revenue, 2) }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Transaction Logs --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Transactions</h3>
            <span class="text-xs font-medium text-gray-400 uppercase">Page {{ $recentOrders->currentPage() }} of {{ $recentOrders->lastPage() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900/50">
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-right">Amount</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-amber-600">#{{ $order->order_no }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $order->customer->name ?? 'Walk-in' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $order->order_type) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium 
                                    @if($order->status === 'completed' || $order->status === 'delivered') bg-emerald-100 text-emerald-700
                                    @elseif($order->status === 'pending' || $order->status === 'confirmed') bg-blue-100 text-blue-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white text-right">${{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-right">{{ $order->created_at->format('d M, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">No transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-100 dark:border-gray-700">
            {{ $recentOrders->links() }}
        </div>
    </div>
</div>
@endsection
