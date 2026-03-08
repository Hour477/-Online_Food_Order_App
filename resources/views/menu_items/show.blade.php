
@extends('layouts.app')

@section('content')
<div class="py-8">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                Menu Item Details
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Viewing information for {{ $menu_item->name }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('menu_items.edit', $menu_item->id) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                <i class="fas fa-edit mr-2"></i> Edit Item
            </a>
            <a href="{{ route('menu_items.index') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Image Section -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if($menu_item->image)
                    <img src="{{ asset('storage/' . $menu_item->image) }}" 
                         alt="{{ $menu_item->name }}" 
                         class="w-full h-64 object-cover">
                @else
                    <div class="w-full h-64 bg-gray-100 dark:bg-gray-700 flex flex-col items-center justify-center text-gray-400">
                        <i class="fas fa-image text-5xl mb-3"></i>
                        <span class="text-sm">No image available</span>
                    </div>
                @endif
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</span>
                        @if($menu_item->status === 'available' || $menu_item->status == 1)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <span class="w-2 h-2 mr-2 rounded-full bg-green-500"></span>
                                Available
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                <span class="w-2 h-2 mr-2 rounded-full bg-red-500"></span>
                                Unavailable
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</span>
                        <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            ${{ number_format($menu_item->price, 2) }}
                        </span>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Details Section -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <div class="mb-8">
                    <h4 class="text-sm font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Item Name</h4>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $menu_item->name }}</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Category</h4>
                        <div class="flex items-center text-gray-700 dark:text-gray-300">
                            <i class="fas fa-tag mr-2 text-indigo-500"></i>
                            <span class="text-lg font-medium">{{ $menu_item->category?->name ?? 'Uncategorized' }}</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Item ID</h4>
                        <div class="flex items-center text-gray-700 dark:text-gray-300">
                            <i class="fas fa-hashtag mr-2 text-indigo-500"></i>
                            <span class="text-lg font-medium">#{{ str_pad($menu_item->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Description</h4>
                    <div class="prose dark:prose-invert text-gray-700 dark:text-gray-300">
                        {{ $menu_item->description ?? 'No description available' }}
                    </div>
                </div>            
            </div>

            <!-- Additional Info / Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-5 rounded-2xl border border-indigo-100 dark:border-indigo-800/50">
                    <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1">Created At</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $menu_item->created_at->format('M d, Y') }}</p>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-5 rounded-2xl border border-emerald-100 dark:border-emerald-800/50">
                    <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-1">Last Updated</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $menu_item->updated_at->diffForHumans() }}</p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/20 p-5 rounded-2xl border border-amber-100 dark:border-amber-800/50">
                    <p class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">Category Status</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $menu_item->category?->status ? 'Active' : 'Inactive' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
