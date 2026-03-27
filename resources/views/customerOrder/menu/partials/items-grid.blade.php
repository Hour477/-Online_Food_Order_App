@foreach($items as $item)
<div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 card-hover">

    {{-- Image --}}
    <div class="relative">
        <img src="{{ $item->display_image }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-xs font-semibold px-2 py-1 rounded-full text-gray-700 battambang-regular">
            🍽 {{ $item->category->name ?? __('app.uncategorized') }}
        </span>

        {{-- Like & Rating Stack --}}
        <div class="absolute top-3 right-3 flex flex-col items-end gap-2">
            {{-- Like Button --}}
            <button type="button" 
                    onclick="toggleLike(this, {{ $item->id }})" 
                    class="w-9 h-9 rounded-full bg-white/95 backdrop-blur shadow-sm flex items-center justify-center transition-all duration-300 hover:scale-110 group {{ Auth::check() && $item->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}"
                    id="like-btn-{{ $item->id }}">
                <i class="{{ Auth::check() && $item->isLikedBy(Auth::user()) ? 'fa-solid' : 'fa-regular' }} fa-heart text-lg"></i>
            </button>

            {{-- Compact Rating Pill --}}
            <div class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-white/95 backdrop-blur shadow-sm border border-amber-100 text-amber-600 transition-all duration-300 hover:scale-105 cursor-default">
                <i class="fa-solid fa-star text-[10px]"></i>
                <span class="text-[11px] font-extrabold tracking-tight">{{ number_format($item->rating, 1) }}</span>
            </div>
        </div>
    </div>

    {{-- Info --}}
    <div class="p-4">
        <h3 class="font-bold text-gray-900 text-lg leading-tight mb-1 battambang-bold">{{ $item->name }}</h3>
        <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-3 battambang-regular">{{ $item->description }}</p>

        <div class="flex items-center justify-between mt-auto">
            <span class="font-bold text-amber-600 text-lg">${{ number_format($item->price, 2) }}</span>

            <form onsubmit="addToCart(event, this)" method="POST" action="{{ route('customerOrder.cart.add') }}" class="flex items-center gap-2">
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
