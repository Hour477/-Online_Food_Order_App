@extends('customerOrder.layouts.app')

@section('title', 'Menu — Saveur')

@section('content')

{{-- ===== HERO BANNER ===== --}}
<section class="relative overflow-hidden bg-ink text-cream">
    <div class="absolute inset-0 opacity-10" style="background-image:url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1400&q=80'); background-size:cover; background-position:center;"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex flex-col md:flex-row items-center gap-8">
        <div class="flex-1">
            <p class="text-brand-300 text-sm font-medium tracking-widest uppercase mb-2">Fresh • Local • Delicious</p>
            <h1 class="font-display text-5xl md:text-6xl font-bold leading-tight mb-4">
                What are you<br><em class="text-brand-300 not-italic">craving</em> today?
            </h1>
            <p class="text-cream/60 text-lg mb-6 max-w-md">Explore our handcrafted menu — from hearty meals to refreshing drinks and indulgent desserts.</p>
            <a href="#menu-grid" class="btn-primary inline-flex items-center gap-2 px-6 py-3 rounded-full font-medium text-sm shadow-lg">
                <i class="fa-solid fa-arrow-down"></i> Browse Menu
            </a>
        </div>
        <div class="flex-shrink-0 hidden md:grid grid-cols-2 gap-3">
            @foreach(['https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=300&q=80',
                       'https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=300&q=80',
                       'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=300&q=80',
                       'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=300&q=80'] as $i => $img)
            <img src="{{ $img }}" alt="food" class="w-36 h-36 object-cover rounded-2xl shadow-lg {{ $i % 2 == 1 ? 'mt-4' : '' }}">
            @endforeach
        </div>
    </div>
</section>

{{-- ===== SEARCH & FILTER BAR ===== --}}
<section class="sticky top-16 z-40 bg-cream/95 backdrop-blur-md border-b border-brand-100 shadow-sm" id="filter-bar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <form method="GET" action="{{ route('customerOrder.menu.index') }}" id="filter-form" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

            {{-- Search --}}
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-brand-300 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search dishes, drinks…"
                    class="w-full pl-9 pr-4 py-2.5 text-sm rounded-full border border-brand-200 bg-white focus:outline-none focus:ring-2 focus:ring-brand-300 transition">
            </div>

            {{-- Category Tabs --}}
            <div class="flex gap-2 flex-wrap">
                <label class="cursor-pointer">
                    <input type="radio" name="category" value="all" class="hidden peer"
                        {{ request('category', 'all') == 'all' ? 'checked' : '' }}
                        onchange="this.form.submit()">
                    <span class="peer-checked:bg-brand-400 peer-checked:text-white peer-checked:border-brand-400
                                 inline-block px-4 py-2 rounded-full text-sm font-medium border border-brand-200 bg-white hover:border-brand-400 transition">
                        All
                    </span>
                </label>
                @foreach($categories as $category)
                <label class="cursor-pointer">
                    <input type="radio" name="category" value="{{ $category->name }}" class="hidden peer"
                        {{ request('category') == $category->name ? 'checked' : '' }}
                        onchange="this.form.submit()">
                    <span class="peer-checked:bg-brand-400 peer-checked:text-white peer-checked:border-brand-400
                                 inline-block px-4 py-2 rounded-full text-sm font-medium border border-brand-200 bg-white hover:border-brand-400 transition">
                        {{ $category->name }}
                    </span>
                </label>
                @endforeach
            </div>

            {{-- Sort --}}
            <select name="sort" onchange="this.form.submit()"
                class="text-sm border border-brand-200 rounded-full px-4 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-brand-300 cursor-pointer">
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
        <h2 class="font-display text-2xl font-bold text-ink">
            {{ request('category', 'all') == 'all' ? 'All Items' : ucfirst(request('category')) }}
            @if(request('search'))
            <span class="text-brand-400"> — "{{ request('search') }}"</span>
            @endif
        </h2>
        <span class="text-sm text-ink/50">{{ $items->count() }} items</span>
    </div>

    @if($items->isEmpty())
    <div class="text-center py-24">
        <i class="fa-solid fa-face-sad-tear text-5xl text-brand-200 mb-4"></i>
        <p class="font-display text-2xl text-ink/40">No items found</p>
        <a href="{{ route('customerOrder.menu.index') }}" class="mt-4 inline-block text-brand-400 hover:underline text-sm">Clear filters</a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($items as $item)
        <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-brand-50 slide-in" style="animation-delay:{{ $loop->index * 40 }}ms">

            {{-- Image --}}
            <div class="relative">
                @if($item->image)
                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <i class="fas fa-utensils text-4xl text-gray-300 dark:text-gray-600"></i>
                    </div>
                @endif
                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-xs font-semibold px-2 py-1 rounded-full text-brand-600">
                    🍽 {{ $item->category->name }}
                </span>
            </div>

            {{-- Info --}}
            <div class="p-4">
                <h3 class="font-display font-bold text-ink text-lg leading-tight mb-1">{{ $item->name }}</h3>
                <p class="text-ink/50 text-sm leading-relaxed line-clamp-2 mb-3">{{ $item->description }}</p>

                <div class="flex items-center justify-between mt-auto">
                    <span class="font-bold text-brand-500 text-lg">${{ number_format($item->price, 2) }}</span>

                    <form method="POST" action="{{ route('customerOrder.cart.add') }}" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <div class="flex items-center border border-brand-200 rounded-full overflow-hidden">
                            <button type="button" onclick="decQty(this)" class="px-2 py-1 text-ink/60 hover:bg-brand-50 transition text-sm">−</button>
                            <input type="number" name="quantity" value="1" min="1" max="99"
                                class="w-8 text-center text-sm font-medium bg-transparent focus:outline-none qty-input">
                            <button type="button" onclick="incQty(this)" class="px-2 py-1 text-ink/60 hover:bg-brand-50 transition text-sm">+</button>
                        </div>
                        <button type="submit" class="btn-primary flex items-center gap-1.5 px-3 py-2 rounded-full text-sm font-medium shadow">
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
