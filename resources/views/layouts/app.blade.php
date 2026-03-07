<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('Restaurant-System.ico') }}">
    <title>{{ config('app.name', 'Restaurant POS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome (if you use icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen antialiased">

    <div class="flex min-h-screen">

        <!-- Mobile Sidebar Backdrop -->
        <div id="sidebar-backdrop"
             class="fixed inset-0 bg-black/60 z-40 lg:hidden transition-opacity duration-300 opacity-0 pointer-events-none">
        </div>

        <!-- Sidebar -->
        @include('layouts.sidebar')

        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen">

            <!-- Top Navbar / Header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-40">
                <div class="flex items-center justify-between px-4 py-3 lg:px-6">

                    <!-- Mobile Hamburger -->
                    <button id="sidebar-toggle" type="button" class="text-gray-500 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg p-2">
                        <svg id="icon-menu" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="icon-close" class="h-7 w-7 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Navbar Content -->
                    @include('layouts.navbar')

                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-50 dark:bg-gray-900">
                <div class="container mx-auto">
                    @yield('content')
                </div>
            </main>

        </div>

    </div>
    @yield('scripts')
</body>
</html>
