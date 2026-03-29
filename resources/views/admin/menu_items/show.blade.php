@extends('admin.layouts.app')

@section('title', 'Menu Item Details')

@section('content')
<div class="mx-auto">
    <!-- Main Card -->
    <div class="bg-white shadow-md dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden p-5">
        
        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Menu Item Details</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Viewing information for "{{ $menu_item->name }}"</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.menu_items.edit', $menu_item->id) }}"
                   class=" inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-edit mr-2"></i> Edit Item
                </a>
                <a href="{{ route('admin.menu_items.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium  dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Image + Quick Info --}}
            <div class="lg:col-span-1">
                <div class="bg-gray-50 shadow dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-600 overflow-hidden">
                    @if($menu_item->image)
                        <img src="{{ $menu_item->display_image }}"
                             alt="{{ $menu_item->name }}"
                             class="w-full h-80 object-contain bg-white">
                    @else
                        <img src="{{ asset('assets/img/placeholder.png') }}"
                             class="w-full h-80 object-cover"
                             alt="{{ $menu_item->name }}">
                    @endif

                    <div class="p-6 space-y-5 shadow-md">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold uppercase tracking-widest">Status</span>
                            @if($menu_item->status === 'available' || $menu_item->status == 1 || $menu_item->status === true)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
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
                            <span class="text-sm font-semibold uppercase tracking-widest">Rating & Popularity</span>
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
                            <span class="text-sm font-semibold uppercase tracking-widest">Price</span>
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
                        <div class=" p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-600">
                            <p class="text-sm font-bold mb-1.5">Category :</p>
                            <div class="flex items-center gap-2.5 text-gray-700 dark:text-gray-200">
                                <i class="fas fa-tag text-amber-500"></i>
                                <span class="text-lg font-semibold">{{ $menu_item->category?->name ?? 'Uncategorized' }}</span>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-100 dark:border-gray-600">
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Item Reference</p>
                            <div class="flex items-center gap-2.5 text-gray-700 dark:text-gray-200">
                                <i class="fas fa-hashtag text-amber-500"></i>
                                <span class="text-lg font-semibold">{{ $menu_item->id }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-bold mb-3">Description :</p>
                        <div class="p-5 bg-gray-50 shadow-md dark:bg-gray-700/30 rounded-lg border border-gray-600 dark:border-gray-800">
                            <p class="text-sm  dark:text-gray-300 leading-relaxed">
                                {{ $menu_item->description ?? 'No description provided for this item.' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Stats / Metadata --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4">
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/50 rounded-lg p-4 transition-all hover:shadow-md">
                        <p class="text-[10px] font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-1">Created On</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $menu_item->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/50 rounded-lg p-4 transition-all hover:shadow-md">
                        <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-1">Last Update</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $menu_item->updated_at->diffForHumans() }}</p>
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50 rounded-lg p-4 transition-all hover:shadow-md">
                        <p class="text-[10px] font-bold text-indigo-600 dark:text-indigo-500 uppercase tracking-widest mb-1">Cat. Status</p>
                        <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                            
                         

                            {{-- active green --}}
                            @if ($menu_item->category?->status)
                                <span class="text-green-600 dark:text-green-400">
                                    <i class="fas fa-check text-xs"></i>Active</span>
                            @else
                                <span class="text-red-600 dark:text-red-400">
                                    <i class="fas fa-times text-xs"></i>Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>

            </div>
</div>

{{-- ============================================================ --}}
{{-- Customer Ratings Table                                        --}}
{{-- ============================================================ --}}
<div class="mt-6 bg-white shadow-md dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">

    {{-- Section Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                <i class="fas fa-star text-amber-500 text-sm"></i>
            </span>
            <div>
                <h4 class="text-base font-bold text-gray-900 dark:text-white">Customer Ratings</h4>
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    {{ $menu_item->ratings->count() }} review{{ $menu_item->ratings->count() !== 1 ? 's' : '' }}
                </p>
            </div>
        </div>

        {{-- Average Score Badge --}}
        @if($menu_item->ratings->count() > 0)
        @php $avg = $menu_item->ratings->avg('rating'); @endphp
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-xs text-gray-400 uppercase tracking-wider">Avg. Rating</p>
                <div class="flex items-center justify-end gap-1">
                    @for($s = 1; $s <= 5; $s++)
                        <i class="fas fa-star text-xs {{ $s <= round($avg) ? 'text-amber-400' : 'text-gray-200 dark:text-gray-600' }}"></i>
                    @endfor
                </div>
            </div>
            <div class="w-14 h-14 rounded-full bg-amber-500 flex items-center justify-center shadow-lg">
                <span class="text-white font-black text-lg leading-none">{{ number_format($avg, 1) }}</span>
            </div>
        </div>
        @endif
    </div>

    @if($menu_item->ratings->count() === 0)
    {{-- Empty State --}}
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
            <i class="fas fa-star text-2xl text-gray-300"></i>
        </div>
        <p class="text-gray-500 dark:text-gray-400 font-medium">No ratings yet</p>
        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Ratings from customers will appear here.</p>
    </div>

    @else
    {{-- Ratings Distribution Bar --}}
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700">
        @php
            $total = $menu_item->ratings->count();
            $dist  = $menu_item->ratings->countBy('rating')->sortKeysDesc();
        @endphp
        <div class="flex flex-col gap-1.5 max-w-sm">
            @foreach([5,4,3,2,1] as $star)
            @php $count = $dist->get($star, 0); $pct = $total > 0 ? ($count / $total * 100) : 0; @endphp
            <div class="flex items-center gap-2 text-xs">
                <span class="w-4 text-right font-semibold text-gray-700 dark:text-gray-300">{{ $star }}</span>
                <i class="fas fa-star text-amber-400 text-[10px]"></i>
                <div class="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                    <div class="h-2 rounded-full bg-amber-400 transition-all" style="width: {{ $pct }}%"></div>
                </div>
                <span class="w-6 text-gray-500 dark:text-gray-400">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Order</th>
                    <th class="px-6 py-3">Rating</th>
                    <th class="px-6 py-3">Comment</th>
                    <th class="px-6 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($menu_item->ratings->sortByDesc('created_at') as $i => $rating)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">

                    {{-- Row number --}}
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $i + 1 }}</td>

                    {{-- Customer --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center flex-shrink-0 text-amber-600 font-bold text-xs">
                                {{ strtoupper(substr($rating->customer?->name ?? $rating->customer?->user?->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm leading-tight">
                                    {{ $rating->customer?->name ?? $rating->customer?->user?->name ?? '—' }}
                                </p>
                                <p class="text-xs text-gray-400">{{ $rating->customer?->email ?? '' }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Order --}}
                    <td class="px-6 py-4">
                        @if($rating->order)
                        <a href="{{ route('admin.orders.show', $rating->order->id) }}"
                           class="inline-flex items-center gap-1 text-xs font-mono font-semibold text-amber-600 hover:text-amber-700 hover:underline">
                            <i class="fas fa-receipt text-[10px]"></i>
                            {{ $rating->order->order_no ?? '#'.$rating->order_id }}
                        </a>
                        @else
                        <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>

                    {{-- Stars --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-0.5">
                            @for($s = 1; $s <= 5; $s++)
                            <i class="fas fa-star text-sm {{ $s <= $rating->rating ? 'text-amber-400' : 'text-gray-200 dark:text-gray-600' }}"></i>
                            @endfor
                            <span class="ml-1.5 text-xs font-bold text-gray-700 dark:text-gray-300">{{ $rating->rating }}/5</span>
                        </div>
                    </td>

                    {{-- Comment --}}
                    <td class="px-6 py-4 max-w-xs">
                        @if($rating->comment)
                        <p class="text-gray-600 dark:text-gray-300 text-xs leading-relaxed line-clamp-2"
                           title="{{ $rating->comment }}">
                            "{{ $rating->comment }}"
                        </p>
                        @else
                        <span class="text-gray-300 dark:text-gray-600 text-xs italic">No comment</span>
                        @endif
                    </td>

                    {{-- Date --}}
                    <td class="px-6 py-4 text-xs text-gray-400 whitespace-nowrap">
                        <p>{{ $rating->created_at->format('M d, Y') }}</p>
                        <p class="text-gray-300 dark:text-gray-600">{{ $rating->created_at->diffForHumans() }}</p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
</div>
@endsection