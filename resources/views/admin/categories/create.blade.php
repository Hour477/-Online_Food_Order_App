@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <h3 class="text-2xl font-bold text-gray-900">Add New Category</h3>
        <a href="{{ route('admin.categories.index') }}"
           class="text-sm text-gray-500 hover:text-gray-900 flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Categories
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 lg:p-8">

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            {{-- Name --}}
            <div class="mb-6">
                <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                    Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       placeholder="e.g. Appetizers, Desserts, Drinks"
                       class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                              px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                    Description
                </label>
                <textarea name="description" id="description" rows="4"
                          placeholder="Optional description, ingredients suggestions, notes…"
                          class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <label class="relative inline-flex items-center cursor-pointer gap-3">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" id="status-toggle"
                           {{ old('status', 1) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 rounded-full
                                peer-checked:bg-amber-600
                                after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                after:bg-white after:border after:border-gray-300 after:rounded-full
                                after:h-5 after:w-5 after:transition-all
                                peer-checked:after:translate-x-full peer-checked:after:border-white
                                peer-focus:ring-2 peer-focus:ring-amber-500"></div>
                    <span class="text-sm text-gray-600" id="status-label">
                        {{ old('status', 1) ? 'Active' : 'Inactive' }}
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
                    Save
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.getElementById('status-toggle')?.addEventListener('change', function () {
    document.getElementById('status-label').textContent = this.checked ? 'Active' : 'Inactive';
});
</script>

@endsection