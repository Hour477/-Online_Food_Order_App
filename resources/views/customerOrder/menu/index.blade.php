@extends('customerOrder.layouts.app')

@section('title', __('app.menu_title') . ' — Saveur')

@section('content')

{{-- ===== ANIMATED HERO BANNER ===== --}}
<section class="relative min-h-[500px] md:min-h-[600px] overflow-hidden bg-gray-900">
    
    @if($banners->count() > 0)
    {{-- Dynamic Banner Slider --}}
    <div id="hero-carousel" class="absolute inset-0">
        @foreach($banners as $index => $banner)
        <div class="hero-slide absolute inset-0 transition-all duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-105' }}" 
             data-index="{{ $index }}">
            {{-- Background Image with Ken Burns Effect --}}
            <div class="absolute inset-0 animate-kenburns">
                <img src="{{ Storage::url($banner->image) }}" 
                     alt="{{ $banner->title ?? 'Banner' }}" 
                     class="w-full h-full object-cover">
            </div>
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-transparent to-transparent"></div>
        </div>
        @endforeach
    </div>
    
    {{-- Hero Content --}}
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full min-h-[500px] md:min-h-[600px] flex items-center">
        <div class="w-full py-16">
            <div class="max-w-2xl">
                {{-- Animated Badge --}}
                <div class="hero-content opacity-0 translate-y-4" data-delay="0">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 backdrop-blur-sm border border-amber-500/30 text-amber-400 text-sm font-medium mb-6 battambang-regular">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        {{ __('app.hero_badge') }}
                    </span>
                </div>
                
                {{-- Animated Title --}}
                <div class="hero-content opacity-0 translate-y-4" data-delay="200">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white leading-tight mb-4 battambang-bold">
                        {{ __('app.hero_title') }}
                        <span class="block mt-2">
                            <em class="text-amber-400 not-italic relative">
                                {{ __('app.hero_craving') }}
                                <svg class="absolute -bottom-2 left-0 w-full h-3 text-amber-500/30" viewBox="0 0 200 12" preserveAspectRatio="none">
                                    <path d="M0,8 Q50,0 100,8 T200,8" stroke="currentColor" stroke-width="4" fill="none" class="animate-draw"/>
                                </svg>
                            </em>
                            {{ __('app.hero_today') }}
                        </span>
                    </h1>
                </div>
                
                {{-- Animated Description --}}
                <div class="hero-content opacity-0 translate-y-4" data-delay="400">
                    <p class="text-gray-300 text-lg md:text-xl mb-8 max-w-lg leading-relaxed battambang-regular">
                        {{ __('app.hero_description') }}
                    </p>
                </div>
                
                {{-- Animated CTA Buttons --}}
                <div class="hero-content opacity-0 translate-y-4 flex flex-wrap gap-4" data-delay="600">
                    <a href="#menu-grid" 
                       class="group inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-base bg-amber-600 text-white hover:bg-amber-500 transition-all duration-300 shadow-lg shadow-amber-600/30 hover:shadow-amber-500/40 hover:scale-105 battambang-bold">
                        <i class="fa-solid fa-utensils group-hover:rotate-12 transition-transform"></i>
                        {{ __('app.browse_menu') }}
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#specials" 
                       class="group inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-base bg-white/10 backdrop-blur-sm text-white border border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105 battambang-regular">
                        <i class="fa-solid fa-star text-amber-400"></i>
                        {{ __('app.todays_specials') }}
                    </a>
                </div>
                
                {{-- Banner Title (Dynamic from Backend) --}}
                <div class="hero-content opacity-0 translate-y-4 mt-8" data-delay="800">
                    <div id="banner-titles" class="relative h-8 overflow-hidden">
                        @foreach($banners as $index => $banner)
                        @if($banner->title)
                        <p class="banner-title absolute inset-0 text-amber-400/80 text-lg font-medium transition-all duration-500 battambang-regular {{ $index === 0 ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4' }}" 
                           data-index="{{ $index }}">
                            <i class="fa-solid fa-bolt mr-2"></i>{{ $banner->title }}
                        </p>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            
            {{-- Navigation Arrows --}}
            @if($banners->count() > 1)
            <div class="absolute bottom-8 right-8 flex items-center gap-4">
                <button onclick="prevHeroSlide()" 
                        class="hero-nav-btn w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white hover:bg-white/20 transition-all duration-300 hover:scale-110 flex items-center justify-center">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="flex gap-2" id="hero-dots">
                    @foreach($banners as $index => $banner)
                    <button onclick="goToHeroSlide({{ $index }})" 
                            class="hero-dot w-3 h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-amber-400 w-8' : 'bg-white/40 hover:bg-white/60' }}"
                            data-dot="{{ $index }}"></button>
                    @endforeach
                </div>
                <button onclick="nextHeroSlide()" 
                        class="hero-nav-btn w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white hover:bg-white/20 transition-all duration-300 hover:scale-110 flex items-center justify-center">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            @endif
        </div>
    </div>
    
    @else
    {{-- Fallback Static Hero --}}
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/80 to-transparent"></div>
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1400&q=80" 
             alt="Hero Background" 
             class="w-full h-full object-cover opacity-60">
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full min-h-[500px] md:min-h-[600px] flex items-center">
        <div class="w-full py-16">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 backdrop-blur-sm border border-amber-500/30 text-amber-400 text-sm font-medium mb-6 battambang-regular">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    {{ __('app.hero_badge') }}
                </span>
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white leading-tight mb-4 battambang-bold">
                    {{ __('app.hero_title') }}
                    <span class="block mt-2">
                        <em class="text-amber-400 not-italic">{{ __('app.hero_craving') }}</em>{{ __('app.hero_today') }}
                    </span>
                </h1>
                <p class="text-gray-300 text-lg md:text-xl mb-8 max-w-lg leading-relaxed battambang-regular">
                    {{ __('app.hero_description') }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#menu-grid" 
                       class="group inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-base bg-amber-600 text-white hover:bg-amber-500 transition-all duration-300 shadow-lg hover:scale-105 battambang-bold">
                        <i class="fa-solid fa-utensils"></i>
                        {{ __('app.browse_menu') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    {{-- Decorative Elements --}}
    <div class="absolute bottom-0 left-0 right-0 h-24 z-10"></div>
    
    {{-- Floating Food Images --}}
    <div class="absolute right-8 top-1/2 -translate-y-1/2 hidden xl:block z-10">
        <div class="relative">
            <div class="floating-food absolute -top-20 -left-10 animate-float" style="animation-delay: 0s;">
                <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=150&q=80" 
                     alt="Burger" class="w-28 h-28 object-cover rounded-2xl shadow-2xl border-4 border-white/20">
            </div>
            <div class="floating-food animate-float" style="animation-delay: 0.5s;">
                <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=150&q=80" 
                     alt="Drink" class="w-28 h-28 object-cover rounded-2xl shadow-2xl border-4 border-white/20">
            </div>
            <div class="floating-food absolute -bottom-20 -left-10 animate-float" style="animation-delay: 1s;">
                <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=150&q=80" 
                     alt="Pizza" class="w-28 h-28 object-cover rounded-2xl shadow-2xl border-4 border-white/20">
            </div>
        </div>
    </div>
</section>

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

{{-- ===== MENU GRID ===== --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" id="menu-grid">

    {{-- Result Count --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900 battambang-bold">
            {{ request('category', 'all') == 'all' ? __('app.all_items') : ucfirst(request('category')) }}
            @if(request('search'))
            <span class="text-amber-600"> — "{{ request('search') }}"</span>
            @endif

        </h2>
        <span class="text-sm text-gray-500 battambang-regular">{{ $items->total() }} {{ __('app.items_count_suffix') }}</span>
    </div>

    {{-- Menu Items Grid --}}
    <div id="menu-items-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @include('customerOrder.menu.partials.items-grid')
    </div>

    {{-- Loading Indicator --}}
    <div id="loading-indicator" class="hidden py-12 text-center">
        <div class="inline-flex flex-col items-center gap-4">
            <div class="relative w-16 h-16">
                <div class="absolute inset-0 border-4 border-gray-200 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-amber-600 rounded-full border-t-transparent animate-spin"></div>
            </div>
            <p class="text-gray-600 font-medium battambang-regular">{{ __('app.loading_more') }}...</p>
        </div>
    </div>

    {{-- No More Items Message --}}
    <div id="no-more-items" class="hidden py-12 text-center">
        <i class="fa-solid fa-check-circle text-4xl text-green-500 mb-3"></i>
        <p class="text-gray-600 font-medium battambang-regular">{{ __('app.no_more_items') }}</p>
    </div>

    {{-- Pagination Links (Hidden, for URL reference) --}}
    @if($items->hasMorePages())
    <div id="pagination-next-url" class="hidden">{{ $items->nextPageUrl() }}</div>
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

// Hero Banner Slider with Animations
let currentHeroSlide = 0;
let heroAutoInterval;
const heroSlides = document.querySelectorAll('.hero-slide');
const totalHeroSlides = heroSlides.length;

// Animate hero content on page load
function animateHeroContent() {
    const contents = document.querySelectorAll('.hero-content');
    contents.forEach((el) => {
        const delay = el.dataset.delay || 0;
        setTimeout(() => {
            el.classList.remove('opacity-0', 'translate-y-4');
            el.classList.add('opacity-100', 'translate-y-0');
        }, parseInt(delay));
    });
}

// Update hero slide with fade effect
function updateHeroSlide() {
    heroSlides.forEach((slide, index) => {
        if (index === currentHeroSlide) {
            slide.classList.remove('opacity-0', 'scale-105');
            slide.classList.add('opacity-100', 'scale-100');
        } else {
            slide.classList.remove('opacity-100', 'scale-100');
            slide.classList.add('opacity-0', 'scale-105');
        }
    });
    
    // Update dots
    document.querySelectorAll('.hero-dot').forEach((dot, index) => {
        if (index === currentHeroSlide) {
            dot.classList.add('bg-amber-400', 'w-8');
            dot.classList.remove('bg-white/40', 'w-3');
        } else {
            dot.classList.remove('bg-amber-400', 'w-8');
            dot.classList.add('bg-white/40', 'w-3');
        }
    });
    
    // Update banner titles
    document.querySelectorAll('.banner-title').forEach((title, index) => {
        if (index === currentHeroSlide) {
            title.classList.remove('opacity-0', 'translate-y-4');
            title.classList.add('opacity-100', 'translate-y-0');
        } else {
            title.classList.remove('opacity-100', 'translate-y-0');
            title.classList.add('opacity-0', 'translate-y-4');
        }
    });
}

function nextHeroSlide() {
    currentHeroSlide = (currentHeroSlide + 1) % totalHeroSlides;
    updateHeroSlide();
    resetHeroAuto();
}

function prevHeroSlide() {
    currentHeroSlide = (currentHeroSlide - 1 + totalHeroSlides) % totalHeroSlides;
    updateHeroSlide();
    resetHeroAuto();
}

function goToHeroSlide(index) {
    currentHeroSlide = index;
    updateHeroSlide();
    resetHeroAuto();
}

function resetHeroAuto() {
    clearInterval(heroAutoInterval);
    heroAutoInterval = setInterval(nextHeroSlide, 6000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    animateHeroContent();
    
    @if($banners->count() > 1)
    heroAutoInterval = setInterval(nextHeroSlide, 6000);
    @endif
    
    // Initialize Infinite Scroll
    initInfiniteScroll();
});

// ===== INFINITE SCROLL FUNCTIONALITY =====
let isLoading = false;
let hasMorePages = document.getElementById('pagination-next-url') !== null;

function initInfiniteScroll() {
    window.addEventListener('scroll', handleScroll);
}

function handleScroll() {
    const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
    
    // Check if we're near the bottom (within 100px)
    if (scrollTop + clientHeight >= scrollHeight - 100 && !isLoading && hasMorePages) {
        loadMoreItems();
    }
}

async function loadMoreItems() {
    isLoading = true;
    const loadingIndicator = document.getElementById('loading-indicator');
    const nextUrlElement = document.getElementById('pagination-next-url');
    
    if (!nextUrlElement) {
        showNoMoreItems();
        return;
    }
    
    const nextUrl = nextUrlElement.textContent;
    
    // Show loading indicator
    loadingIndicator.classList.remove('hidden');
    
    // Add minimum 3 second loading delay for better UX
    await new Promise(resolve => setTimeout(resolve, 3000));
    
    try {
        const response = await fetch(nextUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html,application/xhtml+xml'
            }
        });
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        const html = await response.text();
        
        // Parse the response HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newItems = doc.querySelectorAll('.card-hover'); // Select all menu item cards
        
        if (newItems.length === 0) {
            showNoMoreItems();
            return;
        }
        
        // Append new items to the grid
        const container = document.getElementById('menu-items-container');
        newItems.forEach(item => {
            container.appendChild(item);
        });
        
        // Update pagination URL for next page
        const nextPageUrl = doc.querySelector('#pagination-next-url');
        if (nextPageUrl) {
            nextUrlElement.textContent = nextPageUrl.textContent;
        } else {
            hasMorePages = false;
            nextUrlElement.remove();
            showNoMoreItems();
        }
        
    } catch (error) {
        console.error('Error loading more items:', error);
    } finally {
        // Hide loading indicator
        loadingIndicator.classList.add('hidden');
        isLoading = false;
    }
}

function showNoMoreItems() {
    hasMorePages = false;
    const noMoreItems = document.getElementById('no-more-items');
    if (noMoreItems) {
        noMoreItems.classList.remove('hidden');
    }
    window.removeEventListener('scroll', handleScroll);
}
</script>

{{-- Custom CSS for Animations --}}
<style>
.animate-kenburns {
    animation: kenburns 20s ease-in-out infinite alternate;
}

@keyframes kenburns {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(1.1);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

.animate-draw {
    stroke-dasharray: 200;
    stroke-dashoffset: 200;
    animation: draw 2s ease forwards;
}

@keyframes draw {
    to {
        stroke-dashoffset: 0;
    }
}

.hero-content {
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.floating-food {
    transition: transform  0.3s ease;
}

.floating-food:hover {
    transform: scale(1.1) translateY(-5px);
}
</style>
@endpush
