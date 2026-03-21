@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <h3 class="text-2xl font-bold text-gray-900">Menu Items</h3>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

            {{-- Search --}}
            <form action="{{ route('admin.menu_items.index') }}" method="GET"
                  class="relative flex-1 sm:min-w-[280px]">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search menu items…"
                       class="w-full pl-9 pr-16 py-2.5 text-sm text-gray-900 placeholder-gray-400
                              bg-white border border-gray-200 rounded-lg shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                <button type="submit"
                        class="absolute inset-y-0 right-0 px-3 text-sm font-medium text-amber-600 hover:text-amber-700 transition-colors">
                    Search
                </button>
            </form>

            {{-- Status Filter --}}
            <select onchange="window.location.href=this.value"
                    class="block w-full sm:w-40 text-sm text-gray-900 bg-white border border-gray-200 rounded-lg shadow-sm
                           px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                <option value="{{ route('admin.menu_items.index', ['status' => '',            'search' => request('search')]) }}">All Status</option>
                <option value="{{ route('admin.menu_items.index', ['status' => 'available',   'search' => request('search')]) }}" {{ request('status') == 'available'   ? 'selected' : '' }}>Available</option>
                <option value="{{ route('admin.menu_items.index', ['status' => 'unavailable', 'search' => request('search')]) }}" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>

            {{-- Add Button --}}
            <a href="{{ route('admin.menu_items.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white
                      bg-amber-600 hover:bg-amber-700 rounded-lg shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Menu Item
            </a>

        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($menu_items as $item)
                    <tr class="hover:bg-gray-50 transition-colors duration-100">

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            <a href="{{ route('admin.menu_items.show', $item->id) }}"
                               class="hover:text-amber-600 transition-colors">
                                #{{ $item->id }}
                            </a>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.menu_items.show', $item->id) }}">
                                {{-- @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}"
                                         alt="{{ $item->name }}"
                                         class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-sm"></i>
                                    </div>
                                @endif --}}
                                @if ($item->image)
                                    <img src="{{ $item->display_image }}"
                                         alt="{{ $item->name }}"
                                         class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-sm"></i>
                                    </div>
                                @endif
                            </a>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.menu_items.show', $item->id) }}"
                               class="text-sm font-medium text-amber-600 hover:text-amber-800 transition-colors">
                                {{ $item->name }}
                            </a>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->category?->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-emerald-600">
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

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            {{-- View --}}
                            <a href="{{ route('admin.menu_items.show', $item->id) }}"
                               class=" inline-flex items-center justify-center w-8 h-8 rounded-lg text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors"
                               title="View">
                                <i class="fa-regular fa-eye text-sm"></i>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('admin.menu_items.edit', $item->id) }}"
                               class=" inline-flex items-center justify-center w-8 h-8 rounded-lg text-amber-600 hover:bg-amber-50 hover:text-amber-700 transition-colors"
                               title="Edit">
                                <i class="fa-regular fa-pen-to-square text-sm"></i>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('admin.menu_items.destroy', $item->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete this menu item? This cannot be undone.')"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors"
                                        title="Delete">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-14 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18M9 7v10M15 7v10M4 21h16a1 1 0 001-1V6a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                            </svg>
                            <p class="text-sm text-gray-400">No menu items found.</p>
                            <a href="{{ route('admin.menu_items.create') }}"
                               class="mt-2 inline-block text-sm text-amber-600 hover:text-amber-700 transition-colors">
                                Add your first menu item →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $menu_items->links() }}
        </div>
    </div>

</div>

@endsection