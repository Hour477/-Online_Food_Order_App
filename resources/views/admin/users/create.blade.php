@extends('admin.layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="mx-auto">
    {{-- Add New User --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">
        
        <!-- Header -->
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Add New User</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create a new system user or staff member</p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required autofocus
                           value="{{ old('name') }}"
                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors text-sm"
                           placeholder="e.g. John Doe">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" required
                           value="{{ old('email') }}"
                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors text-sm"
                           placeholder="e.g. john@example.com">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role_id" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        User Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role_id" id="role_id" required
                            class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors text-sm">
                        <option value="" disabled {{ old('role_id') ? '' : 'selected' }}>Select a role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors text-sm"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors text-sm"
                           placeholder="••••••••">
                </div>

                <!-- Image -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-3">
                        Profile Image
                    </label>
                    <div class="relative group w-32 h-32">
                        <img id="image-preview" 
                             src="https://ui-avatars.com/api/?name=New+User&background=f3f4f6&color=a1a1aa"
                             class="w-32 h-32 rounded-2xl border-4 border-white dark:border-gray-700 shadow-md object-cover bg-gray-50 dark:bg-gray-700 transition-all group-hover:brightness-90">
                        
                        <label for="image-upload" class="absolute inset-0 flex items-center justify-center bg-black/40 text-white rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <i class="fa-solid fa-camera text-xl"></i>
                        </label>
                        <input type="file" id="image-upload" name="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <p class="mt-2 text-[10px] text-gray-500 dark:text-gray-400 italic">Recommended: Square image, max 2MB</p>
                    @error('image')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('image-preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
