@extends('admin.layouts.app')

@section('content')
<div class="mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Payments</h3>
            <p class="text-sm text-gray-500 mt-1">Monitor and manage all customer transactions</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-gray-500">Total Revenue:</span>
            <span class="text-2xl font-bold text-amber-600">${{ number_format($totalRevenue ?? 0, 2) }}</span>
        </div>
    </div>

    {{-- Status Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <a href="{{ route('admin.payment.index') }}" 
           class="bg-white rounded-xl border {{ request()->get('status') ? 'border-gray-200' : 'border-amber-300 bg-amber-50' }} p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">All</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $statusCounts['all'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.payment.index', ['status' => 'pending']) }}" 
           class="bg-white rounded-xl border {{ request()->get('status') == 'pending' ? 'border-yellow-300 bg-yellow-50' : 'border-gray-200' }} p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Waiting</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $statusCounts['pending'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.payment.index', ['status' => 'paid']) }}" 
           class="bg-white rounded-xl border {{ request()->get('status') == 'paid' ? 'border-green-300 bg-green-50' : 'border-gray-200' }} p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Success</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $statusCounts['paid'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.payment.index', ['status' => 'failed']) }}" 
           class="bg-white rounded-xl border {{ request()->get('status') == 'failed' ? 'border-red-300 bg-red-50' : 'border-gray-200' }} p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Error</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $statusCounts['failed'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.payment.index', ['status' => 'refunded']) }}" 
           class="bg-white rounded-xl border {{ request()->get('status') == 'refunded' ? 'border-purple-300 bg-purple-50' : 'border-gray-200' }} p-4 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Refunded</p>
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
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-5 mb-6">
        <form action="{{ route('admin.payment.index') }}" method="GET">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Payment Method</label>
                    <select name="payment_method" 
                            class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900
                                   px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                        <option value="aba" {{ request('payment_method') == 'aba' ? 'selected' : '' }}>ABA</option>
                        <option value="wing" {{ request('payment_method') == 'wing' ? 'selected' : '' }}>Wing</option>
                        <option value="bakong" {{ request('payment_method') == 'bakong' ? 'selected' : '' }}>Bakong</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Status</label>
                    <select name="status" 
                            class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900
                                   px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Waiting Payment</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Error</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900
                                  px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" 
                            class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-medium py-2.5 rounded-lg transition shadow-sm">
                        Filter
                    </button>
                    <a href="{{ route('admin.payment.index') }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-center font-medium py-2.5 rounded-lg transition border border-gray-200 text-gray-700">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                        Transaction ID
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                        Order No
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                        Amount
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                        Method
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
                        Status
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-widest">
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConfig = [
                                    'pending' => ['label' => 'Waiting Payment', 'class' => 'bg-yellow-100 text-yellow-700'],
                                    'paid' => ['label' => 'Success', 'class' => 'bg-green-100 text-green-700'],
                                    'failed' => ['label' => 'Error', 'class' => 'bg-red-100 text-red-700'],
                                    'refunded' => ['label' => 'Refunded', 'class' => 'bg-purple-100 text-purple-700'],
                                ];
                                $config = $statusConfig[$payment->status] ?? ['label' => ucfirst($payment->status), 'class' => 'bg-gray-100 text-gray-700'];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['class'] }}">
                                {{ $config['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payment->created_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
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
