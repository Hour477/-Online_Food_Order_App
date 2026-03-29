@use('Illuminate\Support\Facades\Auth')

<nav class="flex items-center justify-between w-full">
    <!-- Right: Sidebar Collapse Button -->
    <div class="flex items-center gap-3"> 
        <button type="button" id="sidebar-collapse-btn"
            class="hidden lg:flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 bg-gray-100 hover:bg-gray-200 hover:text-gray-700 transition-all duration-200 flex-shrink-0"
            aria-label="Toggle sidebar">
            <svg id="collapse-icon-open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
            <svg id="collapse-icon-closed" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    {{-- <div class="flex items-center gap-4"></div> --}}
    {{-- Right: User Area --}}
    <div class="flex items-center gap-4 sm:gap-4">
        @auth
            {{-- Language Switcher --}}
            <div class="relative language-menu" id="admin-language-menu">
                <button type="button"
                    class="language-menu-toggle flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 transition text-sm font-medium text-gray-700 touch-target" aria-expanded="false" aria-haspopup="true">
                    <i class="fa-solid fa-globe text-amber-600"></i>
                    <span class="hidden sm:inline">{{ config('app.available_locales')[app()->getLocale()] ?? 'English' }}</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div
                    class="language-menu-panel hidden absolute right-0 mt-2 w-36 bg-white rounded-lg shadow-lg border border-gray-200 z-[100] py-1">
                    @foreach (config('app.available_locales', ['en' => 'English', 'km' => 'Khmer']) as $locale => $label)
                        <a href="{{ route('language.switch', $locale) }}"
                            class="block px-4 py-3 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 {{ app()->getLocale() === $locale ? 'bg-amber-50 text-amber-700 font-medium' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Notifications --}}
            <div class="relative notification-menu mr-2">
                <button type="button" id="notification-bell"
                    class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 bg-gray-100 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 border border-transparent transition-all duration-200"
                    title="{{ __('app.notifications') }}" aria-label="{{ __('app.notifications') }}">
                    <i class="fa-solid fa-bell "></i>
                    <span id="notification-count"
                        class="hidden absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white">0</span>
                </button>

                {{-- Notification Dropdown --}}
                <div id="notification-panel"
                    class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-lg py-1.5 z-50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-gray-900">{{ __('app.new_orders') }}</h3>
                        <button id="clear-notifications"
                            class="text-xs text-amber-600 hover:text-amber-700 touch-target px-2">{{ __('app.clear') }}</button>
                    </div>
                    <div id="notification-list" class="max-h-96 overflow-y-auto">
                        <div class="px-4 py-8 text-center text-gray-500 italic text-sm">{{ __('app.no_new_orders') }}</div>
                    </div>
                    <a href="{{ route('admin.orders.all') }}"
                        class="block px-4 py-3 text-center text-xs font-medium text-amber-600 hover:bg-gray-50 border-t border-gray-100">
                        {{ __('app.view_all_orders') }}

                    </a>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="hidden md:flex items-center gap-2 mr-2">
                <a href="{{ route('admin.orders.create') }}"
                    class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 bg-gray-100 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 border border-transparent transition-all duration-200" title="{{ __('app.new_order') }}" aria-label="{{ __('app.new_order') }}">
                    <i class="fa-solid fa-plus"></i>
                </a>
                <div class="h-6 w-px bg-gray-200 mx-1"></div>
            </div>

            {{-- User Dropdown --}}
            <div class="relative user-menu">

                <button type="button" aria-expanded="false" aria-haspopup="true"
                    class="border border-gray-200 hover:border-amber-600 user-menu-toggle flex rounded-lg gap-2 items-center px-2 py-1 focus:outline-none focus:ring-2 focus:ring-amber-500 transition hover:opacity-80 touch-target">

                    @php
                        $avatarUrl =
                            Auth::user()->display_image ??
                            'https://ui-avatars.com/api/?name=' .
                                urlencode(Auth::user()->name ?? 'User') .
                                '&background=d97706&color=fff';
                    @endphp

                    <img class="h-9 w-9 rounded-lg object-cover border-2 border-amber-200" src="{{ $avatarUrl }}"
                        alt="{{ Auth::user()?->name ?? 'User' }}">

                    <span class="hidden sm:block text-sm font-medium text-gray-900 truncate max-w-xs">
                        {{ Auth::user()?->name ?? 'User' }}
                    </span>

                    <svg class="user-menu-chevron h-4 w-4 text-gray-400 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown Panel --}}
                <div
                    class="user-menu-panel hidden absolute right-0 mt-2 w-60 bg-white border border-gray-200 rounded-xl shadow-lg py-1.5 z-50">

                    {{-- User Info --}}
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900">
                            {{ Auth::user()?->name ?? 'User' }}
                        </p>
                        <p class="text-xs text-gray-500 truncate mt-0.5">
                            {{ Auth::user()?->email ?? 'user@example.com' }}
                        </p>
                        <p class="text-xs text-amber-600 mt-1">
                            {{ __('app.role') }}:
                            <span class="capitalize font-medium">
                                {{ Auth::user()?->role?->name }}
                            </span>
                        </p>
                    </div>

                    {{-- Links --}}
                    <a href="{{ route('admin.users.show', Auth::user()->id) }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm  hover:bg-gray-50 hover:text-gray-900 transition-colors">
                        <i class="fas fa-user-circle w-4 text-center text-gray-400"></i>
                        {{ __('app.profile') }}
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm  hover:bg-gray-50 hover:text-gray-900 transition-colors">
                        <i class="fas fa-cog w-4 text-center text-gray-400"></i>
                        {{ __('app.settings') }}
                    </a>

                    <div class="border-t border-gray-100 my-1"></div>

                    {{-- Logout --}}
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                        <i class="fas fa-sign-out-alt w-4 text-center"></i>
                        {{ __('app.logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        @else
            {{-- Guest Links --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                    class="px-4 py-2 text-sm font-medium  hover:text-gray-900 transition-colors">
                    {{ __('app.login') }}
                </a>
                <a href="{{ route('register') }}"
                    class="px-4 py-2 text-sm font-medium bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors">
                    {{ __('app.register') }}
                </a>
            </div>
        @endauth

    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // User menu dropdown
        const userMenu = document.querySelector('.user-menu');
        const toggle = userMenu?.querySelector('.user-menu-toggle');
        const panel = userMenu?.querySelector('.user-menu-panel');
        const chevron = userMenu?.querySelector('.user-menu-chevron');

        const setUserMenuState = (open) => {
            if (!toggle || !panel || !chevron) return;
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            panel.classList.toggle('hidden', !open);
            chevron.classList.toggle('rotate-180', open);
        };

        toggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            setUserMenuState(panel.classList.contains('hidden'));
        });

        // Close user menu when clicking outside
        document.addEventListener('click', (e) => {
            if (userMenu && !userMenu.contains(e.target)) setUserMenuState(false);
        });
    });

    // Language menu dropdown - using event delegation for reliability
    document.addEventListener('click', function(e) {
        // Handle toggle click (button only)
        if (e.target.closest('.language-menu-toggle')) {
            e.preventDefault();
            e.stopPropagation();
            const menu = e.target.closest('.language-menu');
            const panel = menu?.querySelector('.language-menu-panel');
            if (panel) {
                panel.classList.toggle('hidden');
            }
            return;
        }

        // Allow language switch links to work normally
        if (e.target.closest('.language-menu-panel a')) {
            return; // Let the link navigate normally
        }

        // Close all language menus when clicking outside
        document.querySelectorAll('.language-menu-panel').forEach(panel => {
            if (!e.target.closest('.language-menu')) {
                panel.classList.add('hidden');
            }
        });
    });
</script>
