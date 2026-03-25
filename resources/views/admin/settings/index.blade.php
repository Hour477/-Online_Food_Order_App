@extends('admin.layouts.app')

@section('title', 'Business Settings')

@section('content')
<div class="mx-auto">
    
    {{-- Form Card --}}
   <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">
        {{-- Header --}}
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Business Settings</h3>
                <p class="text-sm text-gray-500 mt-1">Update your restaurant information and branding</p>
            </div>
        <form action="{{ route('admin.settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Restaurant Name --}}
                <div class="mb-2">
                    <label for="resturant_name" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Restaurant Name
                    </label>
                    <input type="text" name="resturant_name" id="resturant_name"
                           value="{{ old('resturant_name', $settings['resturant_name'] ?? '') }}"
                           placeholder="e.g. My Restaurant"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                  px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                </div>

                {{-- Restaurant Phone --}}
                <div class="mb-2">
                    <label for="resturant_phone" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Phone
                    </label>
                    <input type="text" name="resturant_phone" id="resturant_phone"
                           value="{{ old('resturant_phone', $settings['resturant_phone'] ?? '') }}"
                           placeholder="e.g. +1 234 567 890"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                  px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                </div>

                {{-- Restaurant Email --}}
                <div class="mb-2">
                    <label for="resturant_email" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Email
                    </label>
                    <input type="email" name="resturant_email" id="resturant_email"
                           value="{{ old('resturant_email', $settings['resturant_email'] ?? '') }}"
                           placeholder="e.g. info@restaurant.com"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                  px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                </div>

                {{-- Restaurant Address --}}
                <div class="mb-2 md:col-span-2">
                    <label for="resturant_address" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Address
                    </label>
                    <textarea name="resturant_address" id="resturant_address" rows="3"
                              placeholder="Enter full address"
                              class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400
                                     px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors resize-none">{{ old('resturant_address', $settings['resturant_address'] ?? '') }}</textarea>
                </div>

                {{-- Logo --}}
                <div class="mb-2">
                    <label for="logo" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Logo
                    </label>
                    <input type="file" name="logo" id="logo" accept="image/*"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 
                                  px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors
                                  file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold
                                  file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    @if(!empty($settings['logo']))
                        <div class="mt-3 flex items-center gap-3">
                            <img src="{{ Storage::url($settings['logo']) }}" alt="Logo" 
                                 class="h-14 w-14 rounded-lg border border-gray-200 object-contain bg-gray-50">
                            <span class="text-xs text-gray-400">Current logo</span>
                        </div>
                    @endif
                </div>

                {{-- Favicon --}}
                <div class="mb-2">
                    <label for="favicon" class="block text-xs font-medium text-gray-500 uppercase tracking-widest mb-1.5">
                        Favicon
                    </label>
                    <input type="file" name="favicon" id="favicon" accept="image/*"
                           class="block w-full rounded-lg border border-gray-200 bg-gray-50 text-gray-900 
                                  px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors
                                  file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold
                                  file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    @if(!empty($settings['favicon']))
                        <div class="mt-3 flex items-center gap-3">
                            <img src="{{ Storage::url($settings['favicon']) }}" alt="Favicon" 
                                 class="h-10 w-10 rounded-lg border border-gray-200 object-contain bg-gray-50">
                            <span class="text-xs text-gray-400">Current favicon</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-8 mt-8 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
