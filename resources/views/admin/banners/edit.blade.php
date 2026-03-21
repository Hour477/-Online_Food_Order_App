@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Banner</h1>
            <p class="mt-1 text-sm text-gray-500">Update "{{ $banner->title ?? 'Banner #' . $banner->id }}" details</p>
        </div>
        <a href="{{ route('admin.banners.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600
                  bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Banners
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 lg:p-8">

            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Current Image --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Current Image
                    </label>
                    <div class="flex items-center gap-4">
                        <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title ?? 'Banner' }}"
                             class="w-48 h-28 object-cover rounded-lg border border-gray-200">
                    </div>
                </div>

                {{-- New Image --}}
                <div>
                    <label for="image" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        New Image <span class="text-gray-400 font-normal normal-case tracking-normal">(leave empty to keep current)</span>
                    </label>
                    
                    {{-- Image Preview --}}
                    <div class="mb-3 flex items-center gap-4">
                        <div id="preview-placeholder" class="w-48 h-28 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-image text-2xl text-gray-300 mb-1"></i>
                                <p class="text-xs text-gray-400">No new image</p>
                            </div>
                        </div>
                        <img id="image-preview" src="" alt="Preview" class="hidden w-48 h-28 object-cover rounded-lg border border-gray-200">
                    </div>

                    <input type="file" name="image" id="image" accept="image/*"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 
                                  px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors
                                  file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold
                                  file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    <p class="mt-1 text-[10px] text-gray-400 italic">Recommended: 1920×600 px, JPG/PNG/WebP, max 2MB</p>
                    @error('image')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Title <span class="text-gray-400 font-normal normal-case tracking-normal">(optional)</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $banner->title) }}"
                           placeholder="e.g. Summer Special, New Menu Items"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                  px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                    @error('title')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-2">
                        Status
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer gap-3">
                        <input type="checkbox" name="is_active" id="status-toggle"
                               {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 rounded-full
                                    peer-checked:bg-amber-600
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:border after:border-gray-300 after:rounded-full
                                    after:h-5 after:w-5 after:transition-all
                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                    peer-focus:ring-2 peer-focus:ring-amber-500"></div>
                        <span class="text-sm text-gray-600" id="status-label">
                            {{ old('is_active', $banner->is_active) ? 'Active' : 'Inactive' }}
                        </span>
                    </label>
                    @error('is_active')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                    <a href="{{ route('admin.banners.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                        Update Banner
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('status-toggle')?.addEventListener('change', function () {
    document.getElementById('status-label').textContent = this.checked ? 'Active' : 'Inactive';
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
