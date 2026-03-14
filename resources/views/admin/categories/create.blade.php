@extends('layouts.app')

@section('content')

    <div class="mx-auto">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Add New Category
                </h3>
                <a href="{{ route('categories.index') }}"
                    class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Categories
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 lg:p-8">

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5 transition-colors"
                        placeholder="e.g. Appetizers, Desserts, Drinks">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5 transition-colors"
                        placeholder="Optional description (ingredients suggestions, notes, etc.)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status - Toggle Switch Style -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}
                            class="sr-only peer">

                        <div class="w-12 h-6 bg-gray-200 rounded-full peer 
                        peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800/40
                        dark:bg-gray-700 peer-checked:after:translate-x-full 
                        peer-checked:after:border-white after:content-[''] 
                        after:absolute after:top-[2px] after:left-[2px] 
                        after:bg-white after:border-gray-300 after:border 
                        after:rounded-full after:h-5 after:w-5 after:transition-all 
                        dark:border-gray-600 peer-checked:bg-indigo-600">
                        </div>

                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <span x-show="!$el.querySelector('input[type=checkbox]').checked">Inactive</span>
                            <span x-show="$el.querySelector('input[type=checkbox]').checked">Active</span>
                        </span>
                    </label>

                    @error('status')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-700/30 dark:border-gray-700">
                        <a href="{{ route('categories.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 
                  hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            Cancel
                        </a>

                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white 
                       bg-indigo-600 hover:bg-indigo-700 rounded-lg 
                       shadow-sm hover:shadow transition-all">
                            Save
                        </button>
                    </div>

            </form>

        </div>

    </div>

@endsection