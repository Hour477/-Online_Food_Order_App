@extends('admin.layouts.app')
@section('title', 'Sales Reports')

@section('content')
<div class="mx-auto">
    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Sales Analysis</h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Detailed performance of your menu items and categories</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.export.pdf', ['type' => 'sales', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('admin.reports.export.csv', ['type' => 'sales', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium flex items-center gap-2">
                <i class="fas fa-file-excel"></i> CSV
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Top Selling Items --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Top 10 Selling Items</h3>
                <span class="text-xs font-medium text-gray-400 uppercase">By Quantity</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50">
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Item Name</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-center">Qty Sold</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-right">Total Sales</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($topItems as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $item->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">{{ number_format($item->total_qty) }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-emerald-600 text-right">${{ number_format($item->total_sales, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-400">No data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Category Breakdown --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Sales by Category</h3>
                <span class="text-xs font-medium text-gray-400 uppercase">Revenue Share</span>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    @php $totalCatSales = $categorySales->sum('total_sales') ?: 1; @endphp
                    @forelse($categorySales as $cat)
                        @php $percent = ($cat->total_sales / $totalCatSales) * 100; @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $cat->name }}</span>
                                <span class="font-bold text-gray-900 dark:text-white">${{ number_format($cat->total_sales, 2) }} ({{ number_format($percent, 1) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5">
                                <div class="bg-amber-500 h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-gray-400">No data found</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
