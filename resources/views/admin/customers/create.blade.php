@extends('admin.layouts.app')


@section('content')
    {{-- Create New Customer --}}
    <div class="mx-auto">
        <!-- Header -->
        <div class="mb-10 text-center sm:text-left">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                Create New Customer
            </h1>

            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Add a new customer to the system
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border
                border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
    
                    <form action="{{ route('admin.customers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
    
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                value="{{ old('name') ?? 'Waik-in' }}"
                                class="block w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-600 focus:border-amber-500 transition"
                                placeholder="e.g. John Doe">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
    
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email') }}"
                                class="block w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-600 focus:border-amber-500 transition"
                                placeholder="e.g. john@example.com">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone
                            </label>
                            <input type="text" name="phone" id="phone"
                                value="{{ old('phone') }}"
                                class="block w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-600 focus:border-amber-500 transition"
                                placeholder="e.g. 123-456-789">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password
                            </label>
                            <input type="password" name="password" id="password"
                                class="block w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-600 focus:border-amber-500 transition"
                                placeholder="Minimum 6 characters">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Default: password123</p>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="block w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-600 focus:border-amber-500 transition"
                                placeholder="Confirm password">
                        </div>

                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Profile Image
                            </label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="block w-full px-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-600 focus:border-amber-500 transition">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Max size: 2MB. Formats: jpeg, png, jpg, gif</p>
                            @error('image')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-xl shadow-sm transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500
                                ">
                                <i class="fas fa-plus mr-2"></i>
                                Create Customer
                            </button>
                            <a href="{{ route('admin.customers.index') }}"
                                class="ml-4 inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-xl transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Customers
                            </a>
                        </div>
                        
                    </form>
                </div>
            </div>
    </div>
@endsection