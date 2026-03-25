@extends('customerOrder.layouts.app')

@section('title', __('app.menu_title') . ' — ' . config('app.name'))

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
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 backdrop-blur-sm border border-amber-500/30 text-amber-400 text-sm font-medium mb-6 ">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        {{ __('app.hero_badge') }}
                    </span>
                </div>
                
                {{-- Animated Title --}}
                <div class="hero-content opacity-0 translate-y-4" data-delay="200">
                    {{-- 'title_welcome' => 'សូមស្វាគមន៍', --}}
                    <span class="text-4xl sm:text-5xl md:text-6xl font-bold text-white leading-tight mb-4 ">{{ __('app.title_welcome') }}</span>
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white leading-tight mb-4 ">
                        
                    

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
                    <p class="text-gray-300 text-lg md:text-xl mb-8 max-w-lg leading-relaxed ">
                        {{ __('app.hero_description') }}
                    </p>
                </div>
                
                {{-- Animated CTA Buttons --}}
                <div class="hero-content opacity-0 translate-y-4 flex flex-wrap gap-4" data-delay="600">
                    <a href="#menu-grid" 
                       class="group inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-base bg-amber-600 text-white hover:bg-amber-500 transition-all duration-300 shadow-lg shadow-amber-600/30 hover:shadow-amber-500/40 hover:scale-105 ">
                        <i class="fa-solid fa-utensils group-hover:rotate-12 transition-transform"></i>
                        {{ __('app.browse_menu') }}
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#specials" 
                       class="group inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-base bg-white/10 backdrop-blur-sm text-white border border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105 ">
                        <i class="fa-solid fa-star text-amber-400"></i>
                        {{ __('app.todays_specials') }}
                    </a>
                </div>
                
                {{-- Banner Title (Dynamic from Backend) --}}
                <div class="hero-content opacity-0 translate-y-4 mt-8" data-delay="800">
                    <div id="banner-titles" class="relative h-8 overflow-hidden">
                        @foreach($banners as $index => $banner)
                        @if($banner->title)
                        <p class="banner-title absolute inset-0 text-amber-400/80 text-lg font-medium transition-all duration-500  {{ $index === 0 ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4' }}" 
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
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/20 backdrop-blur-sm border border-amber-500/30 text-amber-400 text-sm font-medium mb-6 ">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    {{ __('app.hero_badge') }}
                </span>
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white leading-tight mb-4 ">
                    {{ __('app.hero_title') }}
                    <span class="block mt-2">
                        <em class="text-amber-400 not-italic">{{ __('app.hero_craving') }}</em>{{ __('app.hero_today') }}
                    </span>
                </h1>
                <p class="text-gray-300 text-lg md:text-xl mb-8 max-w-lg leading-relaxed ">
                    {{ __('app.hero_description') }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#menu-grid" 
                       class="group inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-base bg-amber-600 text-white hover:bg-amber-500 transition-all duration-300 shadow-lg hover:scale-105 ">
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
    
</section>

{{-- ===== SEARCH & FILTER BAR ===== --}}
<div class="max-w-7xl mx-auto flex flex-col lg:flex-row px-4 sm:px-6 lg:px-8">
    <aside class="w-full lg:w-64 flex-shrink-0 lg:sticky lg:top-[72px] lg:h-[calc(100vh-72px)] overflow-y-auto hide-scrollbar border-b lg:border-b-0 lg:border-r border-gray-100 p-6">
        <div class="flex items-center justify-between lg:block mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Filters</h2>
            <button id="mobile-filter-toggle" class="lg:hidden flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 font-medium">
                <i class="fa-solid fa-filter"></i>
                <span>Options</span>
            </button>
        </div>

        <div id="filter-content" class="hidden lg:block">
            <!-- Sort by -->
            <div class="mb-6 border-b border-gray-200 pb-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Sort by</h3>
                <div class="space-y-3" id="sort-container">
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" name="sort" value="relevance" class="w-5 h-5 text-amber-600 bg-gray-100 border-gray-300 focus:ring-amber-600 accent-amber-600" {{ request('sort', 'relevance') == 'relevance' ? 'checked' : '' }}>
                        <span class="ml-3 text-sm text-gray-700 group-hover:text-amber-600">Relevance</span>
                    </label>
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" name="sort" value="price_asc" class="w-5 h-5 text-amber-600 bg-gray-100 border-gray-300 focus:ring-amber-600 accent-amber-600" {{ request('sort') == 'price_asc' ? 'checked' : '' }}>
                        <span class="ml-3 text-sm text-gray-700 group-hover:text-amber-600">Price: Low to High</span>
                    </label>
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" name="sort" value="price_desc" class="w-5 h-5 text-amber-600 bg-gray-100 border-gray-300 focus:ring-amber-600 accent-amber-600" {{ request('sort') == 'price_desc' ? 'checked' : '' }}>
                        <span class="ml-3 text-sm text-gray-700 group-hover:text-amber-600">Price: High to Low</span>
                    </label>
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" name="sort" value="rating" class="w-5 h-5 text-amber-600 bg-gray-100 border-gray-300 focus:ring-amber-600 accent-amber-600" {{ request('sort') == 'rating' ? 'checked' : '' }}>
                        <span class="ml-3 text-sm text-gray-700 group-hover:text-amber-600">Top rated</span>
                    </label>
                </div>
            </div>

            <!-- Quick filters & Offers -->
            <div class="mb-6 border-b border-gray-200 pb-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Quick filters</h3>

                <label class="flex items-center cursor-pointer group mb-3">
                    <input type="checkbox" name="popular_like" value="1" class="quick-filter w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-600 accent-amber-600">
                    <span class="ml-3 text-sm text-gray-700 group-hover:text-amber-600">Popular Like</span> 
                </label>

                <label class="flex items-center cursor-pointer group mb-3">
                    <input type="checkbox" name="top_rated" value="1" class="quick-filter w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-600 accent-amber-600">
                    <span class="ml-3 text-sm text-gray-700 group-hover:text-amber-600">Top Rated</span> 
                </label>

                <label class="flex items-center cursor-pointer group mb-3">
                    <input type="checkbox" name="top_dishes" value="1" class="quick-filter w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-600 accent-amber-600">
                    <span class="ml-3 text-sm text-gray-700 group-hover:text-amber-600">Top Dishes</span> 
                </label>
            </div>

            <!-- Price Range Filter -->
            <div class="mb-6 border-b border-gray-200 pb-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Price Range</h3>
                <div class="flex items-center gap-2">
                    <input type="number" name="min_price" placeholder="Min" class="price-filter w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-amber-600 focus:border-amber-600">
                    <span class="text-gray-400">-</span>
                    <input type="number" name="max_price" placeholder="Max" class="price-filter w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-amber-600 focus:border-amber-600">
                </div>
            </div>

            <!-- Cuisines Filter -->
            <div class="mb-6 border-b border-gray-200 pb-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4 flex justify-between items-center">
                    Cuisines
                    <span id="cuisine-count-badge" class="bg-amber-100 text-amber-600 text-xs px-2 py-0.5 rounded-full hidden">0</span>
                </h3>
                
                <!-- Inner Search for Cuisines -->
                <div class="relative mb-4">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="cuisine-search" class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:bg-white text-sm focus:outline-none focus:ring-1 focus:ring-amber-600 focus:border-amber-600" placeholder="Search for cuisine">
                </div>
                
                <div class="space-y-3" id="sidebar-cuisine-list">
                    <!-- Checkboxes injected by JS -->
                </div>

                <button id="toggle-cuisines-btn" class="mt-4 flex items-center text-sm font-bold text-gray-600 hover:text-amber-600 transition-colors w-full py-1">
                    <span>Show more</span>
                    <svg class="ml-1 h-4 w-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
        </div>
    </aside>

    {{-- ===== MENU GRID ===== --}}
    <section class="flex-1 py-10 lg:pl-10" id="menu-grid">
        {{-- Search and Result Count --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 ">
                    {{ request('category', 'all') == 'all' ? __('app.all_items') : ucfirst(request('category')) }}
                </h2>
                <span class="text-sm text-gray-500 " id="total-items-count">{{ $items->total() }} {{ __('app.items_count_suffix') }}</span>
            </div>

            <div class="relative w-full md:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                </div>
                <input type="text" id="global-search" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-white shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all text-sm" 
                    placeholder="{{ __('app.search_dishes') }}">
            </div>
        </div>

        {{-- Menu Items Grid --}}
    <div id="menu-items-container" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
        @include('customerOrder.menu.partials.items-grid')
    </div>

    {{-- No More Items Message --}}
        <div id="no-more-items" class="hidden py-12 text-center">
            <i class="fa-solid fa-check-circle text-4xl text-green-500 mb-3"></i>
            <p class="text-gray-600 font-medium ">{{ __('app.no_more_items') }}</p>
        </div>

    </section>
</div>

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

/**
 * Add to Cart via AJAX
 */
async function addToCart(event, form) {
    event.preventDefault();
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalContent = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i>';

    try {
        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Update cart badge
            const badge = document.getElementById('cart-badge');
            if (badge) {
                badge.textContent = data.cart_count;
                badge.classList.remove('hidden');
            }

            // Show success toast (using existing flash message logic or custom)
            showToast(data.message, 'success');
            
            // Reset quantity to 1
            form.querySelector('.qty-input').value = 1;
        } else {
            throw new Error(data.message || 'Failed to add item to cart');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showToast(error.message, 'error');
    } finally {
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalContent;
    }
}

/**
 * Custom Toast Notification
 */
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
    const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
    
    toast.className = `fixed top-20 right-4 z-[60] ${bgColor} text-white px-5 py-3 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in`;
    toast.innerHTML = `<i class="fa-solid ${icon}"></i> ${message}`;
    
    document.body.appendChild(toast);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.classList.replace('animate-fade-in', 'opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

/**
 * Toggle Product Like/Unlike
 */
async function toggleLike(btn, productId) {
    @guest
        window.location.href = "{{ route('login') }}";
        return;
    @endguest

    const icon = btn.querySelector('i');
    const likesCountSpan = btn.querySelector('.likes-count');
    const isLiked = icon.classList.contains('fa-solid');
    
    // Optimistic UI update
    icon.classList.toggle('fa-solid');
    icon.classList.toggle('fa-regular');
    btn.classList.toggle('text-red-500');
    btn.classList.toggle('text-gray-400');

    try {
        const url = isLiked 
            ? `/api/products/${productId}/unlike` 
            : `/api/products/${productId}/like`;
        
        const response = await fetch(url, {
            method: isLiked ? 'DELETE' : 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok) {
            likesCountSpan.textContent = data.likes_count;
        } else {
            // Rollback on error
            throw new Error(data.message || 'Something went wrong');
        }
    } catch (error) {
        console.error('Error toggling like:', error);
        // Rollback UI
        icon.classList.toggle('fa-solid');
        icon.classList.toggle('fa-regular');
        btn.classList.toggle('text-red-500');
        btn.classList.toggle('text-gray-400');
        
        alert(error.message || 'Failed to update like status');
    }
}

// Hero Banner Slider with Animations
let currentHeroSlide = 0;
let heroAutoInterval;
let isLoading = false;
let hasMorePages = {{ $items->hasMorePages() ? 'true' : 'false' }};
let nextPageUrl = '{{ $items->nextPageUrl() }}';
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
    
    // Initialize Sidebar Filters
    initSidebarFilters();
    
    // Initialize Infinite Scroll
    initInfiniteScroll();
});

// ===== SIDEBAR FILTERS FUNCTIONALITY =====
function initSidebarFilters() {
    const cuisineList = document.getElementById('sidebar-cuisine-list');
    const cuisineSearch = document.getElementById('cuisine-search');
    const toggleBtn = document.getElementById('toggle-cuisines-btn');
    const badge = document.getElementById('cuisine-count-badge');

    // 1. Populate Cuisines
    const categories = @json($categories);
    let isExpanded = false;

    function renderCuisines(filter = '') {
        const filtered = categories.filter(c => c.name.toLowerCase().includes(filter.toLowerCase()));
        cuisineList.innerHTML = filtered.map(c => `
            <label class="flex items-center cursor-pointer group cuisine-item">
                <input type="checkbox" name="cuisines[]" value="${c.id}" class="cuisine-checkbox w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-600 accent-amber-600">
                <span class="ml-3 text-sm text-gray-700 group-hover:text-amber-600 transition-colors">${c.name}</span>
            </label>
        `).join('');

        updateCuisineVisibility();
    }

    function updateCuisineVisibility() {
        const items = cuisineList.querySelectorAll('.cuisine-item');
        items.forEach((item, index) => {
            if (!isExpanded && index >= 5) {
                item.classList.add('hidden');
            } else {
                item.classList.remove('hidden');
            }
        });
        
        if (items.length <= 5) {
            toggleBtn.classList.add('hidden');
        } else {
            toggleBtn.classList.remove('hidden');
            toggleBtn.querySelector('span').textContent = isExpanded ? 'Show less' : 'Show more';
            toggleBtn.querySelector('svg').style.transform = isExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    }

    renderCuisines();

    // 2. Event Listeners
    cuisineSearch.addEventListener('input', (e) => renderCuisines(e.target.value));
    
    toggleBtn.addEventListener('click', () => {
        isExpanded = !isExpanded;
        updateCuisineVisibility();
    });

    const triggerUpdate = () => {
        const selectedCuisines = Array.from(document.querySelectorAll('.cuisine-checkbox:checked')).map(cb => cb.value);
        badge.textContent = selectedCuisines.length;
        badge.classList.toggle('hidden', selectedCuisines.length === 0);
        
        applyFilters();
    };

    // Debounce search and price input
    let searchTimeout;
    document.getElementById('global-search').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(triggerUpdate, 500);
    });

    let priceTimeout;
    document.querySelectorAll('.price-filter').forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(triggerUpdate, 500);
        });
    });

    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('cuisine-checkbox') || 
            e.target.classList.contains('quick-filter') || 
            e.target.name === 'sort') {
            triggerUpdate();
        }
    });

    // Mobile Toggle
    const mobileToggle = document.getElementById('mobile-filter-toggle');
    const filterContent = document.getElementById('filter-content');
    mobileToggle?.addEventListener('click', () => {
        filterContent.classList.toggle('hidden');
    });
}

async function applyFilters(page = 1) {
    const params = new URLSearchParams();
    
    // Sort
    const sort = document.querySelector('input[name="sort"]:checked')?.value;
    if (sort) params.append('sort', sort);

    // Quick Filters
    document.querySelectorAll('.quick-filter:checked').forEach(cb => {
        params.append(cb.name, '1');
    });

    // Price
    const minPrice = document.querySelector('input[name="min_price"]').value;
    const maxPrice = document.querySelector('input[name="max_price"]').value;
    if (minPrice) params.append('min_price', minPrice);
    if (maxPrice) params.append('max_price', maxPrice);

    // Cuisines
    const cuisines = Array.from(document.querySelectorAll('.cuisine-checkbox:checked')).map(cb => cb.value);
    if (cuisines.length) params.append('cuisines', cuisines.join(','));

    // Search
    const search = document.getElementById('global-search').value;
    if (search) params.append('search', search);

    // Category from URL (persist if present)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('category')) params.append('category', urlParams.get('category'));
    
    if (page > 1) params.append('page', page);

    const baseUrl = window.location.pathname;
    const fullUrl = `${baseUrl}?${params.toString()}`;
    window.history.pushState({}, '', fullUrl);

    const container = document.getElementById('menu-items-container');
    const noMoreItems = document.getElementById('no-more-items');
    
    if (page === 1) {
        container.innerHTML = '<div class="col-span-full py-20 text-center"><i class="fas fa-spinner fa-spin text-3xl text-amber-600"></i></div>';
    }

    isLoading = true;

    try {
        const response = await fetch(fullUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error('Failed to fetch filtered menu');

        const data = await response.json();
        
        if (page === 1) {
            container.innerHTML = data.html || '<div class="col-span-full py-20 text-center">No items found</div>';
        } else {
            container.insertAdjacentHTML('beforeend', data.html);
        }

        // Update total count display
        const countDisplay = document.getElementById('total-items-count');
        if (countDisplay) {
            countDisplay.textContent = `${data.pagination.total} items found`;
        }

        // Update category title if needed
        const categoryTitle = document.querySelector('#menu-grid h2');
        if (categoryTitle) {
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('category') || 'all';
            categoryTitle.textContent = category.charAt(0).toUpperCase() + category.slice(1);
        }

        // Update infinite scroll state
        if (data.pagination.has_more) {
            nextPageUrl = data.pagination.next_page_url;
            noMoreItems.classList.add('hidden');
            hasMorePages = true;
            window.addEventListener('scroll', handleScroll);
        } else {
            nextPageUrl = '';
            noMoreItems.classList.toggle('hidden', data.pagination.total === 0);
            hasMorePages = false;
            window.removeEventListener('scroll', handleScroll);
        }

    } catch (error) {
        console.error('Error:', error);
        container.innerHTML = '<div class="col-span-full py-20 text-center text-red-500">Error loading items</div>';
    } finally {
        isLoading = false;
    }
}

// ===== INFINITE SCROLL FUNCTIONALITY =====
function initInfiniteScroll() {
    window.addEventListener('scroll', handleScroll);
}

function handleScroll() {
    if (isLoading || !hasMorePages || !nextPageUrl) return;
    
    const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
    if (scrollTop + clientHeight >= scrollHeight - 600) {
        const url = new URL(nextPageUrl);
        const page = url.searchParams.get('page');
        if (page) applyFilters(parseInt(page));
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
    transition: transform  0.5s ease;
}

.floating-food:hover {
    transform: scale(1.1) translateY(-5px);
}
</style>
@endpush
