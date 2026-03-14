@extends('customerOrder.layouts.app')

@section('title', 'Order History — Saveur')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-display text-4xl font-bold text-ink">Order <em class="text-brand-400 not-italic">History</em></h1>
            <p class="text-ink/50 text-sm mt-1">All your past orders in one place.</p>
        </div>
        <a href="{{ route('customerOrder.menu.index') }}" class="btn-primary hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-full font-medium text-sm shadow">
            <i class="fa-solid fa-plus"></i> New Order
        </a>
    </div>

    @if(empty($orders))
    <div class="text-center py-24 bg-white rounded-3xl shadow-sm border border-brand-50">
        <div class="w-20 h-20 rounded-full bg-brand-50 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-receipt text-3xl text-brand-200"></i>
        </div>
        <h2 class="font-display text-2xl text-ink/40 mb-2">No orders yet</h2>
        <p class="text-ink/30 text-sm mb-6">Your order history will appear here.</p>
        <a href="{{ route('customerOrder.menu.index') }}" class="btn-primary inline-flex items-center gap-2 px-6 py-3 rounded-full font-medium text-sm">
            <i class="fa-solid fa-utensils"></i> Start Ordering
        </a>
    </div>

    @else
    <div class="space-y-5">
        @foreach($orders as $order)
        @php
            $statusConfig = [
                'pending'    => ['bg-yellow-100 text-yellow-700',  'fa-clock',            'Pending'],
                'preparing'  => ['bg-blue-100 text-blue-700',      'fa-fire-burner',      'Preparing'],
                'on_the_way' => ['bg-purple-100 text-purple-700',  'fa-motorcycle',       'On the Way'],
                'delivered'  => ['bg-emerald-100 text-emerald-700','fa-circle-check',     'Delivered'],
                'cancelled'  => ['bg-red-100 text-red-700',        'fa-circle-xmark',     'Cancelled'],
            ];
            [$statusClass, $statusIcon, $statusLabel] = $statusConfig[$order['status']] ?? $statusConfig['pending'];
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-brand-50 overflow-hidden slide-in">
            {{-- Order Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-brand-50">
                <div class="flex items-center gap-4">
                    <div>
                        <span class="font-bold text-ink font-display text-lg">#{{ $order['id'] }}</span>
                        <p class="text-xs text-ink/40">{{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y · g:i A') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                        <i class="fa-solid {{ $statusIcon }}"></i> {{ $statusLabel }}
                    </span>
                    <span class="font-bold text-brand-500 text-lg">${{ number_format($order['total'], 2) }}</span>
                </div>
            </div>

            {{-- Order Items Preview --}}
            <div class="px-6 py-4">
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach(array_slice($order['items'], 0, 4) as $item)
                    <div class="flex items-center gap-2 bg-brand-50 rounded-full px-3 py-1.5">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-6 h-6 rounded-full object-cover">
                        <span class="text-xs font-medium text-ink">{{ $item['name'] }}</span>
                        <span class="text-xs text-ink/50">×{{ $item['qty'] }}</span>
                    </div>
                    @endforeach
                    @if(count($order['items']) > 4)
                    <span class="text-xs text-ink/40">+{{ count($order['items']) - 4 }} more</span>
                    @endif
                </div>
            </div>

            {{-- Order Footer --}}
            <div class="px-6 py-3 bg-brand-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-ink/50">
                    <i class="fa-solid fa-location-dot text-brand-300"></i>
                    {{ $order['address'] }}
                </div>
                <div class="flex gap-2">
                    <button onclick="toggleDetails('order-{{ $order['id'] }}')"
                        class="text-xs text-brand-400 hover:text-brand-600 font-medium transition flex items-center gap-1">
                        Details <i class="fa-solid fa-chevron-down text-[10px]" id="chevron-{{ $order['id'] }}"></i>
                    </button>
                    @if($order['status'] == 'delivered')
                    <form method="POST" action="{{ route('cart.reorder') }}">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order['id'] }}">
                        <button type="submit" class="text-xs text-brand-400 hover:text-brand-600 font-medium transition flex items-center gap-1">
                            <i class="fa-solid fa-rotate-left"></i> Reorder
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Expandable Details --}}
            <div id="order-{{ $order['id'] }}" class="hidden border-t border-brand-100 px-6 py-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <h4 class="text-xs uppercase tracking-wider text-ink/40 font-semibold mb-2">Items</h4>
                        <div class="space-y-2">
                            @foreach($order['items'] as $item)
                            <div class="flex justify-between">
                                <span class="text-ink/70">{{ $item['name'] }} ×{{ $item['qty'] }}</span>
                                <span class="font-medium text-ink">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs uppercase tracking-wider text-ink/40 font-semibold mb-2">Summary</h4>
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-ink/60"><span>Subtotal</span><span>${{ number_format($order['subtotal'], 2) }}</span></div>
                            <div class="flex justify-between text-ink/60"><span>Delivery</span><span>$2.99</span></div>
                            <div class="flex justify-between text-ink/60"><span>Tax</span><span>${{ number_format($order['subtotal'] * 0.08, 2) }}</span></div>
                            <div class="flex justify-between font-bold text-ink border-t border-brand-100 pt-1.5 mt-1.5">
                                <span>Total</span><span class="text-brand-500">${{ number_format($order['total'], 2) }}</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="text-xs text-ink/40">Payment: <span class="text-ink/70 font-medium">{{ ucwords(str_replace('_', ' ', $order['payment'])) }}</span></p>
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
