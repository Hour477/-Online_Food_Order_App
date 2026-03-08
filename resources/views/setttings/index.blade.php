
@extends('layouts.app')

@section('content')
<div class="mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Business Settings</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Update your restaurant information and branding.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <form action="{{ route('settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="resturant_name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Restaurant Name</label>
                <input
                    id="resturant_name"
                    name="resturant_name"
                    type="text"
                    value="{{ old('resturant_name', $settings['resturant_name'] ?? '') }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="resturant_phone" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input
                        id="resturant_phone"
                        name="resturant_phone"
                        type="text"
                        value="{{ old('resturant_phone', $settings['resturant_phone'] ?? '') }}"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </div>

                <div>
                    <label for="resturant_email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input
                        id="resturant_email"
                        name="resturant_email"
                        type="email"
                        value="{{ old('resturant_email', $settings['resturant_email'] ?? '') }}"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                </div>
            </div>

            <div>
                <label for="resturant_address" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                <textarea
                    id="resturant_address"
                    name="resturant_address"
                    rows="3"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                >{{ old('resturant_address', $settings['resturant_address'] ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="logo" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
                    <input id="logo" name="logo" type="file" class="block w-full text-sm text-gray-700 dark:text-gray-300">
                    @if(!empty($settings['logo']))
                        <img src="{{ asset('storage/settings/' . $settings['logo']) }}" alt="Logo" class="mt-2 h-14 w-14 rounded border object-contain">
                    @endif
                </div>

                <div>
                    <label for="favicon" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Favicon</label>
                    <input id="favicon" name="favicon" type="file" class="block w-full text-sm text-gray-700 dark:text-gray-300">
                    @if(!empty($settings['favicon']))
                        <img src="{{ asset('storage/settings/' . $settings['favicon']) }}" alt="Favicon" class="mt-2 h-10 w-10 rounded border object-contain">
                    @endif
                </div>
            </div>

            <div>
                <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-white hover:bg-indigo-700">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
