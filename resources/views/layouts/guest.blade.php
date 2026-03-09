<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $guestTitle = \App\Models\Setting::where('key', 'resturant_name')->value('value');
        $guestFavicon = \App\Models\Setting::where('key', 'favicon')->value('value');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ !empty($guestFavicon) ? asset('storage/settings/' . $guestFavicon) : asset('Restaurant-System.ico') }}">
    <title>{{ $guestTitle ?: config('app.name', 'Restaurant POS') }}</title>
    
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen antialiased">
    @yield('content')
</body>
</html>
