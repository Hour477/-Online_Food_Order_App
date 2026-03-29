
@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="mx-auto">

    @if(!$customer)
    {{-- Customer not found --}}
    <div class="text-center py-24 bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
        <i class="fa-solid fa-circle-exclamation text-6xl text-red-400 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">User Not Found</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">The user you're trying to edit doesn't exist or has been deleted.</p>
        <a href="{{ route('admin.users.index') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Customers
        </a>
    </div>
    @else

    {{-- Edit Customer --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">
        
        <!-- Header -->
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Customer: {{ $customer->name }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update customer information</p>
        </div>

        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required autofocus
                           value="{{ old('name', $customer->name) }}"
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
                           value="{{ old('email', $customer->email) }}"
                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors text-sm"
                           placeholder="e.g. customer@example.com">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        New Password (optional)
                    </label>
                    <input type="password" name="password" id="password"
                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors text-sm"
                           placeholder="Leave empty to keep current password">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Confirm New Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors text-sm"
                           placeholder="Confirm new password">
                </div>

                <!-- Image -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-3">
                        Profile Image
                    </label>
                    <div class="relative group w-32 h-32">
                        <img id="image-preview" 
                             src="{{ $customer->display_image }}"
                             class="w-32 h-32 rounded-2xl border-4 border-white dark:border-gray-700 shadow-md object-cover bg-gray-50 dark:bg-gray-700 transition-all group-hover:brightness-90">
                        
                        <label for="image-upload" class="absolute inset-0 flex items-center justify-center bg-black/40 text-white rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <i class="fa-solid fa-camera text-xl"></i>
                        </label>
                        <input type="file" id="image-upload" name="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <p class="mt-2 text-[10px] text-gray-500 dark:text-gray-400 italic">Recommended: Square image, max 2MB. Leave empty to keep current.</p>
                    @error('image')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.customers.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-save mr-2"></i>
                    Update Customer
                </button>
            </div>
        </form>
    </div>
    @endif
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
