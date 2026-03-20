@extends('customerOrder.layouts.app')

@section('title', 'Your Cart — Saveur')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-4xl font-bold text-gray-900 mb-8">
        Your <em class="text-amber-500 not-italic">Basket</em>
    </h1>

    @if(empty($cart))
    {{-- Empty state --}}
    <div class="text-center py-28 bg-white rounded-2xl shadow-sm border border-gray-200">
        <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-5">
            <i class="fa-solid fa-basket-shopping text-4xl text-gray-300"></i>
        </div>
        <h2 class="text-2xl text-gray-500 mb-2">Your basket is empty</h2>
        <p class="text-gray-400 text-sm mb-6">Add some delicious items from the menu!</p>
        <a href="{{ route('customerOrder.menu.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-medium text-sm bg-amber-600 text-white hover:bg-amber-700 transition">
            <i class="fa-solid fa-utensils"></i> Browse Menu
        </a>
    </div>

    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ===== CART ITEMS ===== --}}
        <div class="lg:col-span-2 space-y-4">
            @foreach($cart as $id => $item)
            <div class="flex gap-4 bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                @if($item['image'])
                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                @else
                    <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-utensils text-2xl text-gray-300"></i>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $item['name'] }}</h3>
                            <p class="text-gray-500 text-xs mt-0.5">{{ ucfirst($item['category']) }}</p>
                        </div>
                        <form method="POST" action="{{ route('customerOrder.cart.remove') }}">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $id }}">
                            <button type="submit" class="text-red-400 hover:text-red-600 transition p-1" title="Remove">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </form>
                    </div>

                    <div class="flex items-center justify-between mt-3">
                        <form method="POST" action="{{ route('customerOrder.cart.update') }}" class="flex items-center">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $id }}">
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                <button type="submit" name="action" value="dec" class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 transition text-sm font-bold">−</button>
                                <span class="w-8 text-center text-sm font-semibold text-gray-900">{{ $item['qty'] }}</span>
                                <button type="submit" name="action" value="inc" class="px-3 py-1.5 text-gray-600 hover:bg-gray-100 transition text-sm font-bold">+</button>
                            </div>
                        </form>
                        <span class="font-bold text-amber-600 text-lg">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Clear Cart --}}
            <form method="POST" action="{{ route('customerOrder.cart.clear') }}" class="mt-2">
                @csrf
                <button type="submit" class="text-sm text-gray-400 hover:text-red-500 transition flex items-center gap-1">
                    <i class="fa-solid fa-xmark"></i> Clear entire cart
                </button>
            </form>
        </div>

        {{-- ===== ORDER SUMMARY ===== --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 sticky top-28">
                <h2 class="text-xl font-bold text-gray-900 mb-5">Order Summary</h2>

                <div class="space-y-3 text-sm text-gray-600 mb-4">
                    @php $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart)); @endphp
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-medium text-gray-900">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Delivery fee</span>
                        <span class="font-medium text-gray-900">$2.99</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tax (8%)</span>
                        <span class="font-medium text-gray-900">${{ number_format($subtotal * 0.08, 2) }}</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mb-5 flex justify-between items-center">
                    <span class="font-bold text-gray-900 text-base">Total</span>
                    <span class="font-bold text-amber-600 text-2xl">${{ number_format($subtotal + 2.99 + $subtotal * 0.08, 2) }}</span>
                </div>

                @auth
                    <a href="{{ route('customerOrder.checkout.index') }}" class="w-full flex items-center justify-center gap-2 py-3 rounded-lg font-semibold text-sm bg-amber-600 text-white hover:bg-amber-700 transition">
                        <i class="fa-solid fa-credit-card"></i> Proceed to Checkout
                    </a>
                @else
                    <a href="{{ route('customerOrder.checkout.index') }}" class="w-full flex items-center justify-center gap-2 py-3 rounded-lg font-semibold text-sm bg-gray-900 text-white hover:bg-gray-800 transition">
                        <i class="fa-solid fa-right-to-bracket"></i> Login to Checkout
                    </a>
                    <p class="text-[10px] text-center text-gray-400 mt-2">You need to be logged in to place an order</p>
                @endauth
                <a href="{{ route('customerOrder.menu.index') }}" class="mt-3 block text-center text-sm text-gray-500 hover:text-amber-600 transition">
                    ← Continue Shopping
                </a>
            </div>
        </div>

    </div>
    @endif
</div>
@endsection
