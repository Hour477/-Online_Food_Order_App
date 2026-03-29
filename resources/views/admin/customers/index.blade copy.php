
@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                Customers
            </h1>
            <p class="mt-2  dark:text-gray-400">
                Manage your restaurant customers and contact details
            </p>
        </div>

        <a href="{{ route('admin.customers.create') }}"
           class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg shadow-sm transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500
           ">
            <i class="fas fa-user-plus mr-2"></i>
            Add New Customer
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">

        @if($customers->isEmpty())
            <!-- Empty State -->
            <div class="py-16 text-center">
                <div class="inline-block p-6 bg-gray-50 dark:bg-gray-700/30 rounded-full mb-4">
                    <i class="fas fa-users text-5xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">
                    No customers yet
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                    Start adding your regular customers to keep track of their details and orders.
                </p>
                <a href="{{ route('admin.customers.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition
                   ">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add Your First Customer
                </a>
            </div>
        @else
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Phone
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($customers as $customer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $customer->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm  dark:text-gray-300">
                                    {{ $customer->email ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm  dark:text-gray-300">
                                    {{ $customer->phone ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete {{ addslashes($customer->name) }}? This cannot be undone.')"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination (if you use ->paginate() in controller) -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 text-center sm:text-right">
                {{ $customers->links() }}
            </div>
        @endif

    </div>

</div>

@endsection