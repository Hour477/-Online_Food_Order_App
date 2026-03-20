@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Menu Item Details</h3>
            <p class="text-sm text-gray-500 mt-1">Viewing information for {{ $menu_item->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.menu_items.edit', $menu_item->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white
                      bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                <i class="fas fa-edit text-sm"></i> Edit Item
            </a>
            <a href="{{ route('admin.menu_items.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600
                      bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                <i class="fas fa-arrow-left text-sm"></i> Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Image + Quick Info --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if($menu_item->image)
                    <img src="{{ asset('storage/' . $menu_item->image) }}"
                         alt="{{ $menu_item->name }}"
                         class="w-full h-56 object-cover">
                @else
                    <div class="w-full h-56 bg-gray-100 flex flex-col items-center justify-center text-gray-300">
                        <i class="fas fa-image text-4xl mb-2"></i>
                        <span class="text-sm">No image available</span>
                    </div>
                @endif

                <div class="p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-widest">Status</span>
                        @if($menu_item->status === 'available' || $menu_item->status == 1)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Available
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Unavailable
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-widest">Price</span>
                        <span class="text-2xl font-bold text-emerald-600">
                            ${{ number_format($menu_item->price, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                <div class="mb-6">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-1">Item Name</p>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $menu_item->name }}</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-1.5">Category</p>
                        <div class="flex items-center gap-2 text-gray-700">
                            <i class="fas fa-tag text-amber-500 text-sm"></i>
                            <span class="text-base font-medium">{{ $menu_item->category?->name ?? 'Uncategorized' }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-1.5">Item ID</p>
                        <div class="flex items-center gap-2 text-gray-700">
                            <i class="fas fa-hashtag text-amber-500 text-sm"></i>
                            <span class="text-base font-medium">#{{ str_pad($menu_item->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-1.5">Description</p>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $menu_item->description ?? 'No description available.' }}
                    </p>
                </div>

            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-widest mb-1">Created At</p>
                    <p class="text-sm font-medium text-gray-900">{{ $menu_item->created_at->format('M d, Y') }}</p>
                </div>
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-widest mb-1">Last Updated</p>
                    <p class="text-sm font-medium text-gray-900">{{ $menu_item->updated_at->diffForHumans() }}</p>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">Category Status</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ $menu_item->category?->status ? 'Active' : 'Inactive' }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection