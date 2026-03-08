<!-- resources/views/layouts/sidebar.blade.php -->

<aside id="app-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 h-screen overflow-hidden bg-white dark:bg-gray-950 border-r border-gray-200 dark:border-gray-800 
             flex flex-col shadow-xl transform transition-all duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:sticky lg:top-0 lg:inset-y-auto">

    <!-- Brand / Logo -->
    <div class="brand-wrap p-6 border-b border-gray-200 dark:border-gray-800 flex items-center gap-4">
       
    </div>

    <!-- Navigation -->
    <nav class="flex-1 min-h-0 px-4 py-8 overflow-y-auto">
        <ul class="space-y-1.5">

            <!-- Dashboard -->
            @if(auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('dashboard') }}"
                   class="sidebar-link group relative flex items-center gap-4 px-5 py-3.5 rounded-2xl font-medium transition-all duration-200
                          {{ request()->routeIs('dashboard') 
                             ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/30' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/70' }}">
                    
                    <!-- Active indicator bar -->
                    <span class="absolute left-0 top-1 bottom-1 w-1.5 rounded-r-full bg-indigo-600 scale-y-0 group-hover:scale-y-75 transition-transform origin-left {{ request()->routeIs('dashboard') ? 'scale-y-100' : '' }}"></span>

                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            @endif

            <!-- Categories -->
            @if(auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('categories.index') }}"
                   class="sidebar-link group relative flex items-center gap-4 px-5 py-3.5 rounded-2xl font-medium transition-all duration-200
                          {{ request()->routeIs('categories.*') 
                             ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/30' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/70' }}">
                    <span class="absolute left-0 top-1 bottom-1 w-1.5 rounded-r-full bg-indigo-600 scale-y-0 group-hover:scale-y-75 transition-transform origin-left {{ request()->routeIs('categories.*') ? 'scale-y-100' : '' }}"></span>
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span class="sidebar-text">Categories</span>
                </a>
            </li>
            @endif

            <!-- Menu Items -->
            @if(auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('menu_items.index') }}"
                   class="sidebar-link group relative flex items-center gap-4 px-5 py-3.5 rounded-2xl font-medium transition-all duration-200
                          {{ request()->routeIs('menu_items.*') || request()->routeIs('menu-items.*') 
                             ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/30' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/70' }}">
                    <span class="absolute left-0 top-1 bottom-1 w-1.5 rounded-r-full bg-indigo-600 scale-y-0 group-hover:scale-y-75 transition-transform origin-left {{ request()->routeIs('menu_items.*') || request()->routeIs('menu-items.*') ? 'scale-y-100' : '' }}"></span>
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="sidebar-text">Menu Items</span>
                </a>
            </li>
            @endif

            <!-- Orders (dropdown) -->
            @if(in_array(auth()->user()->role, ['waiter', 'admin']))
            <li class="sidebar-folder orders-menu {{ request()->routeIs('orders.*') ? 'is-open' : '' }}">
                <button type="button"
                        aria-expanded="{{ request()->routeIs('orders.*') ? 'true' : 'false' }}"
                        data-folder="orders"
                        class="sidebar-folder-toggle orders-toggle-btn orders-toggle group relative w-full flex items-center justify-between gap-4 px-5 py-3.5 rounded-2xl font-medium transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/70">
                    
                    <span class="folder-indicator orders-indicator absolute left-0 top-1 bottom-1 w-1.5 rounded-r-full bg-indigo-600 scale-y-0 group-hover:scale-y-75 transition-transform origin-left {{ request()->routeIs('orders.*') ? 'scale-y-100' : '' }}"></span>

                    <div class="flex items-center gap-4">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="sidebar-text">
                            Orders
                            @if(auth()->user()->role === 'admin') <span class="text-xs opacity-75 ml-1">(Manage)</span> @endif
                            @if(auth()->user()->role === 'waiter') <span class="text-xs opacity-75 ml-1">(Staff)</span> @endif
                        </span>
                    </div>
                    <svg class="chevron w-5 h-5 transition-transform {{ request()->routeIs('orders.*') ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul class="sidebar-submenu orders-submenu mt-1.5 space-y-1 pl-11 {{ request()->routeIs('orders.*') ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ request()->route('orders') ? route('orders.show', request()->route('orders')) : route('orders.index') }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors
                                  {{ request()->routeIs('orders.index') || request()->routeIs('orders.show') 
                                     ? 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300' 
                                     : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800/50' }}">
                            <i class="fas fa-{{ request()->route('orders') ? 'eye' : 'list' }} w-5 text-center"></i>
                            {{ request()->route('orders') ? 'Current Order' : 'All Orders' }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.create') }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors
                                  {{ request()->routeIs('orders.create') 
                                     ? 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300' 
                                     : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800/50' }}">
                            <i class="fas fa-plus w-5 text-center"></i>
                            New Order
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <!-- Kitchen View -->
            @if(in_array(auth()->user()->role, ['kitchen', 'admin']))
            <li>
                <a href="{{ route('kitchen') }}"
                   class="sidebar-link group relative flex items-center gap-4 px-5 py-3.5 rounded-2xl font-medium transition-all duration-200
                          {{ request()->routeIs('kitchen') 
                             ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/30' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/70' }}">
                    <span class="absolute left-0 top-1 bottom-1 w-1.5 rounded-r-full bg-indigo-600 scale-y-0 group-hover:scale-y-75 transition-transform origin-left {{ request()->routeIs('kitchen') ? 'scale-y-100' : '' }}"></span>
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                    </svg>
                    <span class="sidebar-text">Kitchen View</span>
                </a>
            </li>
            @endif

            <!-- Reports -->
            @if(auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('reports.income') }}"
                   class="sidebar-link group relative flex items-center gap-4 px-5 py-3.5 rounded-2xl font-medium transition-all duration-200
                          {{ request()->routeIs('reports.*') 
                             ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/30' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/70' }}">
                    <span class="absolute left-0 top-1 bottom-1 w-1.5 rounded-r-full bg-indigo-600 scale-y-0 group-hover:scale-y-75 transition-transform origin-left {{ request()->routeIs('reports.*') ? 'scale-y-100' : '' }}"></span>
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span class="sidebar-text">Reports</span>
                </a>
            </li>
            @endif

            <!-- Tables -->
            @if(in_array(auth()->user()->role, ['waiter', 'admin']))
            <li>
                <a href="{{ route('tables.index') }}"
                   class="sidebar-link group relative flex items-center gap-4 px-5 py-3.5 rounded-2xl font-medium transition-all duration-200
                          {{ request()->routeIs('tables.*') 
                             ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/30' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/70' }}">
                    <span class="absolute left-0 top-1 bottom-1 w-1.5 rounded-r-full bg-indigo-600 scale-y-0 group-hover:scale-y-75 transition-transform origin-left {{ request()->routeIs('tables.*') ? 'scale-y-100' : '' }}"></span>
                    <i class="fas fa-table w-6 h-6 flex-shrink-0 text-center"></i>
                    <span class="sidebar-text">Tables</span>
                </a>
            </li>
            @endif

            <!-- Customers -->
            @if(in_array(auth()->user()->role, ['admin', 'waiter']))
            <li>
                <a href="{{ route('customers.index') }}"
                   class="sidebar-link group relative flex items-center gap-4 px-5 py-3.5 rounded-2xl font-medium transition-all duration-200
                          {{ request()->routeIs('customers.*') 
                             ? 'text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/30' 
                             : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/70' }}">
                    <span class="absolute left-0 top-1 bottom-1 w-1.5 rounded-r-full bg-indigo-600 scale-y-0 group-hover:scale-y-75 transition-transform origin-left {{ request()->routeIs('customers.*') ? 'scale-y-100' : '' }}"></span>
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="sidebar-text">Customers</span>
                </a>
            </li>
            @endif

            <!-- Business Settings -->
            @if(auth()->user()->role === 'admin')
            <li class="sidebar-folder settings-menu {{ request()->routeIs('settings.*') ? 'is-open' : '' }}">
                <button type="button"
                        aria-expanded="{{ request()->routeIs('settings.*') ? 'true' : 'false' }}"
                        data-folder="settings"
                        class="sidebar-folder-toggle settings-toggle-btn group relative w-full flex items-center justify-between gap-4 px-5 py-3.5 rounded-2xl font-medium transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/70">
                    <span class="folder-indicator settings-indicator absolute left-0 top-1 bottom-1 w-1.5 rounded-r-full bg-indigo-600 scale-y-0 group-hover:scale-y-75 transition-transform origin-left {{ request()->routeIs('settings.*') ? 'scale-y-100' : '' }}"></span>

                    <div class="flex items-center gap-4">
                        <i class="fas fa-cogs w-6 h-6 flex-shrink-0 text-center"></i>
                        <span class="sidebar-text">Business Settings</span>
                    </div>
                    <svg class="chevron w-5 h-5 transition-transform {{ request()->routeIs('settings.*') ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul class="sidebar-submenu settings-submenu mt-1.5 space-y-1 pl-11 {{ request()->routeIs('settings.*') ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ route('settings.index') }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors
                                  {{ request()->routeIs('settings.*')
                                     ? 'bg-indigo-500/10 text-indigo-700 dark:text-indigo-300'
                                     : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800/50' }}">
                            <i class="fas fa-sliders-h w-5 text-center"></i>
                            Settings
                        </a>
                    </li>
                </ul>
            </li>
            @endif

        </ul>
    </nav>

    <!-- Logout -->
    <div class="p-5 border-t border-gray-200 dark:border-gray-800 mt-auto">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="logout-link group relative flex items-center gap-4 px-5 py-3.5 rounded-2xl font-medium text-gray-700 dark:text-gray-300 
                  hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-400 transition-all duration-200">
            <span class="absolute left-0 top-1 bottom-1 w-1.5 rounded-r-full bg-red-600 scale-y-0 group-hover:scale-y-75 transition-transform origin-left"></span>
            <svg class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="sidebar-text">Logout</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

</aside>

