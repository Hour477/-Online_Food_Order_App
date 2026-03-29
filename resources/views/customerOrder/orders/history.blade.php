@extends('customerOrder.layouts.app')

@section('title', __('app.order_history') . ' — Saveur')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 battambang-bold">{{ __('app.order_history') }}</h1>
            <p class="text-gray-500 text-sm mt-1 battambang-regular">{{ __('app.order_history_subtitle') }}</p>
        </div>
        <a href="{{ route('customerOrder.menu.index') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium text-sm bg-amber-600 text-white hover:bg-amber-700 transition battambang-regular">
            <i class="fa-solid fa-plus"></i> {{ __('app.new_order') }}
        </a>
    </div>

    @if(empty($orders))
    <div class="text-center py-24 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-receipt text-3xl text-gray-300"></i>
        </div>
        <h2 class="text-2xl text-gray-500 mb-2 battambang-regular">{{ __('app.no_orders_yet') }}</h2>
        <p class="text-gray-400 text-sm mb-6 battambang-regular">{{ __('app.order_history_empty') }}</p>
        <a href="{{ route('customerOrder.menu.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-medium text-sm bg-amber-600 text-white hover:bg-amber-700 transition battambang-regular">
            <i class="fa-solid fa-utensils"></i> {{ __('app.start_ordering') }}
        </a>
    </div>

    @else
    <div class="space-y-5">
        @foreach($orders as $order)
        @php
            $statusConfig = [
                'pending'    => ['bg-yellow-100 text-yellow-700',  'fa-clock',            __('app.pending')],
                'confirmed'  => ['bg-blue-100 text-blue-700',      'fa-check-circle',     __('app.confirmed')],
                'delivered'  => ['bg-purple-100 text-purple-700',  'fa-truck',            __('app.delivering')],
                'completed'  => ['bg-emerald-100 text-emerald-700','fa-circle-check',     __('app.completed')],
                'refunded'   => ['bg-orange-100 text-orange-700',  'fa-rotate-left',      __('app.refunded')],
                'cancelled'  => ['bg-red-100 text-red-700',        'fa-circle-xmark',     __('app.cancelled')],
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
                        <img src="{{ $item['display_image'] }}" alt="{{ $item['name'] }}" class="w-6 h-6 rounded-full object-cover">
                        <span class="text-xs font-medium text-gray-900">{{ $item['name'] }}</span>
                        <span class="text-xs text-gray-500">×{{ $item['qty'] }}</span>
                    </div>
                    @endforeach
                    @if(count($order['items']) > 4)
                    <span class="text-xs text-gray-400">+{{ count($order['items']) - 4 }} {{ __('app.more_items') }}</span>
                    @endif
                </div>
            </div>

            {{-- Order Footer --}}
            <div class="px-6 py-3 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    {{-- delivery notes --}}
                        
                </div>
                <div class="flex gap-2">
                    <button onclick="toggleDetails('order-{{ $order['db_id'] }}')"
                        class="text-xs text-amber-600 hover:text-amber-700 font-medium transition flex items-center gap-1 battambang-regular">
                        {{ __('app.details') }} <i class="fa-solid fa-chevron-down text-[10px]" id="chevron-{{ $order['db_id'] }}"></i>
                    </button>
                    @if($order['status'] == 'completed')
                    <a href="{{ route('customerOrder.orders.rate.page', $order['db_id']) }}"
                       class="text-xs text-amber-600 hover:text-amber-700 font-medium transition flex items-center gap-1 battambang-regular">
                        <i class="fa-solid fa-star"></i> {{ __('app.rate') }}
                    </a>
                    <form method="POST" action="{{ route('customerOrder.cart.reorder') }}">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order['db_id'] }}">
                        <button type="submit" class="text-xs text-amber-600 hover:text-amber-700 font-medium transition flex items-center gap-1 battambang-regular">
                            <i class="fa-solid fa-rotate-left"></i> {{ __('app.reorder') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Expandable Details --}}
            <div id="order-{{ $order['db_id'] }}" class="hidden border-t border-gray-200 px-6 py-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <h4 class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-2 battambang-regular">{{ __('app.items') }}</h4>
                        <div class="space-y-2">
                            @foreach($order['items'] as $item)
                            <div class="flex justify-between items-center group">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600">{{ $item['name'] }} ×{{ $item['qty'] }}</span>
                                    @if($order['status'] == 'completed')
                                    <button
                                        type="button"
                                        onclick="openRatingModal({{ $order['db_id'] }}, {{ $item['id'] }}, @js($item['name']), {{ $item['rating'] ?? 5 }}, @js($item['comment'] ?? ''))"
                                        class="inline-flex items-center gap-1 text-[10px] px-2 py-0.5 rounded border transition-all font-medium {{ $item['has_rating'] ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100 hover:scale-105' : 'bg-amber-50 text-amber-600 border-amber-100 hover:bg-amber-100 hover:scale-105' }}">
                                        <i class="fa-solid fa-star"></i> 
                                        {{ $item['has_rating'] ? __('app.edit') : __('app.rate') }}
                                    </button>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="block font-medium text-gray-900">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                                    @if($item['has_rating'])
                                    <span class="block text-[11px] text-gray-500">
                                        {{ $item['rating'] }}/5
                                        @if($item['comment'])
                                        · {{ \Illuminate\Support\Str::limit($item['comment'], 32) }}
                                        @endif
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-2 battambang-regular">{{ __('app.summary') }}</h4>
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-gray-600"><span>{{ __('app.subtotal') }}</span><span>${{ number_format($order['subtotal'], 2) }}</span></div>
                            <div class="flex justify-between text-gray-600"><span>{{ __('app.tax') }} (10%)</span><span>${{ number_format($order['subtotal'] * 0.10, 2) }}</span></div>
                            <div class="flex justify-between font-bold text-gray-900 border-t border-gray-200 pt-1.5 mt-1.5">
                                <span>{{ __('app.total') }}</span><span class="text-amber-600">${{ number_format($order['total'], 2) }}</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="text-xs text-gray-400 battambang-regular">{{ __('app.payment') }}: <span class="text-gray-700 font-medium">{{ $order['payment'] }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- Rating Modal --}}
<div id="ratingModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true" onclick="closeRatingModal()"></div>
        
        <!-- Modal panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('customerOrder.orders.rate') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" id="modal-order-id">
                <input type="hidden" name="menu_item_id" id="modal-item-id">
                <input type="hidden" name="rating" id="modal-rating-value" value="5">

                <div class="px-6 py-6 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900" id="modalTitle">{{ __('app.rate_item') }}</h3>
                        <button type="button" onclick="closeRatingModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fa-solid fa-times text-lg"></i>
                        </button>
                    </div>

                    <div class="text-center mb-8">
                        <p class="text-sm text-gray-500 mb-2">{{ __('app.how_was_your') }}</p>
                        <h4 id="modal-item-name" class="text-lg font-bold text-amber-600 mb-6 truncate">Item Name</h4>
                        
                        <!-- Star Rating -->
                        <div class="flex items-center justify-center gap-3">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="star-btn focus:outline-none transition-transform hover:scale-110" data-value="{{ $i }}">
                                <i class="fa-solid fa-star text-4xl text-amber-400 hover:text-amber-500 transition-colors"></i>
                            </button>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-400 mt-3" id="rating-text">Excellent!</p>
                    </div>

                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.write_review') }}</label>
                        <textarea name="comment" id="comment" rows="3" maxlength="500"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all resize-none"
                                  placeholder="Share your experience (optional)"></textarea>
                        <p class="text-xs text-gray-400 mt-1 text-right"><span id="char-count">0</span>/500</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-amber-600 text-white rounded-xl font-semibold hover:bg-amber-700 transition-all shadow-lg hover:shadow-xl hover:scale-105">
                        {{ __('app.submit_review') }}
                    </button>
                    <button type="button" onclick="closeRatingModal()" class="w-full sm:w-auto px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-xl font-semibold hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Character counter for comment textarea
document.addEventListener('DOMContentLoaded', function() {
    const commentTextarea = document.getElementById('comment');
    if (commentTextarea) {
        commentTextarea.addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('char-count').textContent = count;
        });
    }
});

function toggleDetails(id) {
    const el = document.getElementById(id);
    const orderId = id.replace('order-', '');
    const chevron = document.getElementById('chevron-' + orderId);
    if (el && chevron) {
        el.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
}

function openRatingModal(orderId, itemId, itemName, currentRating = 5, currentComment = '') {
    // Set form values
    const orderInput = document.getElementById('modal-order-id');
    const itemInput = document.getElementById('modal-item-id');
    const nameEl = document.getElementById('modal-item-name');
    const commentEl = document.getElementById('comment');
    const ratingText = document.getElementById('rating-text');
    
    if (orderInput && itemInput && nameEl) {
        orderInput.value = orderId;
        itemInput.value = itemId;
        nameEl.textContent = itemName;
        
        if (commentEl) {
            commentEl.value = currentComment || '';
            document.getElementById('char-count').textContent = (currentComment || '').length;
        }
        
        // Update rating text
        const ratingTexts = ['Terrible', 'Poor', 'Average', 'Good', 'Excellent!'];
        if (ratingText) {
            ratingText.textContent = ratingTexts[(currentRating || 5) - 1];
        }
        
        // Show modal
        const modal = document.getElementById('ratingModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }
        
        // Set initial star display
        setRating(currentRating || 5);
    } else {
        console.error('Modal elements not found:', { orderInput, itemInput, nameEl });
    }
}

function closeRatingModal() {
    const modal = document.getElementById('ratingModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    }
}

function setRating(val) {
    // Update hidden input
    const ratingInput = document.getElementById('modal-rating-value');
    if (ratingInput) {
        ratingInput.value = val;
    }
    
    // Update stars
    const stars = document.querySelectorAll('.star-btn i');
    stars.forEach((star, index) => {
        const starNum = index + 1;
        if (starNum <= val) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-amber-400');
        } else {
            star.classList.remove('text-amber-400');
            star.classList.add('text-gray-300');
        }
    });
    
    // Update rating text
    const ratingText = document.getElementById('rating-text');
    if (ratingText) {
        const ratingTexts = ['Terrible', 'Poor', 'Average', 'Good', 'Excellent!'];
        ratingText.textContent = ratingTexts[val - 1];
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRatingModal();
    }
});
</script>
@endpush
