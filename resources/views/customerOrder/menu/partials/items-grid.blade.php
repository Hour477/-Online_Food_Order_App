@foreach($items as $item)
<div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 card-hover">

    {{-- Image --}}
    <div class="relative">
        @if($item->image)
            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                <i class="fas fa-utensils text-4xl text-gray-300"></i>
            </div>
        @endif
        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-xs font-semibold px-2 py-1 rounded-full text-gray-700 battambang-regular">
            🍽 {{ $item->category->name ?? __('app.uncategorized') }}
        </span>

        {{-- Like Button --}}
        <button type="button" 
                onclick="toggleLike(this, {{ $item->id }})" 
                class="absolute top-3 right-3 w-10 h-10 rounded-full bg-white/90 backdrop-blur shadow-sm flex items-center justify-center transition-all duration-300 hover:scale-110 group {{ Auth::check() && $item->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}"
                id="like-btn-{{ $item->id }}">
            <i class="{{ Auth::check() && $item->isLikedBy(Auth::user()) ? 'fa-solid' : 'fa-regular' }} fa-heart text-xl"></i>
            <span class="absolute -bottom-1 -right-1 bg-amber-500 text-white text-[10px] px-1 rounded-full font-bold likes-count">
                {{ $item->likes_count }}
            </span>
        </button>
    </div>

    {{-- Info --}}
    <div class="p-4">
        <h3 class="font-bold text-gray-900 text-lg leading-tight mb-1 battambang-bold">{{ $item->name }}</h3>
        <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-3 battambang-regular">{{ $item->description }}</p>

        <div class="flex items-center justify-between mt-auto">
            <span class="font-bold text-amber-600 text-lg">${{ number_format($item->price, 2) }}</span>

            <form method="POST" action="{{ route('customerOrder.cart.add') }}" class="flex items-center gap-2">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                    <button type="button" onclick="decQty(this)" class="px-2 py-1 text-gray-500 hover:bg-gray-100 transition text-sm">−</button>
                    <input type="number" name="quantity" value="1" min="1" max="99"
                        class="w-8 text-center text-sm font-medium bg-transparent focus:outline-none qty-input">
                    <button type="button" onclick="incQty(this)" class="px-2 py-1 text-gray-500 hover:bg-gray-100 transition text-sm">+</button>
                </div>
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium bg-amber-600 text-white hover:bg-amber-700 transition shadow battambang-regular">
                    <i class="fa-solid fa-plus text-xs"></i> {{ __('app.add_to_cart') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- Pagination Links for AJAX/Infinite Scroll --}}
@if($items->hasMorePages())
    <div id="pagination-next-url" class="hidden">{{ $items->nextPageUrl() }}</div>
@endif
