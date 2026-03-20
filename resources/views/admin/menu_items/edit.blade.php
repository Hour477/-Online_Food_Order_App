@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Menu Item</h1>
            <p class="mt-1 text-sm text-gray-500">Update "{{ $menu_item->name }}" details</p>
        </div>
        <a href="{{ route('admin.menu_items.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600
                  bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Menu Items
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 lg:p-8">

            <form action="{{ route('admin.menu_items.update', $menu_item->id) }}" method="POST"
                  enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label for="name"
                           class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Dish Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-utensils text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $menu_item->name) }}"
                               required autofocus
                               placeholder="e.g. Spicy Peas Pizza, Butter Corn Cone"
                               class="block w-full pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                      bg-gray-50 border border-gray-200 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id"
                           class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-tags text-gray-400 text-sm"></i>
                        </div>
                        <select name="category_id" id="category_id" required
                                class="block w-full pl-10 pr-10 py-2.5 text-sm text-gray-900
                                       bg-gray-50 border border-gray-200 rounded-lg appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                        {{ old('category_id', $menu_item->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('category_id')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price --}}
                <div>
                    <label for="price"
                           class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-sm font-medium">$</span>
                        </div>
                        <input type="number" step="0.01" min="0"
                               name="price" id="price"
                               value="{{ old('price', $menu_item->price) }}"
                               required
                               placeholder="0.00"
                               class="block w-full pl-8 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                      bg-gray-50 border border-gray-200 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                    </div>
                    @error('price')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status"
                           class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Availability <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-toggle-on text-gray-400 text-sm"></i>
                        </div>
                        <select name="status" id="status" required
                                class="block w-full pl-10 pr-10 py-2.5 text-sm text-gray-900
                                       bg-gray-50 border border-gray-200 rounded-lg appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                            <option value="available"   {{ old('status', $menu_item->status) == 'available'   ? 'selected' : '' }}>Available</option>
                            <option value="unavailable" {{ old('status', $menu_item->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('status')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description"
                           class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                              placeholder="Provide a detailed description of the dish…"
                              class="block w-full px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                     bg-gray-50 border border-gray-200 rounded-lg
                                     focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">{{ old('description', $menu_item->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image --}}
                <div>
                    <label for="image"
                           class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Dish Image <span class="text-gray-400 font-normal normal-case tracking-normal">(optional)</span>
                    </label>
                    <input type="file" name="image" id="image"
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                  file:text-sm file:font-medium
                                  file:bg-amber-50 file:text-amber-700
                                  hover:file:bg-amber-100 transition-colors">
                    @if($menu_item->image)
                        <div class="mt-3 flex items-center gap-3">
                            <img src="{{ asset('storage/' . $menu_item->image) }}"
                                 alt="{{ $menu_item->name }}"
                                 class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-400">Current image — upload a new file to replace it</p>
                        </div>
                    @endif
                    @error('image')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-5 border-t border-gray-100">
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-6
                                   bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium
                                   rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                        <i class="fas fa-save text-sm"></i>
                        Update Menu Item
                    </button>
                    <a href="{{ route('admin.menu_items.index') }}"
                       class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-6
                              bg-white hover:bg-gray-50 text-gray-600 text-sm font-medium
                              rounded-lg border border-gray-200 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

@endsection