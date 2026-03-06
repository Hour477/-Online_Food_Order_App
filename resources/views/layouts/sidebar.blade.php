<!-- resources/views/layouts/sidebar.blade.php -->

<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 text-gray-900 dark:text-gray-100 flex flex-col transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0 shadow-2xl">

    <!-- Logo / Brand -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center gap-3">
        <div class="text-4xl font-black text-indigo-400 tracking-tight drop-shadow-md">
            🍽
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                Restaurant POS
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Management System
            </p>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-6 overflow-y-auto">
        <ul class="space-y-1">
            <!-- Dashboard -->
            @if(auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('dashboard') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                          {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>
            @endif

            {{-- condition --}}
           

            <!-- Categories -->
            @if(auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('categories.index') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                          {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span class="font-medium">Manage Categories</span>
                </a>
            </li>
            @endif

            <!-- Menu Items -->
            @if(auth()->user()->role === 'admin' )
            <li>
                <a href="{{ route('menu_items.index') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                          {{ request()->routeIs('menu_items.*')||request()->routeIs('menu-items.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="font-medium">Manage Menu Items</span>
                </a>
            </li>
            @endif


            <!-- Orders -->

            @if(auth()->user()->role === 'waiter' || auth()->user()->role === 'admin')
            <li x-data="{ open: {{ request()->routeIs('orders.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="group flex items-center justify-between gap-3 w-full px-4 py-3 rounded-xl transition-all duration-200
                            {{ request()->routeIs('orders.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="font-medium">
                            @if(auth()->user()->role === 'admin') 
                                Manage
                            @endif
                            Orders 
                            
                            @if(auth()->user()->role === 'waiter') 
                                (Staff)
                            @endif
                        </span>
                    </span>
                    <svg class="w-4 h-4 transform transition-transform"
                         :class="{'rotate-90': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <ul x-show="open" x-cloak class="mt-1 space-y-1 pl-12">
                    @if(auth()->user()->role === 'waiter' || auth()->user()->role === 'admin')
                    @php
                        $currentOrder = request()->route('order');
                    @endphp
                    <li>
                        <a href="{{ $currentOrder ? route('orders.show', $currentOrder) : route('orders.index') }}"
                           class="flex items-center gap-3 px-4 py-2 rounded-xl transition-all duration-200
                                  {{ $currentOrder ? (request()->routeIs('orders.show') ? 'bg-indigo-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white') : (request()->routeIs('orders.index') ? 'bg-indigo-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white') }}">

                            <i class="fas fa-{{ $currentOrder ? 'eye' : 'list' }}"></i>
                            {{ $currentOrder ? 'View Order' : 'View All Orders' }}
                        </a>
                    </li>

                    @endif
                    
                    @if(auth()->user()->role === 'waiter' || auth()->user()->role === 'admin')
                    <li>
                        <a href="{{ route('orders.create') }}"
                           class="flex items-center gap-3 px-4 py-2 rounded-xl transition-all duration-200
                                  {{ request()->routeIs('orders.create') ? 'bg-indigo-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                            <i class="fas fa-plus"></i>
                            New Order
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- Kitchen --> 
            {{-- admin can view when kitchen cooking food or other --}}
            @if(auth()->user()->role === 'kitchen' || auth()->user()->role === 'admin') 
            <li>
                <a href="{{ route('kitchen') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                          {{ request()->routeIs('kitchen') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                    </svg>
                    <span class="font-medium">Kitchen View</span>
                </a>
            </li>
            @endif
            <!-- Reports -->
            @if(auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('reports.income') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                          {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span class="font-medium">Reports</span>
                </a>
            </li>
            @endif
            <!-- Tables -->
            @if(auth()->user()->role === 'waiter' || auth()->user()->role === 'admin')
            <li>
                <a href="{{ route('tables.index') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                          {{ request()->routeIs('tables.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="fas fa-table"></i>
                    <span class="font-medium">Tables</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'waiter')
            <!-- Customers -->
            <li>
                <a href="{{ route('customers.index') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                          {{ request()->routeIs('customers.*') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="font-medium">Customers</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>

    <!-- Footer / Logout -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-800 mt-auto">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="group flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 dark:text-gray-400 hover:bg-red-100 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200">
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="font-medium">Logout</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

</aside>

<!-- Mobile Sidebar Toggle (add to your main layout if needed) -->
<!-- Example usage in app.blade.php header: -->

<button class="md:hidden p-2 text-gray-400 hover:text-white" id="sidebar-toggle">
    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<script>
document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
    document.querySelector('aside').classList.toggle('-translate-x-full');
});
</script>



