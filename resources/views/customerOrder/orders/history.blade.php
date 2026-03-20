@extends('customerOrder.layouts.app')

@section('title', 'Order History — Saveur')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Order <em class="text-amber-600 not-italic">History</em></h1>
            <p class="text-gray-500 text-sm mt-1">All your past orders in one place.</p>
        </div>
        <a href="{{ route('customerOrder.menu.index') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium text-sm bg-amber-600 text-white hover:bg-amber-700 transition">
            <i class="fa-solid fa-plus"></i> New Order
        </a>
    </div>

    @if(empty($orders))
    <div class="text-center py-24 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-receipt text-3xl text-gray-300"></i>
        </div>
        <h2 class="text-2xl text-gray-500 mb-2">No orders yet</h2>
        <p class="text-gray-400 text-sm mb-6">Your order history will appear here.</p>
        <a href="{{ route('customerOrder.menu.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-medium text-sm bg-amber-600 text-white hover:bg-amber-700 transition">
            <i class="fa-solid fa-utensils"></i> Start Ordering
        </a>
    </div>

    @else
    <div class="space-y-5">
        @foreach($orders as $order)
        @php
            $statusConfig = [
                'pending'    => ['bg-yellow-100 text-yellow-700',  'fa-clock',            'Pending'],
                'confirmed'  => ['bg-blue-100 text-blue-700',      'fa-check-circle',     'Confirmed'],
                'delivered'  => ['bg-purple-100 text-purple-700',  'fa-truck',            'Delivering'],
                'completed'  => ['bg-emerald-100 text-emerald-700','fa-circle-check',     'Completed'],
                'refunded'   => ['bg-orange-100 text-orange-700',  'fa-rotate-left',      'Refunded'],
                'cancelled'  => ['bg-red-100 text-red-700',        'fa-circle-xmark',     'Cancelled'],
            ];
            [$statusClass, $statusIcon, $statusLabel] = $statusConfig[$order['status']] ?? $statusConfig['pending'];
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Order Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-4">
                    <div>
                        <span class="font-bold text-gray-900 text-lg">#{{ $order['id'] }}</span>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y · g:i A') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                        <i class="fa-solid {{ $statusIcon }}"></i> {{ $statusLabel }}
                    </span>
                    <span class="font-bold text-amber-600 text-lg">${{ number_format($order['total'], 2) }}</span>
                </div>
            </div>

            {{-- Order Items Preview --}}
            <div class="px-6 py-4">
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach(array_slice($order['items'], 0, 4) as $item)
                    <div class="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-1.5">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-6 h-6 rounded-full object-cover">
                        <span class="text-xs font-medium text-gray-900">{{ $item['name'] }}</span>
                        <span class="text-xs text-gray-500">×{{ $item['qty'] }}</span>
                    </div>
                    @endforeach
                    @if(count($order['items']) > 4)
                    <span class="text-xs text-gray-400">+{{ count($order['items']) - 4 }} more</span>
                    @endif
                </div>
            </div>

            {{-- Order Footer --}}
            <div class="px-6 py-3 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <i class="fa-solid fa-location-dot text-amber-500"></i>
                    {{ $order['address'] }}
                </div>
                <div class="flex gap-2">
                    <button onclick="toggleDetails('order-{{ $order['id']}}')"
                        class="text-xs text-amber-600 hover:text-amber-700 font-medium transition flex items-center gap-1">
                        Details <i class="fa-solid fa-chevron-down text-[10px]" id="chevron-{{ $order['id'] }}"></i>
                    </button>
                    @if($order['status'] == 'completed')
                    <form method="POST" action="{{ route('customerOrder.cart.reorder') }}">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order['id'] }}">
                        <button type="submit" class="text-xs text-amber-600 hover:text-amber-700 font-medium transition flex items-center gap-1">
                            <i class="fa-solid fa-rotate-left"></i> Reorder
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Expandable Details --}}
            <div id="order-{{ $order['id'] }}" class="hidden border-t border-gray-200 px-6 py-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <h4 class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-2">Items</h4>
                        <div class="space-y-2">
                            @foreach($order['items'] as $item)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $item['name'] }} ×{{ $item['qty'] }}</span>
                                <span class="font-medium text-gray-900">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-2">Summary</h4>
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-gray-600"><span>Subtotal</span><span>${{ number_format($order['subtotal'], 2) }}</span></div>
                            <div class="flex justify-between text-gray-600"><span>Tax (10%)</span><span>${{ number_format($order['subtotal'] * 0.10, 2) }}</span></div>
                            <div class="flex justify-between font-bold text-gray-900 border-t border-gray-200 pt-1.5 mt-1.5">
                                <span>Total</span><span class="text-amber-600">${{ number_format($order['total'], 2) }}</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="text-xs text-gray-400">Payment: <span class="text-gray-700 font-medium">{{ ucwords(str_replace('_', ' ', $order['payment'])) }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function toggleDetails(id) {
    const el = document.getElementById(id);
    const orderId = id.replace('order-', '');
    const chevron = document.getElementById('chevron-' + orderId);
    el.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}
</script>
@endpush
