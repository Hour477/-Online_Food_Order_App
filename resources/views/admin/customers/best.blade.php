@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center p-6 sm:justify-between gap-4 ">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Top Customers
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Your most valuable customers based on order count and total spending
                </p>
            </div>

            <a href="{{ route('admin.customers.index') }}"
            class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                <i class="fas fa-users mr-2"></i>
                View All Customers
            </a>
        </div>
        @if($topCustomers->isEmpty())
            <!-- Empty State -->
            <div class="py-16 text-center">
                <div class="inline-block p-6 bg-gray-50 dark:bg-gray-700/30 rounded-full mb-4">
                    <i class="fas fa-crown text-5xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">
                    No top customers yet
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                    Customers with orders will appear here ranked by their order count and total spending.
                </p>
                <a href="{{ route('admin.customers.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition">
                    <i class="fas fa-users mr-2"></i>
                    View All Customers
                </a>
            </div>
        @else
            <!-- Responsive Table -->
            <div class="overflow-x-auto p-4">
                <x-table.base-table>
                <x-slot name="head">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Rank
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Phone
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Orders
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Spent
                            </th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($topCustomers as $index => $customer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($index < 3)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{
                                            $index === 0 ? 'bg-yellow-100 text-yellow-700' : 
                                            ($index === 1 ? 'bg-gray-200 text-gray-700' : 
                                            'bg-amber-100 text-amber-700')
                                        }}">
                                            @if($index === 0)
                                                <i class="fas fa-crown text-yellow-500"></i>
                                            @elseif($index === 1)
                                                <i class="fas fa-medal text-gray-400"></i>
                                            @else
                                                <i class="fas fa-award text-amber-500"></i>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-gray-500">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $customer->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $customer->email ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $customer->phone ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $customer->orders_count }} orders
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-green-600 dark:text-green-400">
                                    ${{ number_format($customer->orders_sum_total_amount ?? 0, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-table.base-table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 text-center sm:text-right">
                {{ $topCustomers->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
