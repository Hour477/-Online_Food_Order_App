@extends('layouts.app')

@section('title', 'Order Confirmed — Saveur')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">

    {{-- Success Icon --}}
    <div class="w-24 h-24 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-6 animate-bounce">
        <i class="fa-solid fa-check text-emerald-500 text-4xl"></i>
    </div>

    <h1 class="font-display text-4xl font-bold text-ink mb-2">Order Placed!</h1>
    <p class="text-ink/50 mb-8">
        Thank you! Your order <span class="font-bold text-brand-400">#{{ $order['id'] }}</span> has been confirmed and is being prepared.
    </p>

    {{-- ETA Card --}}
    <div class="bg-brand-50 border border-brand-200 rounded-2xl p-5 mb-8 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-brand-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-clock text-brand-400 text-xl"></i>
        </div>
        <div class="text-left">
            <p class="text-xs text-ink/50 uppercase tracking-wide font-semibold">Estimated Delivery</p>
            <p class="font-display text-2xl font-bold text-brand-500">30–45 minutes</p>
        </div>
    </div>

    {{-- Order Details --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 text-left mb-8">
        <h2 class="font-display font-bold text-lg text-ink mb-4">Order Details</h2>

        <div class="space-y-3 mb-4">
            @foreach($order['items'] as $item)
            <div class="flex items-center gap-3">
                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-12 h-12 object-cover rounded-lg flex-shrink-0">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-ink">{{ $item['name'] }}</p>
                    <p class="text-xs text-ink/50">x{{ $item['qty'] }}</p>
                </div>
                <span class="text-sm font-bold text-brand-500">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
            </div>
            @endforeach
        </div>

        <div class="border-t border-brand-100 pt-4 space-y-1.5 text-sm">
            <div class="flex justify-between text-ink/60"><span>Subtotal</span><span>${{ number_format($order['subtotal'], 2) }}</span></div>
            <div class="flex justify-between text-ink/60"><span>Delivery</span><span>$2.99</span></div>
            <div class="flex justify-between text-ink/60"><span>Tax</span><span>${{ number_format($order['subtotal'] * 0.08, 2) }}</span></div>
            <div class="flex justify-between font-bold text-ink text-base pt-1 border-t border-brand-100 mt-1">
                <span>Total</span><span class="text-brand-500">${{ number_format($order['total'], 2) }}</span>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-brand-100 grid grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-xs text-ink/40 uppercase tracking-wide">Deliver to</p>
                <p class="font-medium text-ink mt-0.5">{{ $order['address'] }}</p>
            </div>
            <div>
                <p class="text-xs text-ink/40 uppercase tracking-wide">Payment</p>
                <p class="font-medium text-ink mt-0.5">{{ ucwords(str_replace('_', ' ', $order['payment'])) }}</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('orders.history') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full font-medium text-sm shadow">
            <i class="fa-solid fa-receipt"></i> View Order History
        </a>
        <a href="{{ route('menu.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full font-medium text-sm border-2 border-brand-200 hover:border-brand-400 transition">
            <i class="fa-solid fa-utensils"></i> Order Again
        </a>
    </div>
</div>
@endsection
