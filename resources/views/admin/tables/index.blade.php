@extends('admin.layouts.app')

@section('content')

<div class="mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                Tables
            </h1>
                
        </div>
       
        <a href="{{ route('admin.tables.create') }}"
           class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i>
            Add New Table
        </a>
        
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">

        @if($tables->isEmpty())
            <!-- Empty State -->
            <div class="py-16 text-center">
                <div class="inline-block p-6 bg-gray-50 dark:bg-gray-700/30 rounded-full mb-4">
                    <i class="fas fa-chair text-5xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">
                    No tables found
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    You haven't added any tables yet.
                </p>
                <a href="{{ route('admin.tables.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                    <i class="fas fa-plus mr-2"></i>
                    Add Your First Table
                </a>
            </div>
        @else
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Table No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Capacity
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($tables as $table)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    Table {{ $table->table_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $table->capacity }} Pax
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $table->status === 'available' ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : '' }}
                                        {{ $table->status === 'occupied' ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' : '' }}
                                        {{ $table->status === 'reserved' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300' : '' }}">
                                        {{ ucfirst($table->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    <a href="{{ route('admin.tables.edit', $table->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete Table {{ $table->table_number }}? This action cannot be undone.')"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination (if you use paginate in controller) -->
            {{-- <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $tables->links() }}
            </div> --}}
        @endif

    </div>

</div>

@endsection