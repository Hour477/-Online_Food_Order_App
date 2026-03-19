@extends('customerOrder.layouts.app')

@section('title', 'KHQR Payment — Saveur')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        
        {{-- QR Card --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            
            {{-- Header --}}
            <div class="bg-amber-600 px-6 py-4 text-center">
                <h1 class="text-xl font-bold text-white">Scan to Pay</h1>
                <p class="text-amber-100 text-sm mt-1">Use your banking app to scan</p>
            </div>

            {{-- QR Code --}}
            <div class="p-6 text-center">
                <div class="bg-white p-4 rounded-xl border-2 border-gray-200 inline-block mb-4">
                    <img src="{{ $qrImageUrl }}" alt="KHQR Code" class="w-64 h-64 mx-auto" id="qr-image">
                </div>

                {{-- Amount --}}
                <div class="mb-4">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Amount</p>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                </div>

                {{-- Order Info --}}
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Order No.</span>
                        <span class="font-semibold text-gray-900">{{ $order->order_no }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Expires in</span>
                        <span class="font-semibold text-red-600" id="countdown">--:--</span>
                    </div>
                </div>

                {{-- Status --}}
                <div id="payment-status" class="mb-4">
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
                    <form action="{{ route('customerOrder.checkout.khqr-cancel', $order->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-4 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition">
                            Cancel
                        </button>
                    </form>
                    <button type="button" onclick="checkStatus()" class="flex-1 py-2.5 px-4 rounded-lg bg-amber-600 text-white font-medium hover:bg-amber-700 transition">
                        Check Status
                    </button>
                </div>
            </div>
        </div>

        {{-- Bakong App Links --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500 mb-3">Download banking apps:</p>
            <div class="flex justify-center gap-4">
                <a href="https://apps.apple.com/app/aba-bank/id123456789" target="_blank" class="text-xs text-gray-600 hover:text-amber-600">
                    <i class="fab fa-apple text-lg"></i> ABA
                </a>
                <a href="https://apps.apple.com/app/acleda-bank/id123456789" target="_blank" class="text-xs text-gray-600 hover:text-amber-600">
                    <i class="fab fa-apple text-lg"></i> ACLEDA
                </a>
                <a href="https://bakong.nbc.org.kh" target="_blank" class="text-xs text-gray-600 hover:text-amber-600">
                    <i class="fas fa-mobile-alt text-lg"></i> Bakong
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let expiresAt = new Date('{{ $order->payment->khqr_expires_at }}');
    let checkInterval;
    let countdownInterval;

    // Countdown timer
    function updateCountdown() {
        const now = new Date();
        const diff = expiresAt - now;
        
        if (diff <= 0) {
            document.getElementById('countdown').textContent = 'Expired';
            document.getElementById('countdown').classList.remove('text-red-600');
            document.getElementById('countdown').classList.add('text-gray-500');
            clearInterval(countdownInterval);
            clearInterval(checkInterval);
            
            // Show expired message
            document.getElementById('payment-status').innerHTML = `
                <div class="flex items-center justify-center gap-2 text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                    <span>QR code has expired</span>
                </div>
            `;
            return;
        }
        
        const minutes = Math.floor(diff / 60000);
        const seconds = Math.floor((diff % 60000) / 1000);
        document.getElementById('countdown').textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    // Check payment status
    function checkStatus() {
        fetch('{{ route('customerOrder.checkout.khqr-status', $order->id) }}')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'paid') {
                    clearInterval(checkInterval);
                    clearInterval(countdownInterval);
                    
                    // Show success message
                    document.getElementById('payment-status').innerHTML = `
                        <div class="flex items-center justify-center gap-2 text-emerald-600">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span>Payment successful!</span>
                        </div>
                    `;
                    
                    // Redirect to confirmation
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route('customerOrder.checkout.confirmation') }}';
                    }, 1500);
                } else if (data.status === 'expired') {
                    clearInterval(checkInterval);
                    clearInterval(countdownInterval);
                    
                    document.getElementById('payment-status').innerHTML = `
                        <div class="flex items-center justify-center gap-2 text-red-600">
                            <i class="fas fa-times-circle text-xl"></i>
                            <span>QR code has expired</span>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error checking status:', error);
            });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Start countdown
        updateCountdown();
        countdownInterval = setInterval(updateCountdown, 1000);
        
        // Auto-check status every 5 seconds
        checkInterval = setInterval(checkStatus, 5000);
    });
</script>
@endpush
@endsection
