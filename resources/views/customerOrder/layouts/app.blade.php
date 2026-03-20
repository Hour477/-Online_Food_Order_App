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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
    <link rel="icon" href="{{ asset('storage/settings/' . $sidebarLogo) }}" alt="{{ $sidebarName }}" loading="lazy">
    
</head>
<body class="min-h-screen flex flex-col bg-gray-50">

{{-- ===== NAVBAR ===== --}}
<nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('customerOrder.menu.index') }}" class="flex       items-center gap-2">
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
            <p class="text-2xl font-bold text-gray-900">{{ $sidebarName }}</p>

                
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('customerOrder.menu.index') }}" class="text-sm font-medium {{ request()->routeIs('menu.*') ? 'text-amber-600' : 'text-gray-600 hover:text-gray-900' }}">Menu</a>
                <a href="{{ route('customerOrder.orders.history') }}" class="text-sm font-medium {{ request()->routeIs('orders.*') ? 'text-amber-600' : 'text-gray-600 hover:text-gray-900' }}">My Orders</a>
            </div>

            {{-- Cart + Mobile --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('customerOrder.cart.index') }}" class="relative p-2 rounded-full hover:bg-gray-100 transition">
                    <i class="fa-solid fa-basket-shopping text-xl text-gray-700"></i>
                    @php $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'qty')) : 0; @endphp
                    @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-amber-500 text-white text-xs flex items-center justify-center font-bold">{{ $cartCount }}</span>
                    @endif
                </a>
                <button class="md:hidden p-2 rounded-full hover:bg-gray-100 transition" id="mobile-menu-btn">
                    <i class="fa-solid fa-bars text-gray-700"></i>
                </button>

                {{-- Login + Signup--}}
                @auth
                <div class="flex items-center gap-4 ml-2 border-l border-gray-200 pl-4">
                    <div class="hidden lg:block text-right">
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold leading-none">Welcome</p>
                        <p class="text-sm font-semibold text-gray-900 leading-tight">{{ Auth::user()->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition" title="Logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
                @else
                <div class="flex items-center gap-2 ml-2">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-amber-600 px-3 py-2 transition">Login</a>
                    <a href="{{ route('register') }}" class="text-xs font-bold px-4 py-2 rounded-lg shadow-sm bg-amber-600 text-white hover:bg-amber-700 transition">Sign Up</a>
                </div>
                @endauth
                
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white px-4 py-3 space-y-2">
        <a href="{{ route('customerOrder.menu.index') }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-amber-600">Menu</a>
        <a href="{{ route('customerOrder.orders.history') }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-amber-600">My Orders</a>
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
                    <span class="w-7 h-7 rounded-full bg-amber-500 flex items-center justify-center">
                        <i class="fa-solid fa-bowl-food text-white text-xs"></i>
                    </span>
                    <span class="text-xl font-bold text-white">Restaurant</span>
                </div>
                <p class="text-sm leading-relaxed">Fine food, delivered with care. From our kitchen to your door.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('customerOrder.menu.index') }}" class="hover:text-amber-400 transition">Browse Menu</a></li>
                    <li><a href="{{ route('customerOrder.cart.index') }}" class="hover:text-amber-400 transition">Your Cart</a></li>
                    <li><a href="{{ route('customerOrder.orders.history') }}" class="hover:text-amber-400 transition">Order History</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Hours</h4>
                <p class="text-sm">Mon-Fri: 10am - 10pm</p>
                <p class="text-sm">Sat-Sun: 11am - 11pm</p>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-6 text-center text-xs text-gray-500">
            © {{ date('Y') }} Restaurant. All rights reserved.
        </div>
    </div>
</footer>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
    // Auto-hide flash messages
    setTimeout(() => { document.getElementById('flash-msg')?.remove(); }, 3500);
</script>
@stack('scripts')
</body>
</html>
