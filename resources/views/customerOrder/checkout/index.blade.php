@extends('CustomerOrder.layouts.app')

@section('title', 'Checkout — Saveur')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="font-display text-4xl font-bold text-ink mb-2">Checkout</h1>
    <p class="text-ink/50 text-sm mb-8">Almost there — just confirm your delivery details.</p>

    <form method="POST" action="{{ route('customerOrder.checkout.place') }}" id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- ===== LEFT: Delivery + Payment ===== --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Delivery Address --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50">
                    <div class="flex items-center gap-2 mb-5">
                        <span class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center">
                            <i class="fa-solid fa-location-dot text-brand-400 text-sm"></i>
                        </span>
                        <h2 class="font-display text-xl font-bold text-ink">Delivery Address</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-xs font-semibold text-ink/60 mb-1.5 uppercase tracking-wide">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                class="w-full border border-brand-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-300 bg-cream/50">
                            @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-xs font-semibold text-ink/60 mb-1.5 uppercase tracking-wide">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                class="w-full border border-brand-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-300 bg-cream/50">
                            @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-ink/60 mb-1.5 uppercase tracking-wide">Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                class="w-full border border-brand-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-300 bg-cream/50">
                            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-ink/60 mb-1.5 uppercase tracking-wide">Street Address</label>
                            <input type="text" name="address" value="{{ old('address') }}" required
                                placeholder="123 Main Street, Apt 4B"
                                class="w-full border border-brand-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-300 bg-cream/50">
                            @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink/60 mb-1.5 uppercase tracking-wide">City</label>
                            <input type="text" name="city" value="{{ old('city') }}" required
                                class="w-full border border-brand-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-300 bg-cream/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink/60 mb-1.5 uppercase tracking-wide">ZIP Code</label>
                            <input type="text" name="zip" value="{{ old('zip') }}" required
                                class="w-full border border-brand-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-300 bg-cream/50">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-ink/60 mb-1.5 uppercase tracking-wide">Delivery Notes (optional)</label>
                            <textarea name="notes" rows="2" placeholder="Ring doorbell, leave at door…"
                                class="w-full border border-brand-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-300 bg-cream/50 resize-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50">
                    <div class="flex items-center gap-2 mb-5">
                        <span class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center">
                            <i class="fa-solid fa-credit-card text-brand-400 text-sm"></i>
                        </span>
                        <h2 class="font-display text-xl font-bold text-ink">Payment</h2>
                    </div>

                    <div class="space-y-3">
                        @foreach([
                            ['cod', 'fa-money-bills', 'Cash on Delivery', 'Pay in cash when your order arrives'],
                            ['card', 'fa-credit-card', 'Credit / Debit Card', 'Visa, Mastercard, Amex accepted'],
                            ['wallet', 'fa-wallet', 'Digital Wallet', 'Apple Pay, Google Pay'],
                        ] as [$val, $icon, $label, $desc])
                        <label class="flex items-center gap-4 p-4 border-2 rounded-xl cursor-pointer transition
                            {{ old('payment', 'cod') == $val ? 'border-brand-400 bg-brand-50' : 'border-brand-100 hover:border-brand-200' }}">
                            <input type="radio" name="payment" value="{{ $val }}" class="hidden peer"
                                {{ old('payment', 'cod') == $val ? 'checked' : '' }}>
                            <span class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid {{ $icon }} text-brand-400"></i>
                            </span>
                            <div>
                                <div class="font-semibold text-sm text-ink">{{ $label }}</div>
                                <div class="text-xs text-ink/50">{{ $desc }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT: Order Summary ===== --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 sticky top-28">
                    <h2 class="font-display text-xl font-bold text-ink mb-4">Your Order</h2>

                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto pr-1">
                        @php $subtotal = 0; @endphp
                        @foreach($cart as $id => $item)
                        @php $subtotal += $item['price'] * $item['qty']; @endphp
                        <div class="flex items-center gap-3">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-12 h-12 object-cover rounded-lg flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-ink truncate">{{ $item['name'] }}</p>
                                <p class="text-xs text-ink/50">x{{ $item['qty'] }}</p>
                            </div>
                            <span class="text-sm font-bold text-brand-500">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t border-brand-100 pt-4 space-y-2 text-sm text-ink/70">
                        <div class="flex justify-between"><span>Subtotal</span><span class="font-medium text-ink">${{ number_format($subtotal, 2) }}</span></div>
                        <div class="flex justify-between"><span>Delivery</span><span class="font-medium text-ink">$2.99</span></div>
                        <div class="flex justify-between"><span>Tax (8%)</span><span class="font-medium text-ink">${{ number_format($subtotal * 0.08, 2) }}</span></div>
                    </div>

                    @php $total = $subtotal + 2.99 + ($subtotal * 0.08); @endphp
                    <div class="border-t border-brand-100 mt-3 pt-3 flex justify-between items-center mb-5">
                        <span class="font-bold text-ink">Total</span>
                        <span class="font-bold text-brand-500 text-2xl">${{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2 py-3.5 rounded-full font-semibold shadow-md">
                        <i class="fa-solid fa-check-circle"></i> Place Order
                    </button>

                    <div class="flex items-center justify-center gap-2 mt-4 text-xs text-ink/40">
                        <i class="fa-solid fa-lock"></i>
                        <span>Secure checkout — your data is protected</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
