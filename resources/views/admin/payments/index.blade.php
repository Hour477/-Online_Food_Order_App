
@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                Payments
            </h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">
                Monitor and manage all customer transactions
            </p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue:</span>
            <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                {{-- ${{ number_format($payments->sum('amount'), 2) }} --}}
            </span>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
        <form action="{{ route('admin.payment.index') }}" method="GET">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Payment Method</label>
                    <select name="payment_method" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                    </select>
                    
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Date Range</label>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-xl transition shadow-sm">
                        Filter
                    </button>
                    <a href="{{ route('admin.payment.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 text-center font-medium py-2.5 rounded-xl transition border border-gray-200 dark:border-gray-600">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">

                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Transaction ID
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Order No
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Amount
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Method
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Date
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            #{{ $payment->transaction_id ?? $payment->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 dark:text-indigo-400 font-medium">
                            <a href="{{ route('admin.orders.show', $payment->order_id) }}">
                                
                            </a>
                        </td>
                        <td class="px-6 py-6 py-4 whitespace-nowrap text-sm font-bold text-emerald-600 dark:text-emerald-400">
                            ${{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                <i class="fas fa-{{ $payment->payment_method === 'card' ? 'credit-card' : 'money-bill-wave' }} mr-1.5"></i>
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : '' }}
                                {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300' : '' }}
                                {{ $payment->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' : '' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $payment->created_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <p class="mt-2 text-sm">No payments found yet.</p>
                            <p class="mt-1 text-xs">New payments will appear here automatically.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($payments->hasPages())
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
            {{ $payments->links() }}
        </div>
        @endif
        </table>
    </div>

</div>

@endsection
                    
    