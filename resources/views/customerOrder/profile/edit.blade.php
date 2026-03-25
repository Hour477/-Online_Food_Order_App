@extends('customerOrder.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        {{-- Header / Cover --}}
        <div class="h-32 bg-gradient-to-r from-amber-500 to-amber-600"></div>
        
        <form action="{{ route('customerOrder.profile.update') }}" method="POST" enctype="multipart/form-data" class="px-8 pb-8">
            @csrf
            @method('PUT')

            <div class="relative flex justify-between items-end -mt-16 mb-10">
                {{-- Profile Image Upload --}}
                <div class="relative group">
                    <img id="image-preview" 
                         src="{{ $user->display_image }}" 
                         alt="{{ $user->name }}" 
                         class="w-32 h-32 rounded-2xl border-4 border-white shadow-md object-cover bg-gray-50 group-hover:brightness-90 transition">
                    
                    <label for="image-upload" class="absolute inset-0 flex items-center justify-center bg-black/40 text-white rounded-2xl opacity-0 group-hover:opacity-100 transition cursor-pointer">
                        <i class="fa-solid fa-camera text-xl"></i>
                    </label>
                    <input type="file" id="image-upload" name="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                </div>

                <div class="flex gap-3 pb-2">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-8 py-2.5 bg-amber-600 text-white rounded-xl font-bold shadow-lg shadow-amber-200 hover:bg-amber-700 transition transform hover:-translate-y-0.5 active:scale-95">
                        <i class="fa-solid fa-check"></i>
                        {{ __('app.save_changes') }}
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Basic Info --}}
                <div class="space-y-6">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-4 border-b border-gray-100 pb-2">
                        <i class="fa-solid fa-user text-amber-600"></i>
                        {{ __('app.basic_info') }}
                    </h2>
                    
                    <div>
                        <label for="name" class="block text-[15px] uppercase tracking-wider text-gray-400 font-bold mb-1 ml-1">{{ __('app.name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-300 @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-[15px] uppercase tracking-wider text-gray-400 font-bold mb-1 ml-1">{{ __('app.email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-300 @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-[15px] uppercase tracking-wider text-gray-400 font-bold mb-1 ml-1">{{ __('app.phone') }}</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-300 @error('phone') border-red-500 @enderror">
                        @error('phone') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Security --}}
                <div class="space-y-6">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-4 border-b border-gray-100 pb-2">
                        <i class="fa-solid fa-lock text-amber-600"></i>
                        {{ __('app.security') }}
                    </h2>

                    <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl mb-6">
                        <p class="text-xs text-amber-700 leading-relaxed">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            {{ __('app.password_hint') }}
                        </p>
                    </div>

                    <div>
                        <label for="password" class="block text-[15px] uppercase tracking-wider text-gray-400 font-bold mb-1 ml-1">{{ __('app.new_password') }}</label>
                        <input type="password" name="password" id="password"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-300 @error('password') border-red-500 @enderror">
                        @error('password') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[15px] uppercase tracking-wider text-gray-400 font-bold mb-1 ml-1">{{ __('app.confirm_password') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition duration-300">
                    </div>
                </div>
            </div>
            
            <div class="mt-12 flex justify-center">
                <a href="{{ route('customerOrder.profile.show') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition">
                    {{ __('app.cancel_and_go_back') }}
                </a>
            </div>
        </form>
    </div>
</div>

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
@endsection
