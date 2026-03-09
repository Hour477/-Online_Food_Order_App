@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Customer Login</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Login with phone or email. We will send an OTP code.</p>

        @if(session('success'))
            <div class="mt-4 rounded-lg bg-green-100 text-green-700 px-3 py-2 text-sm">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="mt-4 rounded-lg bg-red-100 text-red-700 px-3 py-2 text-sm">{{ session('error') }}</div>
        @endif

        <form action="{{ route('frontend.otp.send') }}" method="POST" class="mt-5 space-y-4">
            @csrf
            <div>
                <label for="login" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone or Email</label>
                <input
                    id="login"
                    name="login"
                    type="text"
                    value="{{ old('login') }}"
                    placeholder="example@email.com or 012345678"
                    class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2.5 text-sm"
                >
                @error('login')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5">
                Send OTP
            </button>
        </form>
    </div>
</div>
@endsection

