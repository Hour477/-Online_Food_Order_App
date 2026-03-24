@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <h3 class="text-2xl font-bold text-gray-900">Banners</h3>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

            {{-- Search --}}
            <form action="{{ route('admin.banners.index') }}" method="GET"
                  class="relative flex-1 sm:min-w-[280px]">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search banners…"
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
                    class="block w-full sm:w-36 text-sm text-gray-900 bg-white border border-gray-200 rounded-lg shadow-sm
                           px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors">
                <option value="{{ route('admin.banners.index', ['status' => '', 'search' => request('search')]) }}">All Status</option>
                <option value="{{ route('admin.banners.index', ['status' => 'active',   'search' => request('search')]) }}" {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                <option value="{{ route('admin.banners.index', ['status' => 'inactive', 'search' => request('search')]) }}" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            {{-- Add Button --}}
            <a href="{{ route('admin.banners.create') }}"
               class="{{ btn_primary_class() }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Banner
            </a>

        </div>
    </div>

    {{-- Table Card use x-data --}}
    
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($banners as $banner)
                    <tr class="hover:bg-gray-50 transition-colors duration-100">

                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            #{{ $banner->id }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title ?? 'Banner' }}"
                                 class="w-20 h-12 object-cover rounded-lg border border-gray-200">
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $banner->title ?? '—' }}
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

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-1">
                            {{-- Toggle Status --}}
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
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-gray-400">No banners found.</p>
                            <a href="{{ route('admin.banners.create') }}"
                               class="mt-2 inline-block text-sm text-amber-600 hover:text-amber-700 transition-colors">
                                Create your first banner →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $banners->links() }}
        </div>

    </div>
</div>

@endsection
