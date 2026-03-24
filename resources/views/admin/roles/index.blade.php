@extends('admin.layouts.app')

@section('content')
<div class="mx-auto">
    <!-- Table Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">

        {{-- Title --}}
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 ">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Roles Management
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Define and manage system access levels
                </p>
            </div>

            <a href="{{ route('admin.roles.create') }}"
                class="inline-flex w-[150px] justify-center items-center px-4 py-3 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                {{-- shield_person --}}
                <i class="fa-solid fa-user-shield mr-2"></i>
                Add Role
            </a>
        </div>



    <div class="mb-2 bg-white dark:bg-gray-800 rounded-xl border-gray-200 dark:border-gray-700 justify-items-start">
        <form method="GET" action="{{ route('admin.roles.index') }}" class="flex flex-col-2 sm:flex-row gap-3">
            <div class="flex relative ">
                {{-- <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i> --}}
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                    class="min-w-[20px] max-w-[300px]  pl-4 pr-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-50 focus:border-transparent">
            </div>
            <button type="submit"
                class="min-w-[50px] max-w-[50px]  px-4 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors ">
                <i class="fa-solid fa-search "></i>
            </button>
            @if(request('search'))
            <a href="{{ route('admin.roles.index') }}"
                class=" inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                <i class="fa-solid fa-times"></i>
            </a>
            @endif
        </form>

        {{--  --}}
    </div>
        {{-- TABLE --}}
       <x-table.base-table>

    {{-- HEADER --}}
    <x-slot name="head">
        <tr>
            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Slug</th>
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
        </tr>
    </x-slot>

    {{-- BODY --}}
    <x-slot name="body">
        @forelse ($roles as $role)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1.5 inline-flex items-center gap-1.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                        {{ $role->name}}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ $role->slug }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ $role->description ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.roles.show', $role->id) }}" class="{{ action_btn_class('show') }}" title="View">
                            <i class="{{ action_btn_icon('show') }}"></i>
                        </a>

                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="{{ action_btn_class('edit') }}" title="Edit">
                            <i class="{{ action_btn_icon('edit') }}"></i>
                        </a>

                        <button type="button" 
                            onclick="showDeleteModal('{{ route('admin.roles.destroy', $role->id) }}', 'Are you sure you want to delete the role \'{{ $role->name }}\'?')"
                            class="{{ action_btn_class('delete') }}" title="Delete">
                            <i class="{{ action_btn_icon('delete') }}"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <i class="fa-solid fa-users-slash text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No roles found</p>
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
            {{ $roles->links() }}
        </div>
    </div>

</div>
@endsection