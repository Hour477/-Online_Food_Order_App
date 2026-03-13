@extends('layouts.app')

@section('content')

<div class="mx-auto">

    <!-- Header + Create Button -->
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
            Menu Items
        </h3>
        
        
        <div class="mt-4">
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <form action="{{ route('menu_items.index') }}" method="GET" class="relative flex-1 sm:min-w-[300px]">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="text" 
                       name="search"
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Search menu_items..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors shadow-sm text-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit"
                        class="absolute inset-y-0 right-0 px-3 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Search
                </button>
            </form>
            {{-- Price --}}
            
            <div class="flex gap-2">
                <select onchange="window.location.href=this.value" 
                    class="block w-full sm:w-40 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5 shadow-sm">
                    <option value="{{ route('menu_items.index', ['status' => '', 'search' => request('search')]) }}">All Status</option>
                    <option value="{{ route('menu_items.index', ['status' => 'available', 'search' => request('search')]) }}" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="{{ route('menu_items.index', ['status' => 'unavailable', 'search' => request('search')]) }}" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>   

            </div>

            <a href="{{ route('menu_items.create') }}"
           class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-lg shadow-sm transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Menu Item
        </a>
        </div>
        </div>

        
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                <thead class="bg-gray-50 dark:bg-gray-900/70">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            image
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            Name
                        </th>
                        
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            Category
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">

                    @forelse($menu_items as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 ">
                                <a href="{{ route('menu_items.show', $item->id)}}">
                                    #{{ $item->id }}
                                </a>                            
                            </td>
                            <th class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('menu_items.show', $item->id) }}">
                                         @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-10 h-10 rounded-md object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                </a>
                               
                            </th>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                <a href="{{ route('menu_items.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 ml-2">
                                    {{ $item->name }}
                                </a>
                            </td>
                           
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $item->category?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                ${{ number_format($item->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status === 'available' || $item->status == 1 || $item->status === true)
                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-300">
                                        Available
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-300">
                                        Unavailable
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                {{-- show ,edit and delete--}}
                                <a href="{{ route('menu_items.show', $item->id) }}">
                                    <i class="fa-regular fa-eye text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300 mr-3 transition-colors duration-150"></i>
                                </a>
                                
                                <a href="{{ route('menu_items.edit', $item->id) }}"
                                   <i class="fa-regular fa-pen-to-square text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3
                                            transition-colors duration-150"> </i>
                                </a>

                                <form action="{{ route('menu_items.destroy', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this menu item? This cannot be undone.')"
                                    <i class="fas fa-trash-alt ml-1.5 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"></i>
                                            
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M9 7v10M15 7v10M4 21h16a1 1 0 001-1V6a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                                </svg>
                                <p class="mt-2 text-sm">No menu items found.</p>
                                <a href="{{ route('menu_items.create') }}" 
                                   class="mt-3 inline-block text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Add your first menu item →
                                </a>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
            
             <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                    {{ $menu_items->links() }}
            </div>
        </div>
    </div>
    <!-- Optional: Pagination (if you added paginate in controller) -->
   

</div>

@endsection