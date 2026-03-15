@use('Illuminate\Support\Facades\Auth')

<nav class="flex items-center justify-between w-full">

    {{-- Left: Brand --}}
    <div class="flex items-center gap-3"></div>

    {{-- Right: User Area --}}
    <div class="flex items-center gap-4 sm:gap-6">

        @auth
            {{-- Quick Actions --}}
            <div class="hidden md:flex items-center gap-2 mr-2">
                <a href="{{ route('admin.orders.create') }}"
                    class="p-2 text-gray-400 hover:text-amber-600 transition-colors" title="New Order">
                    {{-- POS --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    
                </a>
                <div class="h-5 w-px bg-gray-200 mx-1"></div>
            </div>

            {{-- User Dropdown --}}
            <div class="relative user-menu">

                <button type="button" aria-expanded="false"
                    class="user-menu-toggle flex items-center gap-2.5 focus:outline-none focus:ring-2 focus:ring-amber-500 rounded-full transition hover:opacity-80">
                    @php $avatarUrl = Auth::user()->image ? asset('storage/users/' . Auth::user()->image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'User') . '&background=d97706&color=fff'; @endphp
                    <img class="h-9 w-9 rounded-full object-cover border-2 border-amber-200"
                        src="{{ $avatarUrl }}"
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
                            Role:
                            <span class="capitalize font-medium">
                                {{ Auth::user()?->role?->name }}
                            </span>
                        </p>
                    </div>

                    {{-- Links --}}
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                        <i class="fas fa-user-circle w-4 text-center text-gray-400"></i>
                        Profile
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                        <i class="fas fa-cog w-4 text-center text-gray-400"></i>
                        Settings
                    </a>

                    <div class="border-t border-gray-100 my-1"></div>

                    {{-- Logout --}}
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                        <i class="fas fa-sign-out-alt w-4 text-center"></i>
                        Logout
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
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="px-4 py-2 text-sm font-medium bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors">
                    Register
                </a>
            </div>
        @endauth

    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const userMenu = document.querySelector('.user-menu');
        const toggle = userMenu?.querySelector('.user-menu-toggle');
        const panel = userMenu?.querySelector('.user-menu-panel');
        const chevron = userMenu?.querySelector('.user-menu-chevron');

        const setState = (open) => {
            if (!toggle || !panel || !chevron) return;
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            panel.classList.toggle('hidden', !open);
            chevron.classList.toggle('rotate-180', open);
        };

        toggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            setState(panel.classList.contains('hidden'));
        });

        document.addEventListener('click', (e) => {
            if (userMenu && !userMenu.contains(e.target)) setState(false);
        });
    });
</script>