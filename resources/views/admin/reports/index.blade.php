@extends('admin.layouts.app')
@section('title', 'Reports Overview')

@section('content')
<div class="mx-auto">
    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Reports Overview</h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Comprehensive view of your restaurant's performance</p>
        </div>
        
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">From</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                       class="rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm focus:ring-amber-500 focus:border-amber-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">To</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                       class="rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm focus:ring-amber-500 focus:border-amber-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-medium">
                Filter
            </button>
        </form>
    </div>

    {{-- Quick Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['total_revenue'], 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Orders</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_orders'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Avg. Order Value</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['avg_order_value'], 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Cancelled</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['cancelled_orders'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Hub Links --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('admin.reports.sales', request()->all()) }}"
           class="group p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm hover:border-amber-300 transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 rounded-xl bg-purple-50 dark:bg-purple-900/20 text-purple-600 group-hover:bg-purple-100 transition-colors">
                    <i class="fas fa-chart-pie text-xl"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Analysis</h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Detailed breakdown of item performance and category popularity.</p>
        </a>

        <a href="{{ route('admin.reports.orders', request()->all()) }}"
           class="group p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm hover:border-amber-300 transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 group-hover:bg-blue-100 transition-colors">
                    <i class="fas fa-list-alt text-xl"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Order Reports</h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Track order statuses, types, and recent transaction history.</p>
        </a>

        <a href="{{ route('admin.reports.income', request()->all()) }}"
           class="group p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm hover:border-amber-300 transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Income Reports</h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Daily financial breakdown and payment method distribution.</p>
        </a>
    </div>

    {{-- Trends & Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Daily Revenue Chart --}}
        <div class="bg-white dark:bg-gray-800 p-8 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Daily Revenue Trend</h3>
            <div class="h-80 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Monthly Sales Comparison (Multiple Lines) --}}
        <div class="bg-white dark:bg-gray-800 p-8 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Sales Comparison</h3>
                <div class="flex items-center gap-4 text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <span class="text-gray-500 dark:text-gray-400">{{ now()->year }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                        <span class="text-gray-500 dark:text-gray-400">{{ now()->year - 1 }}</span>
                    </div>
                </div>
            </div>
            <div class="h-80 w-full">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(75, 85, 99, 0.2)' : 'rgba(229, 231, 235, 0.5)';
        const tickColor = isDark ? '#9ca3af' : '#6b7280';
        const tooltipBg = isDark ? '#1f2937' : '#111827';

        // Daily Revenue Chart (Bar)
        const dailyCtx = document.getElementById('revenueChart').getContext('2d');
        const dailyLabels = {!! json_encode($revenueTrend->pluck('date')->map(fn($d) => date('d M', strtotime($d)))) !!};
        const dailyData = {!! json_encode($revenueTrend->pluck('revenue')) !!};

        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Revenue',
                    data: dailyData,
                    backgroundColor: 'rgba(245, 158, 11, 0.8)',
                    borderColor: 'rgb(245, 158, 11)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor: tooltipBg } },
                scales: {
                    y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor, callback: v => '$' + v } },
                    x: { grid: { display: false }, ticks: { color: tickColor } }
                }
            }
        });

        // Monthly Comparison Chart (Line)
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyTrend = {!! json_encode($monthlyTrend) !!};

        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyTrend.labels,
                datasets: [
                    {
                        label: '{{ now()->year }}',
                        data: monthlyTrend.current,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: '{{ now()->year - 1 }}',
                        data: monthlyTrend.previous,
                        borderColor: isDark ? '#4b5563' : '#d1d5db',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.4,
                        fill: false,
                        pointBackgroundColor: isDark ? '#4b5563' : '#d1d5db',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false }, 
                    tooltip: { 
                        backgroundColor: tooltipBg,
                        callbacks: { label: ctx => ` ${ctx.dataset.label}: $${ctx.parsed.y.toLocaleString()}` }
                    } 
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor, callback: v => '$' + v } },
                    x: { grid: { display: false }, ticks: { color: tickColor } }
                }
            }
        });
    });
</script>
@endpush
