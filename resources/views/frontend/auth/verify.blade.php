@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Verify OTP</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Enter the OTP sent to {{ $contact }}</p>

        @if(session('success'))
            <div class="mt-4 rounded-lg bg-green-100 text-green-700 px-3 py-2 text-sm">{{ session('success') }}</div>
        @endif

        @if($debugOtp)
            <div class="mt-4 rounded-lg bg-yellow-100 text-yellow-800 px-3 py-2 text-sm">
                Dev OTP: <strong>{{ $debugOtp }}</strong>
            </div>
        @endif

        <form action="{{ route('frontend.verify.submit') }}" method="POST" class="mt-5 space-y-4">
            @csrf
            <div>
                <label for="otp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">OTP Code</label>
                <input
                    id="otp"
                    name="otp"
                    type="text"
                    maxlength="6"
                    value="{{ old('otp') }}"
                    placeholder="6-digit code"
                    class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2.5 text-sm"
                >
                @error('otp')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5">
                Verify and Login
            </button>
        </form>

        <a href="{{ route('frontend.login') }}" class="mt-4 inline-block text-sm text-indigo-600 hover:text-indigo-700">
            Back to login
        </a>
    </div>
</div>
@endsection

