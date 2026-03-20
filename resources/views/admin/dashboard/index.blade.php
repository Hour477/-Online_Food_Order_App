@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    <!-- Page Header -->
    <div class="mb-10 animate-fade-in-up" style="animation-delay: 0.1s;">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
            Dashboard
        </h1>
        <p class="mt-2 text-gray-600">
            Quick overview of today's performance • {{ now()->format('l, d F Y') }}
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Orders Today -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 ease-out hover:shadow-2xl hover:scale-[1.08] hover:-translate-y-2 hover:ring-2 hover:ring-indigo-500/30 group animate-bounce-in" style="animation-delay: 0.2s;">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                            Orders Today
                        </p>
                        <p class="mt-2 text-4xl font-bold text-indigo-600">
                            {{ $ordersToday ?? 0 }}
                        </p>
                    </div>
                    <div class="p-4 bg-indigo-100 rounded-xl group-hover:scale-125 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-shopping-bag text-4xl text-indigo-600"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-500">
                    <span class="text-green-600 font-medium">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 ease-out hover:shadow-2xl hover:scale-[1.08] hover:-translate-y-2 hover:ring-2 hover:ring-indigo-500/30 group animate-bounce-in" style="animation-delay: 0.2s;">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                            Orders Total
                        </p>
                        <p class="mt-2 text-4xl font-bold text-indigo-600">
                            {{ $totalOrders ?? 0}}
                            
                        </p>
                    </div>
                    <div class="p-4 bg-indigo-100 rounded-xl group-hover:scale-125 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-shopping-bag text-4xl text-indigo-600"></i>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Revenue Today -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 ease-out hover:shadow-2xl hover:scale-[1.08] hover:-translate-y-2 hover:ring-2 hover:ring-emerald-500/30 group animate-bounce-in" style="animation-delay: 0.35s;">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                            Revenue Today
                        </p>
                        <p class="mt-2 text-4xl font-bold text-emerald-600">
                            ${{ number_format($incomeToday ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="p-4 bg-emerald-100 rounded-xl group-hover:scale-125 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-dollar-sign text-4xl text-emerald-600"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-500">
                    <span class="text-green-600 font-medium">↑ 8%</span> vs yesterday
                </div>
            </div>
        </div>

        <!-- Active Tables -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 ease-out hover:shadow-2xl hover:scale-[1.08] hover:-translate-y-2 hover:ring-2 hover:ring-amber-500/30 group animate-bounce-in" style="animation-delay: 0.5s;">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                            Available Tables
                        </p>
                        <p class="mt-2 text-4xl font-bold text-amber-600">
                            {{ $totalAvailableTable ?? 0}}
                        </p>
                    </div>
                    <div class="p-4 bg-amber-100 rounded-xl group-hover:scale-125 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-chair text-4xl text-amber-600"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-500">
                    {{-- <span class="text-amber-600 font-medium">35%</span> occupancy --}}
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 ease-out hover:shadow-2xl hover:scale-[1.08] hover:-translate-y-2 hover:ring-2 hover:ring-rose-500/30 group animate-bounce-in" style="animation-delay: 0.65s;">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">
                            Pending Orders
                        </p>
                        <p class="mt-2 text-4xl font-bold text-rose-600">
                            3
                        </p>
                    </div>
                    <div class="p-4 bg-rose-100 rounded-xl group-hover:scale-125 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-clock text-4xl text-rose-600"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-500">
                    Avg. wait time: <span class="font-medium">12 min</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Additional Sections -->
    <div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-8 animate-fade-in-up" style="animation-delay: 0.8s;">
        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 transition-all duration-300 hover:shadow-2xl hover:scale-[1.02] hover:-translate-y-1">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
                <i class="fas fa-receipt text-indigo-600"></i>
                Recent Orders
            </h2>
            <p class="text-gray-500 text-center py-10">
                Coming soon – list of latest orders
            </p>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 transition-all duration-300 hover:shadow-2xl hover:scale-[1.02] hover:-translate-y-1">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
                <i class="fas fa-bolt text-amber-600"></i>
                Quick Actions
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.orders.create') }}"
                   class="flex flex-col items-center justify-center p-6 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition group hover:shadow-xl hover:-translate-y-2 hover:ring-2 hover:ring-indigo-500/30">
                    <i class="fas fa-plus-circle text-4xl text-indigo-600 mb-3 group-hover:scale-125 group-hover:rotate-6 transition-all duration-300"></i>
                    <span class="font-medium text-indigo-700">New Order</span>
                </a>
                <a href="{{ route('admin.tables.index') }}"
                   class="flex flex-col items-center justify-center p-6 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition group hover:shadow-xl hover:-translate-y-2 hover:ring-2 hover:ring-emerald-500/30">
                    <i class="fas fa-chair text-4xl text-emerald-600 mb-3 group-hover:scale-125 group-hover:rotate-6 transition-all duration-300"></i>
                    <span class="font-medium text-emerald-700">Manage Tables</span>
                </a>
            </div>
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