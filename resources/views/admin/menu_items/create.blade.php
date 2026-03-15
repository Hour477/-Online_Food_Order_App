@extends('admin.layouts.app')

@section('content')

<div class="py-8">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                Add New Menu Item
            </h3>
            <a href="{{ route('admin.menu_items.index') }}"
               class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Menu Items
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 lg:p-8">

        <form action="{{ route('admin.menu_items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name') }}"
                       required
                       autofocus
                       class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5 transition-colors"
                       placeholder="e.g. Pad Thai, Mango Sticky Rice">
                @error('name')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Category <span class="text-red-500">*</span>
                </label>
                <select name="category_id"
                        id="category_id"
                        required
                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5 transition-colors">
                    <option value="">— Select Category —</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            

            <!-- Price -->
            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Price ($) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                    </div>
                    <input type="number"
                           name="price"
                           id="price"
                           step="0.01"
                           min="0"
                           value="{{ old('price') }}"
                           required
                           class="block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5 transition-colors"
                           placeholder="0.00">
                </div>
                @error('price')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            

            <!-- Status -->
            <div class="mb-8">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Availability <span class="text-red-500">*</span>
                </label>
                <select name="status"
                        id="status"
                        required
                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5 transition-colors">
                    <option value="available"   {{ old('status', 'available') === 'available'   ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ old('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
                @error('status')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <!-- Image Upload -->
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Item Image
                </label>
                <input type="file"
                       name="image"
                       id="image"
                       accept="image/*"
                       class="block w-full text-sm text-gray-500 dark:text-gray-400
                              file:mr-4 file:py-2.5 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-medium
                              file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100
                              dark:file:bg-gray-700 dark:file:text-indigo-300 dark:hover:file:bg-gray-600
                              transition-colors">
                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                    Recommended: 800×800 px, JPG/PNG, max 2MB
                </p>
                @error('image')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description
                </label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2.5 transition-colors"
                          placeholder="A brief description of the dish">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                
            </div>
            <!-- Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Menu Item
                </button>

                <a href="{{ route('admin.menu_items.index') }}"
                   class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 text-base transition-colors">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

@endsection