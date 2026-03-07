@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

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
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">

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
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $category->name) }}"
                               required
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
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-5 py-3.5 transition-colors"
                              placeholder="Optional notes, ingredients suggestions, or category purpose...">{{ old('description', $category->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Category Status <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-toggle-on text-gray-400"></i>
                        </div>
                        <select name="status"
                                id="status"
                                required
                                class="block w-full pl-11 pr-10 py-3.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition appearance-none">
                            <option value="1" {{ old('status', $category->status) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $category->status) ? '' : 'selected' }}>Inactive</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit"
                            class="flex-1 py-3.5 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-sm transition-all transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Update Category
                    </button>

                    <a href="{{ route('categories.index') }}"
                       class="flex-1 py-3.5 px-6 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-xl border border-gray-300 dark:border-gray-600 text-center transition flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>

                    <!-- Delete Button (with confirmation) -->
                    {{-- <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="flex-1 py-3.5 px-6 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl shadow-sm transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2">
                            <i class="fas fa-trash-alt"></i>
                            Delete Category
                        </button>
                    </form> --}}
                </div>
            </form>

        </div>
    </div>

</div>

@endsection