@extends('admin.layouts.app')
@section('title', 'Payments Overview')

@section('content')
<div class="mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Payments</h3>
            <p class="text-sm mt-1">Monitor and manage all customer transactions</p>
        </div>
       
    </div>

    {{-- Status Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <a href="{{ route('admin.payment.index') }}" 
           class="bg-white rounded-lg shadow border {{ request()->get('search') ? 'border-gray-200' : 'border-amber-300 bg-amber-50' }} p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider">All</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $statusCounts['all'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <svg class="w-5 h-5 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.payment.index', ['search' => 'pending']) }}" 
           class="bg-white rounded-lg shadow border {{ request()->get('search') == 'pending' ? 'border-yellow-300 bg-yellow-50' : 'border-gray-200' }} p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider">Waiting</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $statusCounts['pending'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.payment.index', ['search' => 'paid']) }}" 
           class="bg-white rounded-lg shadow border {{ request()->get('search') == 'paid' ? 'border-green-300 bg-green-50' : 'border-gray-200' }} p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider">Success</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $statusCounts['paid'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.payment.index', ['search' => 'failed']) }}" 
           class="bg-white rounded-lg shadow border {{ request()->get('search') == 'failed' ? 'border-red-300 bg-red-50' : 'border-gray-200' }} p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider">Failed</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $statusCounts['failed'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.payment.index', ['search' => 'refunded']) }}" 
           class="bg-white rounded-lg shadow border {{ request()->get('search') == 'refunded' ? 'border-purple-300 bg-purple-50' : 'border-gray-200' }} p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider">Refunded</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $statusCounts['refunded'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                </div>
            </div>
        </a>
    </div>

    {{-- Filters --}}
    {{-- Search Form --}}
    <!-- Table Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">

    

        {{-- Search and Filter --}}
        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('admin.payment.index') }}" class="flex items-center gap-2">
                <div class="relative flex-1 sm:min-w-[300px]">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by transaction ID or order no..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors">
                </div>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.payment.index') }}"
                        class="px-3 py-2 text-sm font-medium bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 rounded-lg transition-colors" title="Clear Search">
                        <i class="fa-solid fa-times"></i>
                    </a>
                @endif
            </form>
            <div class="flex flex-wrap items-center justify-end gap-3 sm:ml-auto w-full sm:w-auto">
                <span class="text-sm font-medium dark:text-gray-400">Total Revenue:</span>
                <span class="text-2xl font-bold text-amber-600">${{ number_format($totalRevenue ?? 0, 2) }}</span>
            </div>
    
        </div>
      
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest">
                        Transaction ID
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest">Customer Name </th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest">
                        Order No
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest">
                        Amount
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest">
                        Method
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest">
                        Status
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-widest">
                        Date
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $payment->transaction_id ?? $payment->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($payment->order)
                                {{ $payment->order->customer?->name
                                    ?? $payment->order->user?->name
                                    ?? $payment->order->customer?->email
                                    ?? $payment->order->user?->email
                                    ?? 'Walk-in' }}
                            @else
                                <span class="text-gray-400">&mdash;</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($payment->order)
                                <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-amber-600 hover:text-amber-700 font-medium">
                                    {{ $payment->order->order_no ?? '#' . $payment->order_id }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-amber-600">
                            ${{ number_format($payment->paid_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm ">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 ">
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConfig = [
                                    'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-700'],
                                    'paid' => ['label' => 'Success', 'class' => 'bg-green-100 text-green-700'],
                                    'failed' => ['label' => 'Failed', 'class' => 'bg-red-100 text-red-700'],
                                    'refunded' => ['label' => 'Refunded', 'class' => 'bg-purple-100 text-purple-700'],
                                ];
                                $config = $statusConfig[$payment->status] ?? ['label' => ucfirst($payment->status), 'class' => 'bg-gray-100 '];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['class'] }}">
                                {{ $config['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm ">
                            {{ $payment->created_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center ">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <p class="mt-2 text-sm">No payments found.</p>
                            <p class="mt-1 text-xs text-gray-400">New payments will appear here automatically.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($payments->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
