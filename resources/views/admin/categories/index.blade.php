@extends('admin.layouts.app')

@section('title', 'Categories Management')

@section('content')
<div class="mx-auto">
    <!-- Table Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Categories Management</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage food categories and their visibility</p>
            </div>

            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                <i class="fa fa-plus mr-2"></i>
                Add Category
            </a>
        </div>

        {{-- Search and Filter --}}
        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <div class="relative flex-1 sm:min-w-[300px] w-full">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors">
                </div>
                
                <select name="status" onchange="this.form.submit()"
                    class="block w-full sm:w-44 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors px-3 py-2">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit"
                    class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    Search
                </button>
                
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.categories.index') }}"
                        class="w-full sm:w-auto px-3 py-2 text-sm font-medium text-center text-gray-500 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 rounded-lg transition-colors" title="Clear Filters">
                        <i class="fa-solid fa-times sm:mr-0 mr-2"></i>
                        <span class="sm:hidden">Clear Filters</span>
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
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </x-slot>

            {{-- BODY --}}
            <x-slot name="body">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $category->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                               class="text-sm font-medium text-amber-600 hover:text-amber-800 transition-colors">
                                {{ $category->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ Str::limit($category->description ?? '—', 50) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($category->status)
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
                            <a href="{{ route('admin.categories.toggle-status', $category->id) }}"
                               class="{{ action_btn_class($category->status ? 'status_on' : 'status_off') }}"
                               title="{{ $category->status ? 'Deactivate' : 'Activate' }}">
                                <i class="fas text-lg {{ $category->status ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                            </a>

                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                               class="{{ action_btn_class('edit') }}"
                               title="Edit">
                                <i class="{{ action_btn_icon('edit') }}"></i>
                            </a>

                            <button type="button" 
                                onclick="showDeleteModal('{{ route('admin.categories.destroy', $category->id) }}', 'Are you sure you want to delete the category \'{{ $category->name }}\'?')"
                                class="{{ action_btn_class('delete') }}"
                                title="Delete">
                                <i class="fas text-lg fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i class="fa-solid fa-folder-open text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No categories found</p>
                            @if(request('search'))
                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Try searching with different keywords</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table.base-table>

        <!-- Pagination -->
        <div class="pt-4 pb-2 border-t border-gray-200 dark:border-gray-700 text-center sm:text-right">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
