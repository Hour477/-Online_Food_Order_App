<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Saveur — Fine Food Delivery')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#fff8f0',
                            100: '#ffe8cc',
                            200: '#ffc980',
                            300: '#ffaa33',
                            400: '#ff8c00',
                            500: '#e07800',
                            600: '#b85f00',
                            700: '#8f4800',
                            800: '#663300',
                            900: '#3d1f00',
                        },
                        cream: '#fdf6ec',
                        ink:   '#1a1208',
                    },
                    fontFamily: {
                        display: ['"Playfair Display"', 'serif'],
                        body:    ['"DM Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #fdf6ec; color: #1a1208; }
        .font-display { font-family: 'Playfair Display', serif; }
        .nav-link { position: relative; }
        .nav-link::after { content:''; position:absolute; bottom:-2px; left:0; width:0; height:2px; background:#ff8c00; transition:.3s; }
        .nav-link:hover::after { width:100%; }
        .card-hover { transition: transform .25s ease, box-shadow .25s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(26,18,8,.12); }
        .btn-primary { background:#ff8c00; color:#fff; transition: background .2s, transform .1s; }
        .btn-primary:hover { background:#e07800; transform:scale(1.02); }
        .btn-primary:active { transform:scale(.98); }
        .badge { animation: pop .3s cubic-bezier(.36,.07,.19,.97); }
        @keyframes pop { 0%,100%{transform:scale(1)} 50%{transform:scale(1.4)} }
        .slide-in { animation: slideIn .4s ease forwards; }
        @keyframes slideIn { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
        ::-webkit-scrollbar { width:6px; } ::-webkit-scrollbar-thumb { background:#ffc980; border-radius:3px; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col">

{{-- ===== NAVBAR ===== --}}
<nav class="sticky top-0 z-50 bg-cream/90 backdrop-blur-md border-b border-brand-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('customerOrder.menu.index') }}" class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-brand-400 flex items-center justify-center">
                    <i class="fa-solid fa-bowl-food text-white text-sm"></i>
                </span>
                <span class="font-display text-2xl font-bold text-ink">Saveur</span>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('customerOrder.menu.index') }}" class="nav-link text-sm font-medium {{ request()->routeIs('menu.*') ? 'text-brand-400' : 'text-ink/70 hover:text-ink' }}">Menu</a>
                <a href="{{ route('customerOrder.orders.history') }}" class="nav-link text-sm font-medium {{ request()->routeIs('orders.*') ? 'text-brand-400' : 'text-ink/70 hover:text-ink' }}">My Orders</a>
            </div>

            {{-- Cart + Mobile --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('customerOrder.cart.index') }}" class="relative p-2 rounded-full hover:bg-brand-100 transition">
                    <i class="fa-solid fa-basket-shopping text-xl text-ink"></i>
                    @php $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'qty')) : 0; @endphp
                    @if($cartCount > 0)
                    <span class="badge absolute -top-1 -right-1 w-5 h-5 rounded-full bg-brand-400 text-white text-xs flex items-center justify-center font-bold">{{ $cartCount }}</span>
                    @endif
                </a>
                <button class="md:hidden p-2 rounded-full hover:bg-brand-100 transition" id="mobile-menu-btn">
                    <i class="fa-solid fa-bars text-ink"></i>
                </button>

                {{-- Login + Signup--}}
                @auth
                <div class="flex items-center gap-4 ml-2 border-l border-brand-100 pl-4">
                    <div class="hidden lg:block text-right">
                        <p class="text-[10px] uppercase tracking-wider text-ink/40 font-bold leading-none">Welcome</p>
                        <p class="text-sm font-semibold text-ink leading-tight">{{ Auth::user()->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-full text-ink/50 hover:text-red-500 hover:bg-red-50 transition" title="Logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
                @else
                <div class="flex items-center gap-2 ml-2">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-ink/70 hover:text-brand-400 px-3 py-2 transition">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary text-xs font-bold px-4 py-2 rounded-full shadow-sm">Sign Up</a>
                </div>
                @endauth
                
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-brand-100 bg-cream px-4 py-3 space-y-2">
        <a href="{{ route('customerOrder.menu.index') }}" class="block py-2 text-sm font-medium text-ink/80 hover:text-brand-400">Menu</a>
        <a href="{{ route('customerOrder.orders.history') }}" class="block py-2 text-sm font-medium text-ink/80 hover:text-brand-400">My Orders</a>
    </div>
</nav>

{{-- ===== FLASH MESSAGES ===== --}}
@if(session('success'))
<div class="fixed top-20 right-4 z-50 bg-emerald-500 text-white px-5 py-3 rounded-xl shadow-lg slide-in flex items-center gap-2" id="flash-msg">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="fixed top-20 right-4 z-50 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg slide-in flex items-center gap-2" id="flash-msg">
    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
@endif

{{-- ===== MAIN CONTENT ===== --}}
<main class="flex-1">
    @yield('content')
</main>

{{-- ===== FOOTER ===== --}}
<footer class="bg-ink text-cream/70 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-7 h-7 rounded-full bg-brand-400 flex items-center justify-center">
                        <i class="fa-solid fa-bowl-food text-white text-xs"></i>
                    </span>
                    <span class="font-display text-xl font-bold text-cream">Saveur</span>
                </div>
                <p class="text-sm leading-relaxed">Fine food, delivered with care. From our kitchen to your door.</p>
            </div>
            <div>
                <h4 class="text-cream font-semibold mb-3">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('customerOrder.menu.index') }}" class="hover:text-brand-300 transition">Browse Menu</a></li>
                    <li><a href="{{ route('customerOrder.cart.index') }}" class="hover:text-brand-300 transition">Your Cart</a></li>
                    <li><a href="{{ route('customerOrder.orders.history') }}" class="hover:text-brand-300 transition">Order History</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-cream font-semibold mb-3">Hours</h4>
                <p class="text-sm">Mon–Fri: 10am – 10pm</p>
                <p class="text-sm">Sat–Sun: 11am – 11pm</p>
            </div>
        </div>
        <div class="border-t border-white/10 mt-8 pt-6 text-center text-xs text-cream/40">
            © {{ date('Y') }} Saveur. All rights reserved.
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
