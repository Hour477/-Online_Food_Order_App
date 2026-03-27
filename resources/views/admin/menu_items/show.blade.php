@extends('admin.layouts.app')

@section('title', 'Menu Item Details')

@section('content')
<div class="mx-auto">
    <!-- Main Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">
        
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Menu Item Details</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Viewing information for "{{ $menu_item->name }}"</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.menu_items.edit', $menu_item->id) }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-edit mr-2"></i> Edit Item
                </a>
                <a href="{{ route('admin.menu_items.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Image + Quick Info --}}
            <div class="lg:col-span-1">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-100 dark:border-gray-600 overflow-hidden">
                    @if($menu_item->image)
                        <img src="{{ $menu_item->display_image }}"
                             alt="{{ $menu_item->name }}"
                             class="w-full h-64 object-cover">
                    @else
                        <div class="w-full h-64 bg-gray-100 dark:bg-gray-700 flex flex-col items-center justify-center text-gray-300 dark:text-gray-500">
                            <i class="fas fa-image text-5xl mb-3"></i>
                            <span class="text-sm font-medium">No image available</span>
                        </div>
                    @endif

                    <div class="p-6 space-y-5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Status</span>
                            @if($menu_item->status === 'available' || $item->status == 1 || $item->status === true)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Available
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Unavailable
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Rating & Popularity</span>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1 px-2 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                                    <i class="fas fa-star text-xs"></i>
                                    <span class="text-xs font-bold">{{ number_format($menu_item->rating, 1) }}</span>
                                </div>
                                <div class="flex items-center gap-1 px-2 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                                    <i class="fas fa-fire text-xs"></i>
                                    <span class="text-xs font-bold">{{ $menu_item->popularity }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-600 pt-4">
                            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Price</span>
                            <span class="text-3xl font-black text-emerald-600 dark:text-emerald-400">
                                ${{ number_format($menu_item->price, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Details Section --}}
            <div class="lg:col-span-2 space-y-8">
                
                <div class="space-y-6">
                    <div>
                        <p class="text-xs font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-2">Item Information</p>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white leading-tight">{{ $menu_item->name }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-100 dark:border-gray-600">
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Category</p>
                            <div class="flex items-center gap-2.5 text-gray-700 dark:text-gray-200">
                                <i class="fas fa-tag text-amber-500"></i>
                                <span class="text-lg font-semibold">{{ $menu_item->category?->name ?? 'Uncategorized' }}</span>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-100 dark:border-gray-600">
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Item Reference</p>
                            <div class="flex items-center gap-2.5 text-gray-700 dark:text-gray-200">
                                <i class="fas fa-hashtag text-amber-500"></i>
                                <span class="text-lg font-semibold">#{{ str_pad($menu_item->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3">Description</p>
                        <div class="p-5 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed italic">
                                "{{ $menu_item->description ?? 'No description provided for this item.' }}"
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Stats / Metadata --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4">
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/50 rounded-xl p-4 transition-all hover:shadow-md">
                        <p class="text-[10px] font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-1">Created On</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $menu_item->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/50 rounded-xl p-4 transition-all hover:shadow-md">
                        <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-1">Last Update</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $menu_item->updated_at->diffForHumans() }}</p>
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50 rounded-xl p-4 transition-all hover:shadow-md">
                        <p class="text-[10px] font-bold text-indigo-600 dark:text-indigo-500 uppercase tracking-widest mb-1">Cat. Status</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                            {{ $menu_item->category?->status ? 'Active' : 'Inactive' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection