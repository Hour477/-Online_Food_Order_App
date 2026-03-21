
@extends('admin.layouts.app')

@section('content')
<div class="mx-auto">

    @if(!$user)
    {{-- User not found --}}
    <div class="text-center py-24">
        <i class="fa-solid fa-circle-exclamation text-6xl text-red-400 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">User Not Found</h3>
        <p class="text-gray-500 mb-6">The user you're trying to edit doesn't exist or has been deleted.</p>
        <a href="{{ route('admin.users.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Users
        </a>
    </div>
    @else

    {{-- Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Edit User</h3>
            <p class="text-sm text-gray-500 mt-1">Update user information</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="text-sm text-gray-500 hover:text-gray-900 flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Users
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 lg:p-8">

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div class="mb-2">
                    <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autofocus
                           placeholder="e.g. John Doe"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                  px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-2">
                    <label for="email" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           placeholder="e.g. john@example.com"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                  px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                {{-- image --}}
                

                {{-- Password --}}
                {{-- Leave empty to keep current password --}}
                <div class="mb-2">
                    <label for="password" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        New Password (optional)
                    </label>
                    <input type="password" name="password" id="password"
                           placeholder="Leave empty to keep current password"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                  px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-2">
                    <label for="password_confirmation" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Confirm New Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Confirm new password"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                  px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                </div>

                {{-- Role --}}
                <div class="mb-2">
                    <label for="role_id" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        User Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role_id" id="role_id" required
                            class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900
                                   px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                        <option value="" disabled>Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach 
                    </select>
                    @error('role_id')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-2">
                    <label for="image" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Profile Image (Optional)
                    </label>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 
                                  px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors
                                  file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold
                                  file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    @error('image')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                {{-- Current Image Display --}}
                @if($user->image)
                <div class="mb-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Current Image
                    </label>
                    <div class="flex items-center gap-4">
                        <img src="{{ $user->display_image }}" 
                             class="w-20 h-20 rounded-lg object-cover border-2 border-gray-200">
                        <p class="text-xs text-gray-500">Upload a new image to replace this one</p>
                    </div>
                </div>
                @endif
                {{-- preview image--}}
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
                        <p class="text-[10px] text-gray-400 italic">Recommended: Square image, max 2MB</p>
                    </div>
                </div>

                <script>
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

            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-8 mt-8 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                        <i class="fa-solid fa-pen-to-square mr-2"></i>
                        Update User
                </button>
            </div>

        </form>
    </div>
    @endif
</div>

@endsection
