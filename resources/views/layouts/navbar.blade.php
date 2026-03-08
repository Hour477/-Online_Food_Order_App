<!-- resources/views/layouts/navbar.blade.php -->

@use('Illuminate\Support\Facades\Auth')

<nav class="flex items-center justify-between w-full">

    <!-- Left: Logo / Brand -->
    <div class="flex items-center gap-3">
        <!-- Logo icon -->
        
    </div>

    <!-- Right: User Area + Actions -->
    <div class="flex items-center gap-4 sm:gap-6">

        @auth
            <!-- Quick Actions (optional: notifications, cart, etc.) -->
            <div class="flex items-center gap-4">
                <button id="theme-toggle" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white focus:outline-none" title="Toggle dark mode">
                    {{-- icon dark mode --}}
                    <i class="fas fa-moon text-xl" id="theme-icon"></i>
                </button>

              
            </div>

            <!-- User Dropdown (Authenticated) -->
            <div class="relative group user-menu">
                <button type="button"
                        aria-expanded="false"
                        class="user-menu-toggle flex items-center gap-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full transition hover:opacity-80">
                    <!-- Avatar -->
                    <img class="h-9 w-9 sm:h-10 sm:w-10 rounded-full object-cover border-2 border-indigo-500/30 group-hover:border-indigo-500 transition"
                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=6366f1&color=fff"
                         alt="{{ Auth::user()?->name ?? 'User' }}">

                    <!-- Name (desktop only) -->
                    <span class="hidden sm:block text-sm font-medium text-gray-900 dark:text-white truncate max-w-xs">
                        {{ Auth::user()?->name ?? 'User' }}
                    </span>

                    <!-- Dropdown arrow -->
                    <svg class="user-menu-chevron h-4 w-4 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div class="user-menu-panel hidden absolute right-0 mt-3 w-64 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl py-2 z-50">

                    <!-- User Info -->
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ Auth::user()?->name ?? 'User' }}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                            {{ Auth::user()?->email ?? 'user@example.com' }}
                        </p>
                        <p class="text-xs text-indigo-400 mt-1">
                            Role: <span class="capitalize font-medium">{{ Auth::user()?->role ?? 'staff' }}</span>
                        </p>
                    </div>

                    <!-- Menu Items -->
                    <a href="#" class="flex px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition items-center gap-3">
                        <i class="fas fa-user-circle w-5"></i>
                        Profile
                    </a>
                    <a href="#" class="flex px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition items-center gap-3">
                        <i class="fas fa-cog w-5"></i>
                        Settings
                    </a>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>

                    <!-- Logout -->
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="flex px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 hover:text-red-700 dark:hover:text-red-300 transition items-center gap-3">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        Logout
                    </a>

                    <!-- Hidden logout form -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        @else
            <!-- Guest Links (Unauthenticated) -->
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Register
                </a>
            </div>
        @endauth

    </div>

</nav>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const root = document.documentElement;
    const icon = document.getElementById('theme-icon');
    const toggle = document.getElementById('theme-toggle');

    // Function to update the icon based on current theme
    const updateIcon = (isDark) => {
        if (icon) {
            if (isDark) {
                icon.className = 'fas fa-sun text-xl text-yellow-400'; // Sun icon for dark mode
            } else {
                icon.className = 'fas fa-moon text-xl text-gray-400'; // Moon icon for light mode
            }
        }
    };

    // Set initial icon based on what the <head> script already applied
    const isCurrentlyDark = root.classList.contains('dark');
    updateIcon(isCurrentlyDark);

    // Handle toggle click
    toggle?.addEventListener('click', () => {
        const willBeDark = !root.classList.contains('dark');
        
        if (willBeDark) {
            root.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            root.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
        
        updateIcon(willBeDark);
    });

    const userMenu = document.querySelector('.user-menu');
    const userMenuToggle = userMenu?.querySelector('.user-menu-toggle');
    const userMenuPanel = userMenu?.querySelector('.user-menu-panel');
    const userMenuChevron = userMenu?.querySelector('.user-menu-chevron');

    const setUserMenuState = (open) => {
        if (!userMenuToggle || !userMenuPanel || !userMenuChevron) return;
        userMenuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        userMenuPanel.classList.toggle('hidden', !open);
        userMenuChevron.classList.toggle('rotate-180', open);
    };

    userMenuToggle?.addEventListener('click', (event) => {
        event.stopPropagation();
        if (!userMenuPanel) return;
        const shouldOpen = userMenuPanel.classList.contains('hidden');
        setUserMenuState(shouldOpen);
    });

    document.addEventListener('click', (event) => {
        if (!userMenu) return;
        if (!userMenu.contains(event.target)) {
            setUserMenuState(false);
        }
    });
});
</script>
