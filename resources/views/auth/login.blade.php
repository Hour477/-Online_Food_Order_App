
@php
    $sidebarSettings = \App\Models\Setting::query()
        ->whereIn('key', ['logo', 'resturant_name'])
        ->pluck('value', 'key');
    $sidebarLogo = $sidebarSettings['logo'] ?? null;
    $sidebarName = $sidebarSettings['resturant_name'] ?? config('app.name');
    $logoExists = !empty($sidebarLogo) && \Illuminate\Support\Facades\Storage::disk('public')->exists($sidebarLogo);
    $sidebarLogoUrl = $logoExists ? \Illuminate\Support\Facades\Storage::url($sidebarLogo) : null;  
  
@endphp


@extends('customerOrder.layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">

    <div class="w-full max-w-md bg-white border border-gray-200 shadow-md rounded-lg overflow-hidden">

        {{-- Header --}}
        <div class="px-8 py-8 border-b border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                @if ($sidebarLogoUrl)
                    <img src="{{ $sidebarLogoUrl }}" alt="{{ $sidebarName }}"
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
                <span class="text-base font-semibold text-gray-900 tracking-tight">{{ $sidebarName }}</span>
            </div>

            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">Welcome back</h1>
            <p class="mt-1 text-sm text-gray-500">Login to your account</p>
        </div>

        {{-- Form --}}
        <div class="px-8 py-8">

            @if (session('status'))
                <div class="mb-5 px-4 py-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email"
                           class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">
                        Email
                    </label>
                    <input id="email"
                           name="email"
                           type="email"
                           autocomplete="email"
                           required
                           autofocus
                           value="{{ old('email') }}"
                           placeholder="you@example.com"
                           class="w-full px-3.5 py-2.5 text-sm text-gray-900
                                  bg-gray-50 border border-gray-200 rounded-xl
                                  placeholder-gray-400
                                  focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                  transition-all duration-150">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password"
                               class="block text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Password
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-amber-600 hover:text-amber-500 transition">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <input id="password"
                           name="password"
                           type="password"
                           autocomplete="current-password"
                           required
                           placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 text-sm text-gray-900
                                  bg-gray-50 border border-gray-200 rounded-xl
                                  placeholder-gray-400
                                  focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                  transition-all duration-150">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                {{-- <div class="flex items-center gap-2.5">
                    <input id="remember"
                           name="remember"
                           type="checkbox"
                           class="w-4 h-4 accent-amber-600 border-gray-300 rounded">
                    <label for="remember" class="text-sm text-gray-600">
                        Keep me signed in
                    </label>
                </div> --}}

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-2.5 px-4 bg-amber-600 hover:bg-amber-700 active:bg-amber-800
                               text-white text-sm font-medium rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500
                               transition-all duration-150 active:scale-[0.99]">
                    Login
                </button>

            </form>

            {{-- Register link --}}
            @if (Route::has('register'))
                <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                           class="font-medium text-amber-600 hover:text-amber-500 transition">
                            Register
                        </a>
                    </p>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection