@extends('customerOrder.layouts.app')  <!-- Use a minimal guest layout without sidebar/navbar -->

@section('content')

<div class="min-h-screen flex items-center justify-center bg-linear-to-br from-gray-50 via-gray-100 to-gray-200 dark:from-gray-900 dark:via-gray-950 dark:to-black px-4 py-12 sm:px-6 lg:px-8">

    <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700 transition-all duration-300">

        <!-- Header / Branding -->
        <div class="px-8 py-10 bg-linear-to-r from-indigo-600 to-indigo-800 text-white text-center">
            <div class="text-5xl mb-3 drop-shadow-lg">
                🍽
            </div>
            <h2 class="text-3xl font-bold tracking-tight">
                Restaurant POS
            </h2>
            <p class="mt-2 text-indigo-100 opacity-90">
                Sign in to continue
            </p>
        </div>

        <!-- Form -->
        <div class="px-8 py-10">
            <!-- Session Status / Errors -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email address
                    </label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           autofocus
                           value="{{ old('email') }}"
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password
                    </label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="current-password" 
                           required
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me + Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" 
                               name="remember" 
                               type="checkbox"
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Remember me
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-[1.02]">
                        Sign in
                    </button>
                </div>
            </form>

            <!-- Register Link -->
            @if (Route::has('register'))
                <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}"
                       class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                        Register now
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection