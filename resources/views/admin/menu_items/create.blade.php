@extends('admin.layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important;
        background-color: #f9fafb !important;
        height: 42px !important;
        padding: 4px 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #111827 !important;
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
        padding-left: 8px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #9ca3af !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: transparent !important;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.5) !important;
    }
    .select2-container--default .select2-results__option--selected {
        background-color: #fef3c7 !important;
        color: #92400e !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #f59e0b !important;
        color: white !important;
    }
    .select2-dropdown {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important;
    }
</style>
@endpush

@section('content')
<div class="mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Add New Menu Item</h3>
            <p class="text-sm text-gray-500 mt-1">Create a new menu item for your restaurant</p>
        </div>
        <a href="{{ route('admin.menu_items.index') }}"
           class="text-sm text-gray-500 hover:text-gray-900 flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Menu Items
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 lg:p-8">

        <form action="{{ route('admin.menu_items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div class="mb-2">
                    <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                           placeholder="e.g. ម្ហូបខ្មែរ, Mango Sticky Rice"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                  px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="mb-2">
                    <label for="category_id" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                            class="select2-category w-full"
                            data-placeholder="Select a category">
                        <option value=""></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price --}}
                <div class="mb-2">
                    <label for="price" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Price ($) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-sm">$</span>
                        </div>
                        <input type="number" name="price" id="price" step="0.01" min="0"
                               value="{{ old('price') }}" required
                               placeholder="0.00"
                               class="block w-full pl-8 rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                      px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                    </div>
                    @error('price')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-2">
                    <label for="status" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Availability <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900
                                   px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                        <option value="available" {{ old('status', 'available') === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="unavailable" {{ old('status') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                    @error('status')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image --}}
                <div class="mb-2">
                    <label for="image" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Item Image
                    </label>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 
                                  px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors
                                  file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold
                                  file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    <p class="mt-1 text-[10px] text-gray-400 italic">Recommended: 800×800 px, JPG/PNG, max 2MB</p>
                    @error('image')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image Preview --}}
                <div class="mb-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Image Preview
                    </label>
                    <div class="flex items-center gap-4">
                        <div id="image-preview-container" class="w-16 h-16 rounded-lg border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden bg-gray-50">
                            <img id="image-preview" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                            <svg id="preview-placeholder" class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-[10px] text-gray-400 italic">Square image recommended</p>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-2 md:col-span-2">
                    <label for="description" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              placeholder="A brief description of the dish"
                              class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                     px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors resize-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-8 mt-8 border-t border-gray-100">
                <a href="{{ route('admin.menu_items.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    Create Menu Item
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-category').select2({
            theme: 'default',
            width: '100%',
            placeholder: 'Select a category',
            allowClear: true
        });
    });

    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        const placeholder = document.getElementById('preview-placeholder');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    });
</script>
@endpush
@endsection
