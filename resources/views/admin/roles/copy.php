



<!-- Copy Role -->


@extends('admin.layouts.app')

@section('content')
<div class="mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                Roles Management
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Define and manage system access levels
            </p>
        </div>

        <a href="{{ route('admin.roles.create') }}"
           class="inline-flex items-center px-6 py-3 text-sm font-medium text-white  bg-amber-600 hover:bg-amber-700 rounded-xl shadow-sm transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
            <i class="fas fa-shield-alt mr-2"></i>
            Add New Role
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Role Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Slug
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Created At
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($roles as $role)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs font-mono">
                                    {{ $role->slug }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $role->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <a href="{{ route('admin.roles.edit', $role->id) }}"
                                  class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300 bg-amber-50 dark:bg-amber-900/20 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900/30 transition">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if(!in_array($role->slug, ['admin', 'staff', 'customer']))
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline">  

                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this role? This may affect users assigned to it.')"
                                             class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 text-center sm:text-right">
            {{ $roles->links() }}
        </div>
        
    </div>

</div>

@endsection

                                