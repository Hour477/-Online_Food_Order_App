<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $guestTitle   = \App\Models\Setting::where('key', 'resturant_name')->value('value');
        $guestFavicon = \App\Models\Setting::where('key', 'favicon')->value('value');
    @endphp

    <title>{{ $guestTitle ?? config('app.name') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ !empty($guestFavicon) ? asset('storage/settings/' . $guestFavicon) : asset('Restaurant-System.ico') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    </head>
    <body class="bg-gray-50 text-gray-900 min-h-screen antialiased">

    @yield('content')

</body>
</html>