@extends('customerOrder.layouts.app')

@section('title', __('app.rate_your_order') . ' — Saveur')

@push('styles')
<style>
    .star-btn i {
        transition: transform 0.15s ease, color 0.15s ease;
    }
    .star-btn:hover i {
        transform: scale(1.2);
    }
    .star-btn.active i {
        color: #f59e0b !important;
        transform: scale(1.15);
    }
    .item-card {
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .item-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .rating-label {
        transition: opacity 0.2s ease;
    }
    .rated-badge {
        animation: popIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes popIn {
        from { transform: scale(0.7); opacity: 0; }
        to   { transform: scale(1);   opacity: 1; }
    }
    .submit-btn {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        transition: all 0.2s ease;
    }
    .submit-btn:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        box-shadow: 0 6px 20px rgba(217, 119, 6, 0.35);
        transform: translateY(-1px);
    }
    .submit-btn:active {
        transform: translateY(0);
    }
    .progress-ring {
        transition: stroke-dashoffset 0.5s ease;
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page Header --}}
    <div class="mb-8">
        <a href="{{ route('customerOrder.orders.history') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-amber-600 transition mb-4 group">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            {{ __('app.order_history') }}
        </a>
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 battambang-bold">
                    {{ __('app.rate_your_order') }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 battambang-regular">
                    {{ __('app.order') }} #{{ $order['id'] }} ·
                    {{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y') }}
                </p>
            </div>
            {{-- Progress indicator --}}
            <div class="hidden sm:flex flex-col items-center gap-1 text-center">
                <div class="relative w-14 h-14">
                    <svg class="w-14 h-14 -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.9" fill="none"
                                stroke="#f3f4f6" stroke-width="3"/>
                        <circle id="progress-ring" cx="18" cy="18" r="15.9" fill="none"
                                stroke="#f59e0b" stroke-width="3"
                                stroke-dasharray="100"
                                stroke-dashoffset="100"
                                stroke-linecap="round"/>
                    </svg>
                    <span id="progress-count"
                          class="absolute inset-0 flex items-center justify-center text-xs font-bold text-amber-600">
                        0/{{ count($order['items']) }}
                    </span>
                </div>
                <span class="text-[10px] text-gray-400 font-medium">RATED</span>
            </div>
        </div>
    </div>

    {{-- Alert: all rated --}}
    <div id="all-rated-banner"
         class="hidden mb-6 flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700">
        <i class="fa-solid fa-circle-check text-xl"></i>
        <div>
            <p class="font-semibold text-sm">{{ __('app.all_items_rated') ?? 'All items rated!' }}</p>
            <p class="text-xs text-emerald-600">{{ __('app.thank_you_rating') }}</p>
        </div>
    </div>

    {{-- Items --}}
    <div class="space-y-4" id="item-list">
        @foreach($order['items'] as $index => $item)
        <div class="item-card bg-white rounded-2xl border border-gray-200 overflow-hidden"
             id="item-card-{{ $index }}">

            {{-- Item Info Row --}}
            <div class="flex items-center gap-4 px-5 py-4">
                <img src="{{ $item['display_image'] }}"
                     alt="{{ $item['name'] }}"
                     class="w-14 h-14 rounded-xl object-cover flex-shrink-0 border border-gray-100">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 truncate">{{ $item['name'] }}</p>
                    <p class="text-xs text-gray-400">×{{ $item['qty'] }} · ${{ number_format($item['price'], 2) }}</p>
                </div>
                @if($item['has_rating'])
                <span class="rated-badge flex-shrink-0 inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                    <i class="fa-solid fa-star text-[10px]"></i> {{ $item['rating'] }}/5
                </span>
                @endif
            </div>

            {{-- Rating Form --}}
            <form action="{{ route('customerOrder.orders.rate') }}" method="POST"
                  class="rating-form border-t border-gray-100 px-5 py-5 bg-gray-50/60"
                  data-index="{{ $index }}"
                  data-total="{{ count($order['items']) }}">
                @csrf
                <input type="hidden" name="order_id"     value="{{ $order['db_id'] }}">
                <input type="hidden" name="menu_item_id" value="{{ $item['id'] }}">
                <input type="hidden" name="rating"       value="{{ $item['rating'] ?? 5 }}"
                       class="rating-input" id="rating-input-{{ $index }}">

                {{-- Stars --}}
                <div class="flex items-center gap-1 mb-4" id="stars-{{ $index }}">
                    @for($s = 1; $s <= 5; $s++)
                    <button type="button"
                            onclick="setItemRating({{ $index }}, {{ $s }})"
                            onmouseover="hoverStars({{ $index }}, {{ $s }})"
                            onmouseleave="resetHover({{ $index }})"
                            class="star-btn p-1 focus:outline-none"
                            data-index="{{ $index }}"
                            data-value="{{ $s }}">
                        <i class="fa-solid fa-star text-3xl
                            {{ ($item['rating'] ?? 0) >= $s ? 'text-amber-400' : 'text-gray-200' }}
                            star-icon-{{ $index }}"></i>
                    </button>
                    @endfor
                    <span id="rating-label-{{ $index }}"
                          class="rating-label ml-3 text-sm font-medium text-gray-500">
                        @php
                            $labels = [1=>'Terrible',2=>'Poor',3=>'Average',4=>'Good',5=>'Excellent!'];
                            echo $labels[$item['rating'] ?? 5] ?? 'Excellent!';
                        @endphp
                    </span>
                </div>

                {{-- Comment --}}
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                        {{ __('app.write_review') }}
                        <span class="normal-case font-normal text-gray-400">({{ __('app.optional') ?? 'optional' }})</span>
                    </label>
                    <textarea name="comment" rows="2" maxlength="500"
                              class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all resize-none placeholder-gray-300"
                              placeholder="{{ __('app.share_your_experience') ?? 'Share your experience…' }}"
                    >{{ $item['comment'] ?? '' }}</textarea>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end">
                    <button type="submit"
                            class="submit-btn inline-flex items-center gap-2 px-5 py-2.5 text-white text-sm font-semibold rounded-xl shadow">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        {{ $item['has_rating'] ? __('app.update_review') ?? 'Update Review' : __('app.submit_review') }}
                    </button>
                </div>
            </form>
        </div>
        @endforeach
    </div>

    {{-- Footer actions --}}
    <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <a href="{{ route('customerOrder.orders.history') }}"
           class="text-sm text-gray-500 hover:text-amber-600 transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            {{ __('app.back_to_orders') ?? 'Back to Orders' }}
        </a>
        <a href="{{ route('customerOrder.menu.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm bg-amber-600 text-white hover:bg-amber-700 transition shadow battambang-regular">
            <i class="fa-solid fa-utensils"></i>
            {{ __('app.order_more') ?? 'Order More' }}
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
const ratingLabels = ['', 'Terrible', 'Poor', 'Average', 'Good', 'Excellent!'];
// Pre-compute rated count from server-side data
let ratedCount = {{ collect($order['items'])->filter(fn($i) => $i['has_rating'])->count() }};
const totalItems = {{ count($order['items']) }};

// Update progress ring on load
updateProgress();

function setItemRating(index, val) {
    document.getElementById('rating-input-' + index).value = val;
    paintStars(index, val);
    document.getElementById('rating-label-' + index).textContent = ratingLabels[val];
}

function hoverStars(index, val) {
    paintStars(index, val, true);
    document.getElementById('rating-label-' + index).textContent = ratingLabels[val];
}

function resetHover(index) {
    const current = parseInt(document.getElementById('rating-input-' + index).value) || 5;
    paintStars(index, current);
    document.getElementById('rating-label-' + index).textContent = ratingLabels[current];
}

function paintStars(index, val, isHover = false) {
    const stars = document.querySelectorAll('.star-icon-' + index);
    stars.forEach((star, i) => {
        const filled = i + 1 <= val;
        star.classList.toggle('text-amber-400', filled);
        star.classList.toggle('text-amber-200', isHover && !filled);
        star.classList.toggle('text-gray-200', !isHover && !filled);
    });
}

function updateProgress() {
    const pct = totalItems > 0 ? (ratedCount / totalItems) * 100 : 0;
    const ring = document.getElementById('progress-ring');
    if (ring) ring.style.strokeDashoffset = (100 - pct).toString();
    const countEl = document.getElementById('progress-count');
    if (countEl) countEl.textContent = ratedCount + '/' + totalItems;

    if (ratedCount >= totalItems) {
        document.getElementById('all-rated-banner')?.classList.remove('hidden');
    }
}

// Intercept form submissions to update the progress ring immediately
document.querySelectorAll('.rating-form').forEach(form => {
    form.addEventListener('submit', function () {
        const card = this.closest('.item-card');
        if (card) {
            const badge = card.querySelector('.rated-badge');
            if (!badge) {
                // Will be shown after page reload, just increment optimistically
                ratedCount = Math.min(ratedCount + 1, totalItems);
            }
        }
    });
});
</script>
@endpush
