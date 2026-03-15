<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $appTitle   = \App\Models\Setting::where('key', 'resturant_name')->value('value');
        $appFavicon = \App\Models\Setting::where('key', 'favicon')->value('value');
    @endphp

    <link rel="icon" type="image/x-icon"
          href="{{ !empty($appFavicon) ? asset('storage/settings/' . $appFavicon) : asset('Restaurant-System.ico') }}">
    <title>{{ $appTitle ?: config('app.name', 'Restaurant POS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Apply saved sidebar collapsed state before first paint to avoid flash
        (function () {
            try {
                if (localStorage.getItem('sidebar_collapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {
                // localStorage might be unavailable (e.g. privacy mode); fail silently
            }
        })();
    </script>

 
    {{-- Google Material Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"
          crossorigin="anonymous">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    {!! ToastMagic::styles() !!}
</head>

<body class="bg-gray-100 text-gray-900 min-h-screen antialiased">

    <div class="flex min-h-screen">

        {{-- Mobile Sidebar Backdrop --}}
        <div id="sidebar-backdrop"
             class="fixed inset-0 bg-black/60 z-40 lg:hidden transition-opacity duration-300 opacity-0 pointer-events-none">
        </div>

        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-screen">

            {{-- Header --}}
            <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
                <div class="flex items-center justify-between px-4 py-3 lg:px-6">

                    
                    

                    {{-- Navbar --}}
                    @include('admin.layouts.navbar')

                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-50">
                @yield('content')
            </main>

            @include('admin.layouts.footer')

        </div>
    </div>

    {!! ToastMagic::scripts() !!}
    @yield('scripts')

</body>
</html>