{{-- ===== SEARCH & FILTER BAR ===== --}}
<section class="sticky top-16 z-40 bg-gradient-to-r from-white via-white to-amber-50/95 backdrop-blur-md border-b border-gray-200 shadow-lg" id="filter-bar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <form method="GET" action="{{ route('customerOrder.menu.index') }}" id="filter-form" class="space-y-4">
            
            {{-- Top Row: Search + Sort --}}
            <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                {{-- Search with Icon --}}
                <div class="relative flex-1 max-w-lg group">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-amber-600 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('app.search_placeholder') }}"
                        class="w-full pl-11 pr-4 py-3 text-sm rounded-xl border-2 border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-300 battambang-regular shadow-sm hover:shadow-md">
                </div>

                {{-- Sort Dropdown --}}
                <select name="sort" onchange="this.form.submit()"
                    class="text-sm border-2 border-gray-200 bg-gray-50 rounded-xl px-5 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 cursor-pointer battambang-regular shadow-sm hover:shadow-md transition-all duration-300 min-w-[180px]">
                    <option value="" {{ !request('sort') ? 'selected' : '' }}>🔤 {{ __('app.sort_default') }}</option>
                    <option value="price_asc"  {{ request('sort')=='price_asc'  ? 'selected' : '' }}>💰 {{ __('app.sort_price_low') }}</option>
                    <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected' : '' }}>💎 {{ __('app.sort_price_high') }}</option>
                    <option value="name_asc"   {{ request('sort')=='name_asc'   ? 'selected' : '' }}>📝 {{ __('app.sort_name_az') }}</option>
                </select>
            </div>

            {{-- Category Horizontal List --}}
            <div class="border-t border-gray-200 pt-4">
                <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
                    
                    {{-- All Categories Button --}}
                    <label class="cursor-pointer flex-shrink-0 transform transition-all duration-300 hover:scale-105">
                        <input type="radio" name="category" value="all" class="hidden peer"
                            {{ request('category', 'all') == 'all' ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        <span class="peer-checked:bg-gradient-to-r peer-checked:from-amber-500 peer-checked:to-amber-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-amber-500/30
                                     inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-sm font-semibold border-2 border-amber-500 bg-white text-amber-600 hover:bg-amber-50 transition-all duration-300 battambang-regular whitespace-nowrap">
                            <i class="fa-solid fa-layer-group"></i>{{ __('app.all') }}
                        </span>
                    </label>

                    {{-- Dynamic Category Buttons --}}
                    @foreach($categories as $category)
                    <label class="cursor-pointer flex-shrink-0 transform transition-all duration-300 hover:scale-105">
                        <input type="radio" name="category" value="{{ $category->name }}" class="hidden peer"
                            {{ request('category') == $category->name ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        <span class="peer-checked:bg-gradient-to-r peer-checked:from-amber-500 peer-checked:to-amber-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-amber-500/30
                                     inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-sm font-semibold border-2 border-gray-300 bg-white text-gray-700 hover:border-amber-400 hover:bg-amber-50 transition-all duration-300 battambang-regular whitespace-nowrap">
                            {{ $category->name }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

        </form>
    </div>
</section>