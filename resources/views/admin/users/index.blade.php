@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Users Management
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Manage system users and members
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-sm transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                <i class="fas fa-user-plus mr-2"></i>
                Add User
            </a>
        </div>

        <!-- Table Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Name
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Email
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Role
                            </th>
                            <th
                                class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{-- email and image --}}

                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if ($user->image)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                    src="{{ asset('storage/users/' . $user->image) }}"
                                                    alt="{{ $user->name }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-700 dark:text-amber-300 font-bold text-xs">
                                                    {{ strtoupper(mb_substr($user->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $user->email }}</div>
                                        </div>
                                    </div>





                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                        {{-- role_id --}}
                                        {{ $user->role->name ?? 'No Role' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                        class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300 transition">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300 transition">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this user?')"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination (if you use ->paginate() in controller) -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-amber-700 text-center sm:text-right">
                {{ $users->links() }}
            </div>
        </div>

    </div>
@endsection
