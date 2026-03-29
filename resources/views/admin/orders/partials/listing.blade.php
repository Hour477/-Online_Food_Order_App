<div class="mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pageTitle }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Track and manage customer orders and deliveries</p>
            </div>

            <a href="{{ route('admin.orders.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                <i class="fa fa-plus mr-2"></i>
                New Order
            </a>
        </div>

        {{-- Search and Filter --}}
        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:min-w-[300px] w-full">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order no..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors">
                </div>
                
                

                <button type="submit"
                    class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    Search
                </button>
                
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.orders.index') }}"
                        class="w-full sm:w-auto px-3 py-2 text-sm font-medium text-center text-gray-500 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 rounded-lg transition-colors" title="Clear Filters">
                        <i class="fa-solid fa-times sm:mr-0 mr-2"></i>
                        <span class="sm:hidden">Clear Filters</span>
                    </a>
                @endif
            </form>
        </div>

        <x-table.base-table>
            <x-slot name="head">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order Info</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payment</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grand Total</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </x-slot>

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
                        <td class="px-6 py-4 whitespace-nowrap text-sm  dark:text-gray-300">
                            {{ $order->customer?->email ?? 'N/A' }}
                            <br>
                            {{ $order->customer?->name ?? 'Walk-in' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-emerald-600 dark:text-emerald-400">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                       <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    'refunded' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                ];
                                
                                $statusClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400';
                            @endphp
                            
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="{{ action_btn_class('view') }}"
                               title="View Details">
                                <i class="{{ action_btn_icon('view') }}"></i>
                            </a>

                            <button type="button"
                                onclick="showDeleteModal('{{ route('admin.orders.destroy', $order->id) }}', 'Are you sure you want to delete order #{{ $order->order_no }}?')"
                                class="{{ action_btn_class('delete') }}"
                                title="Delete">
                                <i class="{{ action_btn_icon('delete') }}"></i>
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

        @if($orders->hasPages())
            <div class="pt-4 pb-2 border-t border-gray-200 dark:border-gray-700 text-center sm:text-right">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
