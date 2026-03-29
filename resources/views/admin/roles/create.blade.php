
@extends('admin.layouts.app')

@section('content')
<div class="mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Add New Role</h3>
            <p class="text-sm text-gray-500 mt-1">Define a new access level for the system</p>
        </div>
        <a href="{{ route('admin.roles.index') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Roles
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 lg:p-8">

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                {{-- Role Name --}}
                <div>
                    <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Role Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                           placeholder="e.g. Manager, Cashier"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                  px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                                  
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role Slug --}}
                <div>
                    <label for="slug" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Role Slug <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                           placeholder="e.g. manager, cashier"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                  px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                    <p class="mt-1.5 text-[10px] text-gray-400 italic">Unique identifier used by the system (lowercase, no spaces)</p>
                    @error('slug')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                {{-- description --}}
                <div>
                    <label for="description" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              placeholder="Briefly describe the responsibilities of this role..."
                              class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                     px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-8 mt-8 border-t border-gray-100">
                <a href="{{ route('admin.roles.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    Create Role
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    // Simple slug generator using AJAX
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slugInput = document.getElementById('slug');
        
        if (name.length > 2) {
            fetch(`{{ route('admin.roles.generate-slug') }}?name=${encodeURIComponent(name)}`)
                .then(response => response.json())
                .then(data => {
                    slugInput.value = data.slug;
                })
                .catch(error => console.error('Error generating slug:', error));
        } else if (name.length === 0) {
            slugInput.value = '';
        }
    });

    // Manual slug override handling
    document.getElementById('slug').addEventListener('input', function() {
        this.value = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
    });
</script>

@endsection