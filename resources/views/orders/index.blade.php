@extends('layouts.app')

@section('content')

<div class="mx-auto">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
            Orders
        </h3>

        <!-- Optional: Add New Order button (if your system allows manual creation) -->
         
        <a href="{{ route('orders.create') }}"
        
           class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Order
        </a>
        
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                <thead class="bg-gray-50 dark:bg-gray-900/70">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            Order ID
                        </th>
                        <td scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            Order Type
                        </td>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            Order No
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            Customer
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            Total Amount
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                          Created At</td>
                            <!-- Actions -->
                        <!-- Optional: Actions column -->
                         <th scope="col" class="px-6 py-4 text-right ...">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                #{{ $order->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->order_type ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $order->order_no }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $order->customer?->name ?? 'Walk-in' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch(strtolower($order->status))
                                    @case('pending')
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-300">
                                            Pending
                                        </span>
                                    @break

                                    @case('processing')
                                    @case('preparing')
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-300">
                                            Processing
                                        </span>
                                    @break

                                    @case('ready')
                                    @case('served')
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-300">
                                            Ready / Served
                                        </span>
                                    @break

                                    @case('completed')
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Completed
                                        </span>
                                    @break

                                    @case('cancelled')
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-300">
                                            Cancelled
                                        </span>
                                    @break

                                    @default
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                @endswitch
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">

                                {{ $order->created_at instanceof \Illuminate\Support\Carbon 
                                ? $order->created_at->format('M d, Y h:i A') 
                                : '---' }}
                            </td>

                            <!-- Optional: View / Edit / Print buttons -->

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                    View
                                </a>
                                
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <p class="mt-2 text-sm">No orders found yet.</p>
                                <p class="mt-1 text-xs">New orders will appear here automatically.</p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

    <!-- Pagination (if using paginate in controller) -->
    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif

</div>

@endsection