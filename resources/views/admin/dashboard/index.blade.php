@extends('admin.layouts.app')

@section('content')
<div class="mx-auto">

    <!-- Page Header -->
    <div class="mb-8 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                    {{ __('app.dashboard') }}
                </h1>
                <p class="mt-2 text-gray-600">
                    {{ now()->format('l, d F Y') }} • Quick overview of your restaurant's performance
                </p>    
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-plus-circle mr-2"></i>
                    New Order
                </a>
                <a href="{{ route('admin.reports.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-chart-line mr-2"></i>
                    View Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Orders Today -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 ease-out hover:shadow-xl hover:scale-[1.02] hover:-translate-y-1 group animate-bounce-in" style="animation-delay: 0.1s;">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                            Orders Today
                        </p>
                        <p class="mt-2 text-4xl font-bold text-indigo-600">
                            {{ $ordersToday ?? 0 }}
                        </p>
                        <p class="mt-2 text-xs text-gray-500">
                            Total Revenue: <span class="font-semibold text-emerald-600">${{ number_format($incomeToday ?? 0, 2) }}</span>
                        </p>
                    </div>
                    <div class="p-4 bg-indigo-100 rounded-xl group-hover:scale-125 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-shopping-bag text-4xl text-indigo-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 ease-out hover:shadow-xl hover:scale-[1.02] hover:-translate-y-1 group animate-bounce-in" style="animation-delay: 0.2s;">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                            Total Orders
                        </p>
                        <p class="mt-2 text-4xl font-bold text-indigo-600">
                            {{ $totalOrders ?? 0 }}
                        </p>
                        <p class="mt-2 text-xs text-gray-500">
                            Revenue: <span class="font-semibold text-emerald-600">${{ number_format($totalRevenue ?? 0, 2) }}</span>
                        </p>
                    </div>
                    <div class="p-4 bg-indigo-100 rounded-xl group-hover:scale-125 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-receipt text-4xl text-indigo-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 ease-out hover:shadow-xl hover:scale-[1.02] hover:-translate-y-1 group animate-bounce-in" style="animation-delay: 0.3s;">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                            Total Customers
                        </p>
                        <p class="mt-2 text-4xl font-bold text-emerald-600">
                            {{ $totalCustomers ?? 0 }}
                        </p>
                        <p class="mt-2 text-xs text-gray-500">
                            Menu Items: <span class="font-semibold text-indigo-600">{{ $totalMenuItems ?? 0 }}</span>
                        </p>
                    </div>
                    <div class="p-4 bg-emerald-100 rounded-xl group-hover:scale-125 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-users text-4xl text-emerald-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 ease-out hover:shadow-xl hover:scale-[1.02] hover:-translate-y-1 group animate-bounce-in" style="animation-delay: 0.4s;">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                            Tables Available
                        </p>
                        <p class="mt-2 text-4xl font-bold text-amber-600">
                            {{ $totalAvailableTable ?? 0 }}/{{ $totalTables ?? 0 }}
                        </p>
                        <p class="mt-2 text-xs text-gray-500">
                            Occupied: <span class="font-semibold text-rose-600">{{ $occupiedTables ?? 0 }}</span>
                        </p>
                    </div>
                    <div class="p-4 bg-amber-100 rounded-xl group-hover:scale-125 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-chair text-4xl text-amber-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 ease-out hover:shadow-xl hover:scale-[1.02] hover:-translate-y-1 group animate-bounce-in" style="animation-delay: 0.5s;">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                            Pending Orders
                        </p>
                        <p class="mt-2 text-4xl font-bold text-rose-600">
                            {{ $pendingOrders ?? 0 }}
                        </p>
                        <p class="mt-2 text-xs text-gray-500">
                            Needs attention
                        </p>
                    </div>
                    <div class="p-4 bg-rose-100 rounded-xl group-hover:scale-125 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-clock text-4xl text-rose-600"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-3">
                    <i class="fas fa-receipt text-indigo-600"></i>
                    Recent Orders
                </h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                    View All →
                </a>
            </div>
            
            @if($recentOrders && $recentOrders->count() > 0)
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                           class="block p-4 rounded-xl border border-gray-100 hover:border-amber-300 hover:bg-amber-50 transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Order #{{ $order->order_no }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->customer?->name ?? 'Guest Customer' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $order->status === 'preparing' ? 'bg-purple-100 text-purple-700' : '' }}
                                        {{ $order->status === 'delivered' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-10">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                    <br>No recent orders
                </p>
            @endif
        </div>

        <!-- Top Selling Items -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 transition-all duration-300 hover:shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center gap-3">
                    <i class="fas fa-fire text-amber-600"></i>
                    Top Selling Items (This Week)
                </h2>
                <a href="{{ route('admin.menu_items.index') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                    Manage Menu →
                </a>
            </div>
            
            @if($topSellingItems && $topSellingItems->count() > 0)
                <div class="space-y-4">
                    @foreach($topSellingItems as $index => $item)
                        <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex-shrink-0">
                                <span class="flex items-center justify-center h-8 w-8 rounded-full 
                                    {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $index === 1 ? 'bg-gray-100 text-gray-700' : '' }}
                                    {{ $index === 2 ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $index > 2 ? 'bg-gray-50 text-gray-500' : '' }}
                                    font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $item->name }}</p>
                                <p class="text-sm text-gray-500">{{ $item->total_sold }} sold this week</p>
                            </div>
                            <i class="fas fa-chart-line text-emerald-500"></i>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-10">
                    <i class="fas fa-chart-bar text-4xl text-gray-300 mb-3"></i>
                    <br>No sales data this week
                </p>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-gradient-to-r from-amber-50 to-indigo-50 rounded-2xl shadow-sm border border-amber-100 p-6 transition-all duration-300">
        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
            <i class="fas fa-bolt text-amber-600"></i>
            Quick Actions
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('admin.orders.create') }}"
               class="flex flex-col items-center justify-center p-4 bg-white rounded-xl hover:bg-indigo-50 transition group hover:shadow-lg hover:-translate-y-1">
                <i class="fas fa-plus-circle text-3xl text-indigo-600 mb-2 group-hover:scale-110 transition-all"></i>
                <span class="text-sm font-medium text-gray-700">New Order</span>
            </a>
            <a href="{{ route('admin.menu_items.index') }}"
               class="flex flex-col items-center justify-center p-4 bg-white rounded-xl hover:bg-emerald-50 transition group hover:shadow-lg hover:-translate-y-1">
                <i class="fas fa-utensils text-3xl text-emerald-600 mb-2 group-hover:scale-110 transition-all"></i>
                <span class="text-sm font-medium text-gray-700">Menu Items</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="flex flex-col items-center justify-center p-4 bg-white rounded-xl hover:bg-blue-50 transition group hover:shadow-lg hover:-translate-y-1">
                <i class="fas fa-tags text-3xl text-blue-600 mb-2 group-hover:scale-110 transition-all"></i>
                <span class="text-sm font-medium text-gray-700">Categories</span>
            </a>
            <a href="{{ route('admin.tables.index') }}"
               class="flex flex-col items-center justify-center p-4 bg-white rounded-xl hover:bg-amber-50 transition group hover:shadow-lg hover:-translate-y-1">
                <i class="fas fa-chair text-3xl text-amber-600 mb-2 group-hover:scale-110 transition-all"></i>
                <span class="text-sm font-medium text-gray-700">Tables</span>
            </a>
            <a href="{{ route('admin.customers.index') }}"
               class="flex flex-col items-center justify-center p-4 bg-white rounded-xl hover:bg-pink-50 transition group hover:shadow-lg hover:-translate-y-1">
                <i class="fas fa-users text-3xl text-pink-600 mb-2 group-hover:scale-110 transition-all"></i>
                <span class="text-sm font-medium text-gray-700">Customers</span>
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="flex flex-col items-center justify-center p-4 bg-white rounded-xl hover:bg-purple-50 transition group hover:shadow-lg hover:-translate-y-1">
                <i class="fas fa-chart-line text-3xl text-purple-600 mb-2 group-hover:scale-110 transition-all"></i>
                <span class="text-sm font-medium text-gray-700">Reports</span>
            </a>
        </div>
    </div>

</div>

<!-- Animation Keyframes -->
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
        }
        60% {
            opacity: 1;
            transform: translateY(-10px) scale(1.05);
        }
        80% {
            transform: translateY(5px) scale(0.98);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 1s ease-out forwards;
    }

    .animate-bounce-in {
        animation: bounceIn 0.9s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
</style>
@endsection