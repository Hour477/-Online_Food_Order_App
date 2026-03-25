@extends('customerOrder.layouts.app')

@section('title', __('app.checkout_title') . ' — Saveur')

@section('content')
@php
    $names = explode(' ', $customer->name ?? '', 2);
    $firstName = $names[0] ?? '';
    $lastName = $names[1] ?? '';
@endphp
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-4xl font-bold text-gray-900 mb-2 ">{{ __('app.checkout_title') }}</h1>
    <p class="text-gray-500 text-sm mb-8 ">{{ __('app.checkout_subtitle') }}</p>

    <form method="POST" action="{{ route('customerOrder.checkout.place') }}" id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- ===== LEFT: Delivery + Payment ===== --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Delivery Address --}}
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center gap-2 mb-5">
                        <span class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                            <i class="fa-solid fa-location-dot text-amber-600 text-sm"></i>
                        </span>
                        <h2 class="text-xl font-bold text-gray-900 ">{{ __('app.delivery_address') }}</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide ">{{ __('app.first_name') }}</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $firstName) }}" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 bg-gray-50">
                            @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide ">{{ __('app.last_name') }}</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $lastName) }}" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 bg-gray-50">
                            @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide ">{{ __('app.phone') }}</label>
                            <input type="tel" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 bg-gray-50">
                            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide ">{{ __('app.street_address') }}</label>
                            <input type="text" name="address" value="{{ old('address', $customer->address ?? '') }}" required
                                placeholder="{{ __('app.address_placeholder') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 bg-gray-50">
                            @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide ">{{ __('app.city') }}</label>
                            <input type="text" name="city" value="{{ old('city', $customer->city ?? '') }}" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide ">{{ __('app.zip_code') }}</label>
                            <input type="text" name="zip" value="{{ old('zip') }}" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 bg-gray-50">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide ">{{ __('app.delivery_notes') }}</label>
                            <textarea name="notes" rows="2" placeholder="{{ __('app.notes_placeholder') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 bg-gray-50 resize-none">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center gap-2 mb-5">
                        <span class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                            <i class="fa-solid fa-credit-card text-amber-600 text-sm"></i>
                        </span>
                        <h2 class="text-xl font-bold text-gray-900 ">{{ __('app.payment') }}</h2>
                    </div>

                    <div class="space-y-3" id="payment-options">
                        @foreach([
                            ['cash', 'fa-money-bills', __('app.cash'), __('app.cash_description')],
                            ['khqr', 'fa-qrcode', 'KHQR', __('app.khqr_description')],
                        ] as [$val, $icon, $label, $desc])
                        <label class="payment-option flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer transition
                            {{ old('payment', 'cash') == $val ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-gray-300' }}"
                            data-value="{{ $val }}">
                            <input type="radio" name="payment" value="{{ $val }}"
                                class="sr-only"
                                {{ old('payment', 'cash') == $val ? 'checked' : '' }}>
                            <span class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid {{ $icon }} text-amber-600"></i>
                            </span>
                            <div>
                                <div class="font-semibold text-sm text-gray-900">{{ $label }}</div>
                                <div class="text-xs text-gray-500">{{ $desc }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT: Order Summary ===== --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 sticky top-28">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 ">{{ __('app.your_order') }}</h2>

                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto pr-1">
                        @foreach($cart as $id => $item)
                        <div class="flex items-center gap-3">
                            @if($item['image'])
                                <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-12 h-12 object-cover rounded-lg flex-shrink-0">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-utensils text-xs text-gray-300"></i>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500">x{{ $item['qty'] }}</p>
                            </div>
                            <span class="text-sm font-bold text-amber-600">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 pt-4 space-y-2 text-sm text-gray-600 ">
                        <div class="flex justify-between"><span>{{ __('app.subtotal') }}</span><span class="font-medium text-gray-900">${{ number_format($subtotal, 2) }}</span></div>
                        <div class="flex justify-between"><span>{{ __('app.tax') }} (10%)</span><span class="font-medium text-gray-900">${{ number_format($tax, 2) }}</span></div>
                    </div>

                    <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between items-center mb-5">
                        <span class="font-bold text-gray-900 ">{{ __('app.total') }}</span>
                        <span class="font-bold text-amber-600 text-2xl">${{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" id="place-order-btn" class="w-full flex items-center justify-center gap-2 py-3.5 rounded-lg font-semibold bg-amber-600 text-white hover:bg-amber-700 transition ">
                        <i class="fa-solid fa-check-circle"></i> {{ __('app.place_order') }}
                    </button>

                    <div class="flex items-center justify-center gap-2 mt-4 text-xs text-gray-400 ">
                        <i class="fa-solid fa-lock"></i>
                        <span>{{ __('app.secure_checkout') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- KHQR Payment Modal --}}
<div id="khqr-modal" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeKhqrModal()"></div>
    
    {{-- Modal Content --}}
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden animate-fade-in">
            {{-- Header --}}
            <div class="bg-amber-600 px-6 py-4 text-center relative">
                <button onclick="closeKhqrModal()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 hover:text-white">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
                <h1 class="text-xl font-bold text-white">Scan to Pay</h1>
                <p class="text-amber-100 text-sm mt-1">Use your banking app to scan</p>
            </div>

            {{-- QR Content --}}
            <div class="p-6 text-center" id="khqr-content">
                {{-- Loading State --}}
                <div id="khqr-loading" class="py-12">
                    <div class="animate-spin w-12 h-12 border-4 border-amber-200 border-t-amber-600 rounded-full mx-auto mb-4"></div>
                    <p class="text-gray-600">Generating QR code...</p>
                </div>

                {{-- QR Code (hidden initially) --}}
                <div id="khqr-qr-section" class="hidden">
                    <div class="bg-white p-4 rounded-xl border-2 border-gray-200 inline-block mb-4">
                        <img src="" alt="KHQR Code" class="w-56 h-56 mx-auto" id="khqr-image">
                    </div>

                    {{-- Amount --}}
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Amount</p>
                        <p class="text-3xl font-bold text-gray-900" id="khqr-amount">$0.00</p>
                    </div>

                    {{-- Order Info --}}
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-500">Order No.</span>
                            <span class="font-semibold text-gray-900" id="khqr-order-no">-</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Expires in</span>
                            <span class="font-semibold text-red-600" id="khqr-countdown">--:--</span>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div id="khqr-status" class="mb-4">
                        <div class="flex items-center justify-center gap-2 text-amber-600">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Waiting for payment...</span>
                        </div>
                    </div>

                    {{-- Instructions --}}
                    <div class="text-left bg-blue-50 rounded-lg p-4 mb-4">
                        <h3 class="font-semibold text-blue-900 text-sm mb-2">How to pay:</h3>
                        <ol class="text-xs text-blue-700 space-y-1 list-decimal list-inside">
                            <li>Open your banking app (ABA, ACLEDA, etc.)</li>
                            <li>Scan the QR code above</li>
                            <li>Confirm the payment</li>
                            <li>Wait for confirmation</li>
                        </ol>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button type="button" onclick="cancelKhqrPayment()" class="flex-1 py-2.5 px-4 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="button" onclick="checkKhqrStatusManual()" class="flex-1 py-2.5 px-4 rounded-lg bg-amber-600 text-white font-medium hover:bg-amber-700 transition">
                            Check Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fade-in {
    animation: fade-in 0.2s ease-out;
}
</style>

@push('scripts')
<script>
    // Payment option selection
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.classList.remove('border-amber-500', 'bg-amber-50');
                opt.classList.add('border-gray-200');
            });
            this.classList.remove('border-gray-200');
            this.classList.add('border-amber-500', 'bg-amber-50');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    // Form submission
    let currentOrderId = null;
    let khqrCheckInterval = null;
    let khqrCountdownInterval = null;

    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment"]:checked')?.value;
        
        if (paymentMethod === 'khqr') {
            e.preventDefault();
            submitKhqrOrder();
        }
    });

    function submitKhqrOrder() {
        const form = document.getElementById('checkout-form');
        const formData = new FormData(form);
        const btn = document.getElementById('place-order-btn');
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.redirected) {
                // If redirected, follow the redirect
                window.location.href = response.url;
                return null;
            }
            return response.json();
        })
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Place Order';
            
            if (data && data.success && data.order_id) {
                currentOrderId = data.order_id;
                showKhqrModal(data.qr_image, data.amount, data.order_no, data.expires_at);
            } else if (data && data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Place Order';
            console.error('Error:', error);
        });
    }

    function showKhqrModal(qrImage, amount, orderNo, expiresAt) {
        document.getElementById('khqr-image').src = qrImage;
        document.getElementById('khqr-amount').textContent = '$' + parseFloat(amount).toFixed(2);
        document.getElementById('khqr-order-no').textContent = orderNo;
        
        document.getElementById('khqr-loading').classList.add('hidden');
        document.getElementById('khqr-qr-section').classList.remove('hidden');
        document.getElementById('khqr-modal').classList.remove('hidden');
        
        // Start countdown
        startKhqrCountdown(new Date(expiresAt));
        
        // Start status checking
        khqrCheckInterval = setInterval(checkKhqrStatus, 5000);
    }

    function closeKhqrModal() {
        document.getElementById('khqr-modal').classList.add('hidden');
        clearInterval(khqrCheckInterval);
        clearInterval(khqrCountdownInterval);
    }

    function startKhqrCountdown(expiresAt) {
        function update() {
            const now = new Date();
            const diff = expiresAt - now;
            
            if (diff <= 0) {
                document.getElementById('khqr-countdown').textContent = 'Expired';
                document.getElementById('khqr-countdown').classList.remove('text-red-600');
                document.getElementById('khqr-countdown').classList.add('text-gray-500');
                clearInterval(khqrCountdownInterval);
                clearInterval(khqrCheckInterval);
                document.getElementById('khqr-status').innerHTML = `
                    <div class="flex items-center justify-center gap-2 text-red-600">
                        <i class="fas fa-times-circle text-xl"></i>
                        <span>QR code has expired</span>
                    </div>
                `;
                return;
            }
            
            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            document.getElementById('khqr-countdown').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
        
        update();
        khqrCountdownInterval = setInterval(update, 1000);
    }

    function checkKhqrStatus() {
        if (!currentOrderId) return;
        
        fetch(`/checkout/khqr-status/${currentOrderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'paid') {
                    clearInterval(khqrCheckInterval);
                    clearInterval(khqrCountdownInterval);
                    
                    document.getElementById('khqr-status').innerHTML = `
                        <div class="flex items-center justify-center gap-2 text-emerald-600">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span>Payment successful!</span>
                        </div>
                    `;
                    
                    setTimeout(() => {
                        window.location.href = data.redirect || '/checkout/confirmation';
                    }, 1500);
                } else if (data.status === 'expired') {
                    clearInterval(khqrCheckInterval);
                    clearInterval(khqrCountdownInterval);
                    
                    document.getElementById('khqr-status').innerHTML = `
                        <div class="flex items-center justify-center gap-2 text-red-600">
                            <i class="fas fa-times-circle text-xl"></i>
                            <span>QR code has expired</span>
                        </div>
                    `;
                }
            })
            .catch(error => console.error('Error checking status:', error));
    }

    function checkKhqrStatusManual() {
        checkKhqrStatus();
    }

    function cancelKhqrPayment() {
        if (!currentOrderId) return;
        
        fetch(`/checkout/khqr-cancel/${currentOrderId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(() => {
            closeKhqrModal();
            window.location.href = '/menu';
        });
    }
</script>
@endpush
@endsection
