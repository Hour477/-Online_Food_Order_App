@extends('admin.layouts.app')


@section('title' , 'Customer Management')


@section('content')
<div class="mx-auto">
    <!-- Table Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden p-5">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Customer Management</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage customers and their contact details</p>
            </div>

            <a href="{{ route('admin.customers.create') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                <i class="fas fa-user-plus mr-2"></i>
                Add Customer
            </a>
        </div>

        {{-- Search and Filter --}}
        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-2">
                <div class="relative flex-1 sm:min-w-[300px]">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-colors">
                </div>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors shadow-sm">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.customers.index') }}"
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
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email & Phone</th>
            
            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
        </tr>
    </x-slot>

    {{-- BODY --}}
    <x-slot name="body">
        @forelse ($customers as $customer)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <a href="{{ route('admin.customers.show', $customer->id) }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12">
                                <img src="{{ $customer->user?->display_image ?? asset('assets/img/placeholder.png') }}" alt="{{ $customer->name }}" class="w-full h-full rounded-full object-cover border-amber-600">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    {{ $customer->name }}
                                    @if($customer->trashed())
                                        <span class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded bg-red-100 text-red-600 uppercase tracking-tighter">Deleted</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <i class="fa-solid fa-envelope text-gray-400 mr-2 text-xs"></i>
                        <span class="text-sm  dark:text-gray-300">{{ $customer->email }}</span>
                    </div>
                    @if($customer->phone)
                        <div class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-phone mr-1.5"></i>{{ $customer->phone }}
                        </div>
                    @endif
                </td>

               

                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="{{ action_btn_class('show') }}" title="View">
                            <i class="{{ action_btn_icon('show') }}"></i>
                        </a>

                        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="{{ action_btn_class('edit') }}" title="Edit">
                            <i class="{{ action_btn_icon('edit') }}"></i>
                        </a>

                        <button type="button" 
                            onclick="showDeleteModal('{{ route('admin.customers.destroy', $customer->id) }}', 'Are you sure you want to delete the customer \'{{ $customer->name }}\'?')"
                            class="{{ action_btn_class('delete') }}" 
                            title="Delete">
                            <i class="{{ action_btn_icon('delete') }}"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <i class="fa-solid fa-users-slash text-5xl text-gray-300 dark: mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No customers found</p>
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
            {{ $customers->links() }}
        </div>
    </div>

</div>
@endsection

