@php
    $sidebarSettings = \App\Models\Setting::query()
        ->whereIn('key', ['logo', 'resturant_name'])
        ->pluck('value', 'key');
    $sidebarLogo = $sidebarSettings['logo'] ?? null;
    $sidebarName = $sidebarSettings['resturant_name'] ?? config('app.name');
  
@endphp


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $sidebarSettings['description'] ?? 'Order System' }}">
    <title>{{ $sidebarName ?? 'Order System' }}</title>
    
    {{-- Google Fonts - Kantumruy Pro and Battambang --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&family=Battambang:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
    <link rel="icon" href="{{ asset('storage/settings/' . $sidebarLogo) }}" alt="{{ $sidebarName }}" loading="lazy">
    
    {{-- Custom Styles matching system default --}}
    <style>
        /* Khmer Font - Kantumruy Pro & Battambang */
        body {
            font-family: "Kantumruy Pro", "Battambang", "Momo Trust Sans", system-ui;
            background-color: #fdf6ec;
            color: #1a1208;
        }
        
        .battambang-thin {
            font-family: "Battambang", system-ui;
            font-weight: 100;
        }
        
        .battambang-light {
            font-family: "Battambang", system-ui;
            font-weight: 300;
        }
        
        .battambang-regular {
            font-family: "Battambang", system-ui;
            font-weight: 400;
        }
        
        .battambang-bold {
            font-family: "Battambang", system-ui;
            font-weight: 700;
        }
        
        .battambang-black {
            font-family: "Battambang", system-ui;
            font-weight: 900;
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-thumb {
            background: #ffc980;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-track {
            background: #fff;
        }
        
        /* Animation utilities */
        .animate-fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Card hover effect */
        .card-hover {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(26, 18, 8, 0.12);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#fdf6ec] font-sans">

{{-- ===== NAVBAR ===== --}}
<nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm" aria-label="Main Navigation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('customerOrder.menu.index') }}" class="flex items-center gap-2 touch-target" aria-label="{{ $sidebarName }} Home">
                @if(!empty($sidebarLogo))
                <img src="{{ asset('storage/settings/' . $sidebarLogo) }}" alt="{{ $sidebarName }}"
                    class="h-10 w-10 rounded-lg object-contain bg-gray-100 p-1 flex-shrink-0">
            @else
                <div class="h-10 w-10 rounded-lg bg-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="4.5"></circle>
                        <path d="M5 4v7m0 0a2 2 0 002 2M5 11a2 2 0 01-2-2V4"></path>
                        <path d="M18 4v7a2 2 0 01-2 2"></path>
                    </svg>
                </div>
            @endif
            <p class="text-2xl font-bold text-gray-900 hidden sm:block">{{ $sidebarName }}</p>

                
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('customerOrder.menu.index') }}" class="text-sm font-medium touch-target px-2 {{ request()->routeIs('menu.*') ? 'text-amber-600' : 'text-gray-600 hover:text-gray-900' }}">{{ __('app.menu') }}</a>
                <a href="{{ route('customerOrder.orders.history') }}" class="text-sm font-medium touch-target px-2 {{ request()->routeIs('orders.*') ? 'text-amber-600' : 'text-gray-600 hover:text-gray-900' }}">{{ __('app.my_orders') }}</a>
            </div>

            {{-- Cart + Language + Mobile --}}
            <div class="flex items-center gap-3">
                @php $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'qty')) : 0; @endphp
                {{-- Language Switcher --}}
                <div class="relative language-menu" id="customer-language-menu">
                    <button type="button" class="language-menu-toggle flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 transition text-sm font-medium text-gray-700 touch-target" aria-expanded="false" aria-haspopup="true">
                        <i class="fa-solid fa-globe text-amber-600"></i>
                        <span class="hidden xs:inline">{{ config('app.available_locales')[app()->getLocale()] ?? 'English' }}</span>
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>
                    
                    <div class="language-menu-panel hidden absolute right-0 mt-2 w-36 bg-white rounded-lg shadow-lg border border-gray-200 z-[100] py-1">
                        @foreach(config('app.available_locales', ['en' => 'English', 'km' => 'Khmer']) as $locale => $label)
                        <a href="{{ route('language.switch', $locale) }}" 
                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 {{ app()->getLocale() === $locale ? 'bg-amber-50 text-amber-700 font-medium' : '' }}">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('customerOrder.cart.index') }}" class="relative p-2 rounded-full hover:bg-gray-100 transition touch-target" title="{{ __('app.cart') }}" aria-label="{{ __('app.cart') }} ({{ $cartCount }} items)">
                    <i class="fa-solid fa-basket-shopping text-xl text-gray-700"></i>
                    @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-amber-500 text-white text-xs flex items-center justify-center font-bold">{{ $cartCount }}</span>
                    @endif
                </a>
                {{-- <button class="md:hidden p-2 rounded-full hover:bg-gray-100 transition touch-target" id="mobile-menu-btn" aria-label="Toggle Menu" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="material-symbols-outlined">
                        menu
                        </span>
                </button> --}}

                {{-- Login + Signup--}}
                @auth
                <div class="flex items-center gap-4 ml-2 border-l border-gray-200 pl-4">
                    <div class="hidden lg:block text-right">
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold leading-none">{{ __('app.welcome') }}</p>
                        <p class="text-sm font-semibold text-gray-900 leading-tight">{{ Auth::user()->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition touch-target" title="{{ __('app.logout') }}" aria-label="{{ __('app.logout') }}">
                            <span class="material-symbols-outlined">
                                logout
                                </span>
                        </button>
                    </form>
                </div>
                @else
                <div class="flex items-center gap-2 ml-2">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-amber-600 px-3 py-2 transition touch-target">{{ __('app.login') }}</a>
                    <a href="{{ route('register') }}" class="text-xs font-bold px-4 py-2 rounded-lg shadow-sm bg-amber-600 text-white hover:bg-amber-700 transition touch-target">{{ __('app.register') }}</a>
                </div>
                @endauth
                
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white px-4 py-3 space-y-2">
        <a href="{{ route('customerOrder.menu.index') }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-amber-600">{{ __('app.menu') }}</a>
        <a href="{{ route('customerOrder.orders.history') }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-amber-600">{{ __('app.my_orders') }}</a>
        {{-- Mobile Language Switcher --}}
        <div class="border-t border-gray-100 pt-2 mt-2">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">{{ __('app.language') }}</p>
            <div class="flex gap-2">
                @foreach(config('app.available_locales', ['en' => 'English', 'km' => 'Khmer']) as $locale => $label)
                <a href="{{ route('language.switch', $locale) }}" 
                   class="px-3 py-1.5 rounded-lg text-sm {{ app()->getLocale() === $locale ? 'bg-amber-100 text-amber-700 font-medium' : 'bg-gray-100 text-gray-600' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</nav>

{{-- ===== FLASH MESSAGES ===== --}}
@if(session('success'))
<div class="fixed top-20 right-4 z-50 bg-emerald-500 text-white px-5 py-3 rounded-lg shadow-lg flex items-center gap-2" id="flash-msg">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="fixed top-20 right-4 z-50 bg-red-500 text-white px-5 py-3 rounded-lg shadow-lg flex items-center gap-2" id="flash-msg">
    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
@endif

{{-- ===== MAIN CONTENT ===== --}}
<main class="flex-1">
    @yield('content')
</main>

{{-- ===== FOOTER ===== --}}
<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    @if(!empty($sidebarLogo))
                    <img src="{{ asset('storage/settings/' . $sidebarLogo) }}" alt="{{ $sidebarName }}"
                         class="h-8 w-8 rounded-lg object-contain bg-gray-800 p-1">
                    @else
                    <span class="w-8 h-8 rounded-lg bg-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-bowl-food text-white text-sm"></i>
                    </span>
                    @endif
                    <span class="text-xl font-bold text-white">{{ $sidebarName ?? __('app.site_name') }}</span>
                </div>
                <p class="text-sm leading-relaxed text-gray-400">{{ __('app.footer_description') }}</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 battambang-bold">{{ __('app.quick_links') }}</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('customerOrder.menu.index') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fa-solid fa-utensils text-xs"></i> {{ __('app.menu') }}</a></li>
                    <li><a href="{{ route('customerOrder.cart.index') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fa-solid fa-cart-shopping text-xs"></i> {{ __('app.cart') }}</a></li>
                    <li><a href="{{ route('customerOrder.orders.history') }}" class="hover:text-amber-400 transition flex items-center gap-2"><i class="fa-solid fa-clock-rotate-left text-xs"></i> {{ __('app.order_history') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 battambang-bold">{{ __('app.hours') }}</h4>
                <div class="space-y-2 text-sm text-gray-400">
                    <p class="flex items-center gap-2"><i class="fa-regular fa-clock text-amber-500"></i> {{ __('app.weekdays') }}</p>
                    <p class="flex items-center gap-2"><i class="fa-regular fa-clock text-amber-500"></i> {{ __('app.weekends') }}</p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-6 text-center text-xs text-gray-500">
            © {{ date('Y') }} {{ $sidebarName ?? __('app.site_name') }}. {{ __('app.all_rights_reserved') }}
        </div>
    </div>
</footer>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
    
    // Language menu dropdown - using event delegation for reliability
    document.addEventListener('click', function(e) {
        // Handle toggle click (button only)
        if (e.target.closest('.language-menu-toggle')) {
            e.preventDefault();
            e.stopPropagation();
            const menu = e.target.closest('.language-menu');
            const panel = menu?.querySelector('.language-menu-panel');
            if (panel) {
                panel.classList.toggle('hidden');
            }
            return;
        }
        
        // Allow language switch links to work normally
        if (e.target.closest('.language-menu-panel a')) {
            return; // Let the link navigate normally
        }
        
        // Close all language menus when clicking outside
        document.querySelectorAll('.language-menu-panel').forEach(panel => {
            if (!e.target.closest('.language-menu')) {
                panel.classList.add('hidden');
            }
        });
    });
    
    // Auto-hide flash messages
    setTimeout(() => { document.getElementById('flash-msg')?.remove(); }, 3500);
</script>
@stack('scripts')
</body>
</html>
