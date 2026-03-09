@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950 px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Orders</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Welcome, {{ $customer->name }}</p>
            </div>
            <form action="{{ route('frontend.logout') }}" method="POST">
                @csrf
                <button class="rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm">Logout</button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-700 px-3 py-2 text-sm">{{ session('success') }}</div>
        @endif

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr>
                            <th class="text-left px-4 py-3">Order No</th>
                            <th class="text-left px-4 py-3">Type</th>
                            <th class="text-left px-4 py-3">Status</th>
                            <th class="text-left px-4 py-3">Amount</th>
                            <th class="text-right px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr class="border-t border-gray-200 dark:border-gray-800">
                            <td class="px-4 py-3">{{ $order->order_no }}</td>
                            <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</td>
                            <td class="px-4 py-3">{{ ucfirst($order->status) }}</td>
                            <td class="px-4 py-3">${{ number_format((float) $order->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                @if($order->order_type === 'delivery')
                                    <a href="{{ route('frontend.orders.track', $order->id) }}" class="text-indigo-600 hover:text-indigo-700">Track on map</a>
                                @else
                                    <span class="text-gray-400">No tracking</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

