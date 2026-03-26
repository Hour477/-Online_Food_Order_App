@extends('customerOrder.layouts.app')

<!-- Page Title -->
@section('title', __('app.order_confirmed'))

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">

    <!-- Success Icon -->
    <div class="w-24 h-24 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-6 animate-bounce">
        <i class="fa-solid fa-check text-emerald-500 text-4xl"></i>
    </div>

    <h1 class="text-4xl font-bold text-gray-900 mb-2 ">{{ __('app.order_placed') }}</h1>
    <p class="text-gray-500 mb-8 ">   
        {{ __('app.order_thank_you') }} <span class="font-bold text-amber-600">#{{ $order->order_no }}</span> {{ __('app.order_confirmed_message') }}
    </p>

    <!-- ETA Card -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-8 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-clock text-amber-600 text-xl"></i>
        </div>
        <div class="text-left">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold ">{{ __('app.estimated_delivery') }}</p>
            <p class="text-2xl font-bold text-amber-600 ">{{ __('app.estimated_delivery_time') }}</p>
        </div>
    </div>

    <!-- Order Details -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 text-left mb-8">
        <h2 class="font-bold text-lg text-gray-900 mb-4 ">{{ __('app.order_details') }}</h2>

        <div class="space-y-3 mb-4">
            @foreach($order->orderItems as $item)
            <div class="flex items-center gap-3">
                <img src="{{ $item->menuItem->display_image }}" alt="{{ $item->menuItem->name }}" class="w-12 h-12 object-cover rounded-lg flex-shrink-0">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900">{{ $item->menuItem->name }}</p>
                    <p class="text-xs text-gray-500">x{{ $item->quantity }}</p>
                </div>
                <span class="text-sm font-bold text-amber-600">${{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>

            @endforeach
        </div>

        <div class="border-t border-gray-200 pt-4 space-y-1.5 text-sm ">
            <div class="flex justify-between text-gray-600"><span>{{ __('app.subtotal') }}</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
            <div class="flex justify-between text-gray-600"><span>{{ __('app.tax') }} (10%)</span><span>${{ number_format($order->tax, 2) }}</span></div>
            <div class="flex justify-between font-bold text-gray-900 text-base pt-1 border-t border-gray-200 mt-1">
                <span>{{ __('app.total') }}</span><span class="text-amber-600">${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-200 text-sm">
            <p class="text-xs text-gray-400 uppercase tracking-wide ">{{ __('app.delivery_info') }}</p>
            <p class="font-medium text-gray-900 mt-0.5 whitespace-pre-line">{{ $order->notes }}</p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('customerOrder.orders.history') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium text-sm bg-amber-600 text-white hover:bg-amber-700 transition ">
            <i class="fa-solid fa-receipt"></i> {{ __('app.view_order_history') }}
        </a>
        <a href="{{ route('customerOrder.menu.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium text-sm border-2 border-gray-300 hover:border-gray-400 transition text-gray-700 ">
            <i class="fa-solid fa-utensils"></i> {{ __('app.order_again') }}
        </a>
    </div>
</div>
@endsection
