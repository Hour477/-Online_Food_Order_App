@extends('customerOrder.layouts.app')

@section('title', 'Menu — Saveur')

@section('content')

{{-- ===== HERO BANNER ===== --}}
<section class="relative overflow-hidden bg-gray-900 text-white">
    <div class="absolute inset-0 opacity-20" style="background-image:url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1400&q=80'); background-size:cover; background-position:center;"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex flex-col md:flex-row items-center gap-8">
        <div class="flex-1">
            <p class="text-amber-400 text-sm font-medium tracking-widest uppercase mb-2">Fresh • Local • Delicious</p>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
                What are you<br><em class="text-amber-400 not-italic">craving</em> today?
            </h1>
            <p class="text-gray-300 text-lg mb-6 max-w-md">Explore our handcrafted menu — from hearty meals to refreshing drinks and indulgent desserts.</p>
            <a href="#menu-grid" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-medium text-sm bg-amber-600 text-white hover:bg-amber-700 transition shadow-lg">
                <i class="fa-solid fa-arrow-down"></i> Browse Menu
            </a>
        </div>
        <div class="flex-shrink-0 hidden md:grid grid-cols-2 gap-3">
            @foreach(['https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&q=80',
                       'https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=300&q=80',
                       'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=300&q=80',
                       'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=300&q=80'] as $i => $img)
            <img src="{{ $img }}" alt="food" class="w-36 h-36 object-cover rounded-xl shadow-lg {{ $i % 2 == 1 ? 'mt-4' : '' }}">
            @endforeach
        </div>
    </div>
</section>

{{-- ===== SEARCH & FILTER BAR ===== --}}
<section class="sticky top-16 z-40 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm" id="filter-bar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <form method="GET" action="{{ route('customerOrder.menu.index') }}" id="filter-form" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

            {{-- Search --}}
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search dishes, drinks…"
                    class="w-full pl-9 pr-4 py-2.5 text-sm rounded-lg border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
            </div>

            {{-- Category Tabs --}}
            <div class="flex gap-2 flex-wrap">
                <label class="cursor-pointer">
                    <input type="radio" name="category" value="all" class="hidden peer"
                        {{ request('category', 'all') == 'all' ? 'checked' : '' }}
                        onchange="this.form.submit()">
                    <span class="peer-checked:bg-amber-600 peer-checked:text-white peer-checked:border-amber-600
                                 inline-block px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 bg-white hover:border-amber-500 transition">
                        All
                    </span>
                </label>
                @foreach($categories as $category)
                <label class="cursor-pointer">
                    <input type="radio" name="category" value="{{ $category->name }}" class="hidden peer"
                        {{ request('category') == $category->name ? 'checked' : '' }}
                        onchange="this.form.submit()">
                    <span class="peer-checked:bg-amber-600 peer-checked:text-white peer-checked:border-amber-600
                                 inline-block px-4 py-2 rounded-lg text-sm font-medium border border-gray-300 bg-white hover:border-amber-500 transition">
                        {{ $category->name }}
                    </span>
                </label>
                @endforeach
            </div>

            {{-- Sort --}}
            <select name="sort" onchange="this.form.submit()"
                class="text-sm border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 cursor-pointer">
                <option value="" {{ !request('sort') ? 'selected' : '' }}>Sort: Default</option>
                <option value="price_asc"  {{ request('sort')=='price_asc'  ? 'selected' : '' }}>Price: Low → High</option>
                <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                <option value="name_asc"   {{ request('sort')=='name_asc'   ? 'selected' : '' }}>Name A–Z</option>
            </select>

        </form>
    </div>
</section>

{{-- ===== MENU GRID ===== --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" id="menu-grid">

    {{-- Result Count --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            {{ request('category', 'all') == 'all' ? 'All Items' : ucfirst(request('category')) }}
            @if(request('search'))
            <span class="text-amber-600"> — "{{ request('search') }}"</span>
            @endif
        </h2>
        <span class="text-sm text-gray-500">{{ $items->count() }} items</span>
    </div>

    @if($items->isEmpty())
    <div class="text-center py-24">
        <i class="fa-solid fa-face-sad-tear text-5xl text-gray-300 mb-4"></i>
        <p class="text-2xl text-gray-400">No items found</p>
        <a href="{{ route('customerOrder.menu.index') }}" class="mt-4 inline-block text-amber-600 hover:underline text-sm">Clear filters</a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($items as $item)
        <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">

            {{-- Image --}}
            <div class="relative">
                @if($item->image)
                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-utensils text-4xl text-gray-300"></i>
                    </div>
                @endif
                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-xs font-semibold px-2 py-1 rounded-full text-gray-700">
                    🍽 {{ $item->category->name }}
                </span>
            </div>

            {{-- Info --}}
            <div class="p-4">
                <h3 class="font-bold text-gray-900 text-lg leading-tight mb-1">{{ $item->name }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-3">{{ $item->description }}</p>

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
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium bg-amber-600 text-white hover:bg-amber-700 transition shadow">
                            <i class="fa-solid fa-plus text-xs"></i> Add
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>

@endsection

@push('scripts')
<script>
function incQty(btn) {
    const input = btn.parentElement.querySelector('.qty-input');
    input.value = Math.min(99, parseInt(input.value) + 1);
}
function decQty(btn) {
    const input = btn.parentElement.querySelector('.qty-input');
    input.value = Math.max(1, parseInt(input.value) - 1);
}
</script>
@endpush
