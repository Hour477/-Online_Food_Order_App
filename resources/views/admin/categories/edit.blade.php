@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Category</h1>
            <p class="mt-1 text-sm text-gray-500">Update "{{ $category->name }}" details</p>
        </div>
        <a href="{{ route('admin.categories.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600
                  bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Categories
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 lg:p-8">

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-tag text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $category->name) }}"
                               required autofocus
                               placeholder="e.g. Appetizers, Desserts, Drinks"
                               class="block w-full pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                      bg-gray-50 border border-gray-200 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Description <span class="text-gray-400 font-normal normal-case tracking-normal">(optional)</span>
                    </label>
                    <textarea name="description" id="description" rows="4"
                              placeholder="Optional notes, ingredients suggestions, or category purpose…"
                              class="block w-full px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                     bg-gray-50 border border-gray-200 rounded-lg
                                     focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">{{ old('description', $category->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer gap-3">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" id="status-toggle"
                               {{ old('status', $category->status ?? 1) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 rounded-full
                                    peer-checked:bg-amber-600
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:border after:border-gray-300 after:rounded-full
                                    after:h-5 after:w-5 after:transition-all
                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                    peer-focus:ring-2 peer-focus:ring-amber-500"></div>
                        <span class="text-sm text-gray-600" id="status-label">
                            {{ old('status', $category->status ?? 1) ? 'Active' : 'Inactive' }}
                        </span>
                    </label>
                    @error('status')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('status-toggle')?.addEventListener('change', function () {
    document.getElementById('status-label').textContent = this.checked ? 'Active' : 'Inactive';
});
</script>

@endsection