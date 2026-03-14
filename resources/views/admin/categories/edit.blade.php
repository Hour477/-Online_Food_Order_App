@extends('layouts.app')

@section('content')

    <div class="mx-auto">

        <!-- Header -->
        <div class="mb-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Edit Category
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Update "{{ $category->name }}" details
                </p>
            </div>

            <a href="{{ route('categories.index') }}"
                class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Categories
            </a>
        </div>

        <!-- Form Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="p-6 lg:p-8">

                <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                                autofocus
                                class="block w-full pl-11 pr-4 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                placeholder="e.g. Appetizers, Desserts, Drinks">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description (optional)
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-5 py-3.5 transition-colors"
                            placeholder="Optional notes, ingredients suggestions, or category purpose...">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        Category Status <span class="text-red-500">*</span>
    </label>

    <label class="relative inline-flex items-center cursor-pointer">
        <input type="hidden" name="status" value="0">
        <input type="checkbox" name="status" value="1"
               {{ old('status', $category->status ?? 1) ? 'checked' : '' }}
               class="sr-only peer">

        <div class="w-12 h-6 bg-gray-200 rounded-full peer 
                    peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 
                    dark:peer-focus:ring-indigo-800/50 dark:bg-gray-700
                    peer-checked:after:translate-x-full peer-checked:after:border-white 
                    after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                    after:bg-white after:border-gray-300 after:border after:rounded-full 
                    after:h-5 after:w-5 after:transition-all dark:border-gray-600 
                    peer-checked:bg-indigo-600"></div>

        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ old('status', $category->status ?? 1) ? 'Active' : 'Inactive' }}
        </span>
    </label>

    @error('status')
        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-700/30 dark:border-gray-700">
                        <a href="{{ route('categories.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 
                      hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            Cancel
                        </a>

                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white 
                           bg-indigo-600 hover:bg-indigo-700 rounded-lg 
                           shadow-sm hover:shadow transition-all">
                            Update
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

@endsection