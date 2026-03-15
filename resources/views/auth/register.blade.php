@extends('customerOrder.layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-12">

    <div class="w-full max-w-md bg-white border border-gray-200 rounded-2xl overflow-hidden">

        {{-- Header --}}
        <div class="px-8 py-7 border-b border-gray-100">
            <div class="flex items-center gap-2.5 mb-6">
                <div class="w-8 h-8 rounded-lg bg-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.2"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3 11l19-9-9 19-2-8-8-2z"/>
                    </svg>
                </div>
                <span class="text-[15px] font-semibold text-gray-900 tracking-tight">Saveur POS</span>
            </div>
            <h1 class="text-xl font-semibold text-gray-900 tracking-tight">Create your account</h1>
            <p class="mt-1 text-sm text-gray-500">Set up your staff access</p>
        </div>

        {{-- Form --}}
        <div class="px-8 py-7">

            @if ($errors->any())
                <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Full Name --}}
                <div>
                    <label for="name"
                           class="block text-[11px] font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Full Name
                    </label>
                    <input id="name"
                           name="name"
                           type="text"
                           autocomplete="name"
                           required
                           autofocus
                           value="{{ old('name') }}"
                           placeholder="John Doe"
                           class="w-full px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                  bg-gray-50 border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                  transition-colors duration-150">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email"
                           class="block text-[11px] font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Email
                    </label>
                    <input id="email"
                           name="email"
                           type="email"
                           autocomplete="email"
                           required
                           value="{{ old('email') }}"
                           placeholder="you@saveur.com"
                           class="w-full px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                  bg-gray-50 border border-gray-200 rounded-lg
                                  focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                  transition-colors duration-150">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password"
                           class="block text-[11px] font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <input id="password"
                               name="password"
                               type="password"
                               autocomplete="new-password"
                               required
                               placeholder="••••••••"
                               class="w-full px-3 py-2.5 pr-10 text-sm text-gray-900 placeholder-gray-400
                                      bg-gray-50 border border-gray-200 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                      transition-colors duration-150">
                        <button type="button" onclick="togglePassword('password')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-eye text-xs" id="password-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation"
                           class="block text-[11px] font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <input id="password_confirmation"
                               name="password_confirmation"
                               type="password"
                               autocomplete="new-password"
                               required
                               placeholder="••••••••"
                               class="w-full px-3 py-2.5 pr-10 text-sm text-gray-900 placeholder-gray-400
                                      bg-gray-50 border border-gray-200 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                      transition-colors duration-150">
                        <button type="button" onclick="togglePassword('password_confirmation')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-eye text-xs" id="password_confirmation-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="pt-1">
                    <button type="submit"
                            class="w-full py-2.5 px-4 bg-amber-600 hover:bg-amber-700 active:bg-amber-800
                                   text-white text-sm font-medium rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500
                                   transition-colors duration-150 active:scale-[0.99]">
                        Create Account
                    </button>
                </div>

            </form>

            {{-- Login link --}}
            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400">
                    Already have an account?
                    <a href="{{ route('login') }}"
                       class="font-medium text-amber-600 hover:text-amber-500 transition-colors">
                        Sign in
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye   = document.getElementById(fieldId + '-eye');
    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

@endsection