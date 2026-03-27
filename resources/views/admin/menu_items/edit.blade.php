@extends('admin.layouts.app')

@section('title', 'Edit Menu Item')

@section('content')
<div class="mx-auto">
    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">
        
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Menu Item</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update details for "{{ $menu_item->name }}"</p>
            </div>
            <a href="{{ route('admin.menu_items.index') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Back to Menu Items
            </a>
        </div>

        <form action="{{ route('admin.menu_items.update', $menu_item->id) }}" method="POST"
              enctype="multipart/form-data" class="max-w-4xl">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {{-- Name --}}
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Dish Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-utensils text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $menu_item->name) }}"
                               required autofocus
                               placeholder="e.g. Spicy Peas Pizza"
                               class="block w-full pl-11 pr-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-tags text-gray-400 text-sm"></i>
                        </div>
                        <select name="category_id" id="category_id" required
                                class="block w-full pl-11 pr-10 py-3 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                        {{ old('category_id', $menu_item->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    @error('category_id')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price --}}
                <div>
                    <label for="price" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-sm font-bold">$</span>
                        </div>
                        <input type="number" step="0.01" min="0"
                               name="price" id="price"
                               value="{{ old('price', $menu_item->price) }}"
                               required
                               placeholder="0.00"
                               class="block w-full pl-11 pr-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    </div>
                    @error('price')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Availability <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-toggle-on text-gray-400 text-sm"></i>
                        </div>
                        <select name="status" id="status" required
                                class="block w-full pl-11 pr-10 py-3 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                            <option value="available"   {{ old('status', $menu_item->status) == 'available'   ? 'selected' : '' }}>Available</option>
                            <option value="unavailable" {{ old('status', $menu_item->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rating --}}
                <div>
                    <label for="rating" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Rating (0-5)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-star text-gray-400 text-sm"></i>
                        </div>
                        <input type="number" step="0.1" min="0" max="5"
                               name="rating" id="rating"
                               value="{{ old('rating', $menu_item->rating) }}"
                               placeholder="0.0"
                               class="block w-full pl-11 pr-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    </div>
                    @error('rating')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Popularity --}}
                <div>
                    <label for="popularity" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Popularity (Order Count)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-fire text-gray-400 text-sm"></i>
                        </div>
                        <input type="number" min="0"
                               name="popularity" id="popularity"
                               value="{{ old('popularity', $menu_item->popularity) }}"
                               placeholder="0"
                               class="block w-full pl-11 pr-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    </div>
                    @error('popularity')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image --}}
                <div>
                    <label for="image" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Dish Image
                    </label>
                    <div class="flex items-center gap-4">
                        <input type="file" name="image" id="image"
                               class="block w-full text-xs text-gray-500 dark:text-gray-400
                                      file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                                      file:text-xs file:font-bold
                                      file:bg-amber-50 file:text-amber-700 dark:file:bg-amber-900/30 dark:file:text-amber-500
                                      hover:file:bg-amber-100 transition-all cursor-pointer">
                        @if($menu_item->image)
                            <img src="{{ $menu_item->display_image }}"
                                 alt="Current"
                                 class="h-12 w-12 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                        @endif
                    </div>
                    @error('image')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                              placeholder="Describe this delicious dish…"
                              class="block w-full px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">{{ old('description', $menu_item->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 py-3 px-6 bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    <i class="fas fa-save"></i>
                    Update Menu Item
                </button>
                <a href="{{ route('admin.menu_items.index') }}"
                   class="flex-1 inline-flex items-center justify-center gap-2 py-3 px-6 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-200 text-sm font-bold rounded-xl border border-gray-200 dark:border-gray-600 transition-all">
                    <i class="fas fa-times"></i>
                    Cancel Changes
                </a>
            </div>

        </form>
    </div>
</div>
@endsection