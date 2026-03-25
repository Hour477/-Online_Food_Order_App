@extends('admin.layouts.app')

@section('title', 'Banners Management')

@section('content')
<div class="mx-auto">
    <!-- Table Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Banners Management</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage promotional banners and advertisements</p>
            </div>

            <a href="{{ route('admin.banners.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                <i class="fa fa-plus mr-2"></i>
                Add Banner
            </a>
        </div>

        {{-- Search and Filter --}}
        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('admin.banners.index') }}" class="flex items-center gap-2">
                <div class="relative flex-1 sm:min-w-[300px]">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors">
                </div>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.banners.index') }}"
                        class="px-3 py-2 text-sm font-medium text-gray-500 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 rounded-lg transition-colors" title="Clear Search">
                        <i class="fa-solid fa-times"></i>
                    </a>
                @endif
            </form>
        </div>
        {{-- TABLE --}}
       <x-table.base-table>

    {{-- HEADER --}}
    <x-slot name="head">
        <tr>
            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Image</th>
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
        </tr>
    </x-slot>

    {{-- BODY --}}
    <x-slot name="body">
        @forelse ($banners as $banner)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <a href="{{ route('admin.banners.edit', $banner->id) }}">
                         <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title ?? 'Banner' }}"
                                 class="w-20 h-12 object-cover rounded-lg border border-gray-200">
                    </a>
                </td>
              
                <td class="px-6 py-4 whitespace-nowrap">
                   {{ Str::limit($banner->title ?? 'N/A', 30) }}
                </td>
                 <td class="px-6 py-4 whitespace-nowrap">
                            @if($banner->is_active)
                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-600">
                                    Inactive
                                </span>
                            @endif
                        </td>

                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.banners.toggle-status', $banner->id) }}"
                               class="{{ action_btn_class($banner->is_active ? 'status_on' : 'status_off') }}"
                               title="{{ $banner->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="fas text-lg {{ $banner->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                            </a>

                            <a href="{{ route('admin.banners.edit', $banner->id) }}"
                               class="{{ action_btn_class('edit') }}"
                               title="Edit">
                                <i class="{{ action_btn_icon('edit') }}"></i>
                            </a>

                            <button type="button" 
                                onclick="showDeleteModal('{{ route('admin.banners.destroy', $banner->id) }}', 'Are you sure you want to delete the banner \'{{ $banner->title ?? 'Untitled' }}\'?')"
                                class="{{ action_btn_class('delete') }}"
                                title="Delete">
                                <i class="fas text-lg fa-trash"></i>
                    
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <i class="fa-solid fa-users-slash text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No banners found</p>
                    @if(request('search'))
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Try searching with different keywords</p>
                    @endif
                </td>
            </tr>
        @endforelse
    </x-slot>

</x-table.base-table>


        <!-- Pagination (if you use ->paginate() in controller) -->
        <div class="pt-4 pb-2 border-t border-gray-200 dark:border-amber-700 text-center sm:text-right">
            {{ $banners->links() }}
        </div>
    </div>

</div>
@endsection
