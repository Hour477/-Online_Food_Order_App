@extends('admin.layouts.app')

@section('title', 'Add New Menu Item')

@section('content')
<div class="mx-auto">
    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-4 md:p-4">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-10">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">Add New Menu Item</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create a new menu item for your restaurant menu</p>
            </div>
            <a href="{{ route('admin.menu_items.index') }}"
               class="inline-flex shrink-0 items-center justify-center px-5 py-3 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
                <i class="fas fa-arrow-left mr-2 text-gray-400"></i>
                Back to Menu Items
            </a>
        </div>

        <form action="{{ route('admin.menu_items.store') }}" method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Name --}}
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Menu Item Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-utensils text-gray-400"></i>
                        </div>
                        <input type="text" name="name" id="name"
                               value="{{ old('name') }}"
                               required autofocus
                               placeholder="e.g. Spicy Pepperoni Pizza"
                               class="block w-full pl-11 pr-4 py-3.5 text-base text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
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
                            <i class="fas fa-tags text-gray-400"></i>
                        </div>
                        <select name="category_id" id="category_id" required
                                class="block w-full pl-11 pr-10 py-3.5 text-base text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <i class="fas fa-chevron-down text-gray-400"></i>
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
                            <span class="text-gray-400 font-semibold">$</span>
                        </div>
                        <input type="number" step="0.01" min="0"
                               name="price" id="price"
                               value="{{ old('price') }}"
                               required
                               placeholder="0.00"
                               class="block w-full pl-11 pr-4 py-3.5 text-base text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
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
                            <i class="fas fa-toggle-on text-gray-400"></i>
                        </div>
                        <select name="status" id="status" required
                                class="block w-full pl-11 pr-10 py-3.5 text-base text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                            <option value="available"   {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rating --}}
                <div>
                    <label for="rating" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Initial Rating (0&ndash;5)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-star text-gray-400"></i>
                        </div>
                        <input type="number" step="0.1" min="0" max="5"
                               name="rating" id="rating"
                               value="{{ old('rating', 0) }}"
                               placeholder="0.0"
                               class="block w-full pl-11 pr-4 py-3.5 text-base text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    </div>
                    @error('rating')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Popularity --}}
                <div>
                    <label for="popularity" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Initial Popularity
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-fire text-gray-400"></i>
                        </div>
                        <input type="number" min="0"
                               name="popularity" id="popularity"
                               value="{{ old('popularity', 0) }}"
                               placeholder="0"
                               class="block w-full pl-11 pr-4 py-3.5 text-base text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    </div>
                    @error('popularity')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dish Image --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3">
                        Dish Image
                    </label>
                    <div class="relative group w-32 h-32">
                        <img id="image-preview"
                             src="{{ asset('assets/img/placeholder.png') }}"
                             alt=""
                             class="w-32 h-32 rounded-lg border-4 border-white dark:border-gray-700 shadow-md object-cover bg-gray-50 dark:bg-gray-700 transition-all group-hover:brightness-90">

                        <label for="image-upload" class="absolute inset-0 flex items-center justify-center bg-black/40 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <i class="fas fa-camera text-xl" aria-hidden="true"></i>
                            <span class="sr-only">Choose dish image</span>
                        </label>
                        <input type="file" id="image-upload" name="image" class="hidden" accept="image/*">
                    </div>
                    <p class="mt-2 text-[10px] text-gray-500 dark:text-gray-400 italic">Recommended: Square image, max 2MB.</p>
                    @error('image')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="5"
                              placeholder="Describe this delicious dish, ingredients, taste, and special notes..."
                              class="block w-full px-5 py-4 text-base text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-y transition-all">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                <a href="{{ route('admin.menu_items.index') }}"
                   class="px-5 py-2.5 text-sm font-medium  hover:bg-gray-100 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('image-upload').addEventListener('change', function (e) {
        var file = e.target.files && e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (ev) {
            var img = document.getElementById('image-preview');
            if (img) img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
@endsection
