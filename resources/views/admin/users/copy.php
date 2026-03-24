<div class="overflow-x-auto rounded-[10px] border border-gray-200 dark:border-gray-700">
            <table  class="min-w-full divide-y divide-gray-200 dark:divide-gray-700  border-gray-200 dark:border-gray-700 ">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            #
                        </th>
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
                    @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.users.show', $user->id) }}">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                       
                                        <img src="{{ $user->display_image }}" alt="{{ $user->name }}"
                                            class="w-full h-full rounded-full object-cover border-amber-600">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $user->name }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fa-solid fa-envelope text-gray-400 mr-2 text-xs"></i>
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $user->email }}</span>
                            </div>
                            @if($user->phone)
                            <div class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <i class="fa-solid fa-phone mr-1.5"></i>{{ $user->phone }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-3 py-1.5 inline-flex items-center gap-1.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                <i class="fa-solid fa-shield-halved"></i>
                                {{ $user->role->name ?? 'No Role' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                {{-- View Button --}}
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="{{ action_btn_class('show') }}" title="View">
                                    <i class="{{ action_btn_icon('show') }}"></i>
                                    
                                </a>

                                {{-- Edit Button --}}
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="{{ action_btn_class('edit') }}" title="Edit">
                                    <i class="{{ action_btn_icon('edit') }}"></i>
                                </a>

                                    
                                </a>

                                {{-- Delete Button --}}
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="{{ action_btn_class('delete') }}" title="Delete">
                                        <i class="{{ action_btn_icon('delete') }}"></i>
                                        
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <i class="fa-solid fa-users-slash text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No users found</p>
                            @if(request('search'))
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Try searching with different
                                keywords</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>