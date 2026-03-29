@extends('admin.layouts.app')

@section('title', 'Menu Items Management')

@section('content')
<div class="mx-auto">
    <!-- Table Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Menu Items Management</h3>
                <p class="text-lg    mt-1">Manage dishes, pricing, and availability</p>
            </div>

            <a href="{{ route('admin.menu_items.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 text-lg  font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                <i class="fa fa-plus mr-2"></i>
                Add Menu Item
            </a>
        </div>

        {{-- Search and Filter --}}
        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('admin.menu_items.index') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:min-w-[300px] w-full">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                        class="w-full pl-9 pr-4 py-2 text-lg  border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors">
                </div>
                
              



                <button type="submit"
                    class="w-full sm:w-auto px-4 py-2 text-lg  font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    Search
                </button>
                 @if(request('search') || request('status'))
                    <a href="{{ route('admin.menu_items.index') }}"
                        class="w-full sm:w-auto px-3 py-2 text-lg  font-medium text-center  bg-gray-100 hover:bg-gray-200 dark:bg-gray-700  dark:hover:bg-gray-600 rounded-lg transition-colors" title="Clear Filters">
                        <i class="fa-solid fa-times sm:mr-0 mr-2"></i>
                        <span class="sm:hidden">Clear Filters</span>
                    </a>
                @endif
               
                  <select name="status" onchange="this.form.submit()"
                    class="block w-full sm:min-w-[200px] sm:w-44 text-lg  text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors px-4 py-3"> 
                    
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>

                
                
               
            </form>
        </div>

        {{-- TABLE --}}
        <x-table.base-table>
            {{-- HEADER --}}
            <x-slot name="head">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium   uppercase tracking-wider">#</th>
                    <th class="px-6 py-4 text-left text-xs font-medium   uppercase tracking-wider">Image</th>
                    <th class="px-6 py-4 text-left text-xs font-medium   uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium   uppercase tracking-wider">Category active</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium   uppercase tracking-wider">Rating</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium   uppercase tracking-wider">Price</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-medium   uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-medium   uppercase tracking-wider">Actions</th>
                </tr>
            </x-slot>

            {{-- BODY --}}
            <x-slot name="body">
                @forelse($menu_items as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.menu_items.show', $item->id) }}">
                                @if ($item->image)
                                    <img src="{{ $item->display_image}}"
                                         alt="{{ $item->name }}"
                                         class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                @else
                                    <img src="{{ asset('assets/img/placeholder.png') }}"
                                         alt="{{ $item->name }}"
                                         class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                   
                                @endif
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.menu_items.show', $item->id) }}"
                               class="text-lg  font-medium text-amber-600 hover:text-amber-800 transition-colors">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg   ">
                            {{-- name Category active --}}
                            {{ $item->category?->name ?? '—' }}
                            <br>
                            @if ($item->category?->status)
                                <span class="text-green-600 dark:text-green-400">
                                    <i class="fas fa-check text-xs"></i>Active</span>
                            @else
                                <span class="text-red-600 dark:text-red-400">
                                    <i class="fas fa-times text-xs"></i>Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-star text-amber-400 text-xs"></i>
                                <span class="text-lg  font-bold text-gray-700 dark:text-gray-300">{{ number_format($item->rating, 1) }}</span>
                                <span class="text-[10px] text-gray-400">({{ $item->popularity }})</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg  font-bold text-emerald-600 dark:text-emerald-400">
                            ${{ number_format($item->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status === 'available' || $item->status == 1 || $item->status === true)
                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                    Available
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-600">
                                    Unavailable
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-lg  font-medium space-x-1">
                            <a href="{{ route('admin.menu_items.show', $item->id) }}"
                               class="{{ action_btn_class('view') }}"
                               title="View">
                                <i class="{{ action_btn_icon('view') }}"></i>
                            </a>

                            <a href="{{ route('admin.menu_items.edit', $item->id) }}"
                               class="{{ action_btn_class('edit') }}"
                               title="Edit">
                                <i class="{{ action_btn_icon('edit') }}"></i>
                            </a>

                            <button type="button" 
                                onclick="showDeleteModal('{{ route('admin.menu_items.destroy', $item->id) }}', 'Are you sure you want to delete the menu item \'{{ $item->name }}\'?')"
                                class="{{ action_btn_class('delete') }}"
                                title="Delete">
                                <i class="{{ action_btn_icon('delete') }}"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fa-solid fa-utensils text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <p class="  text-lg">No menu items found</p>
                            @if(request('search'))
                                <p class="text-gray-400 dark: text-lg  mt-2">Try searching with different keywords</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table.base-table>

        <!-- Pagination -->
        <div class="pt-4 pb-2 border-t border-gray-200 dark:border-gray-700 text-center sm:text-right">
            {{ $menu_items->links() }}
        </div>
    </div>
</div>
@endsection
