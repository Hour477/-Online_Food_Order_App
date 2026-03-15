@php
    $sidebarSettings = \App\Models\Setting::query()
        ->whereIn('key', ['logo', 'resturant_name'])
        ->pluck('value', 'key');
    $sidebarLogo = $sidebarSettings['logo'] ?? null;
    $sidebarName = $sidebarSettings['resturant_name'] ?? config('app.name');
    use App\Helpers\HelperSidebar;
    $menus = HelperSidebar::menus();
@endphp

<aside id="app-sidebar"
       class="fixed inset-y-0 left-0 z-50 w-72 h-screen overflow-hidden bg-white border-r border-gray-200 flex flex-col shadow-sm transform transition-all duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:sticky lg:top-0 lg:inset-y-auto">

    {{-- Brand --}}
    <div class="brand-wrap p-4 border-b border-gray-100 flex items-center gap-3">
        @if(!empty($sidebarLogo))
            <img src="{{ asset('storage/settings/' . $sidebarLogo) }}" alt="{{ $sidebarName }}"
                 class="h-10 w-10 rounded-lg object-contain bg-gray-100 p-1 flex-shrink-0">
        @else
            <div class="h-10 w-10 rounded-lg bg-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="4.5"></circle>
                    <path d="M5 4v7m0 0a2 2 0 002 2M5 11a2 2 0 01-2-2V4"></path>
                    <path d="M18 4v7a2 2 0 01-2 2"></path>
                </svg>
            </div>
        @endif
        <p class="brand-text text-sm font-semibold text-gray-900 truncate">{{ $sidebarName }}</p>
        <button type="button" id="sidebar-collapse-btn"
                class="ml-auto hidden lg:flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors flex-shrink-0"
                aria-label="Toggle sidebar">
            <svg id="collapse-icon-open" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
            <svg id="collapse-icon-closed" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 min-h-0 px-3 py-5 overflow-y-auto">
        <ul class="space-y-0.5">
            @foreach($menus as $menu)
                @if(isset($menu['children']) && !empty($menu['children']))
                    {{-- Dropdown (folder) --}}
                    @php
                        $folderKey = $menu['folder'] ?? 'folder-' . \Illuminate\Support\Str::slug($menu['title']);
                        $parentActive = false;
                        foreach ($menu['children'] as $child) {
                            if (request()->routeIs($child['active'] ?? '')) { $parentActive = true; break; }
                        }
                        $isOpen = $parentActive;
                    @endphp
                    <li class="sidebar-folder {{ $isOpen ? 'is-open' : '' }}">
                        <button type="button"
                                data-folder="{{ $folderKey }}"
                                aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                                class="sidebar-folder-toggle orders-toggle group relative w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ $parentActive ? 'is-active' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                            <span class="sidebar-link-accent absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-amber-600 transition-opacity {{ $parentActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                            <div class="flex items-center gap-3">
                                @include('admin.layouts.partials.sidebar-icon', ['icon' => $menu['icon'] ?? 'circle'])
                                <span class="sidebar-text">{{ $menu['title'] }}</span>
                            </div>
                            <svg class="chevron w-4 h-4 transition-transform {{ $isOpen ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul class="sidebar-submenu orders-submenu mt-0.5 space-y-0.5 pl-9 {{ $isOpen ? 'is-open' : '' }}">
                            @foreach($menu['children'] as $child)
                                @php $childActive = request()->routeIs($child['active'] ?? ''); @endphp
                                <li>
                                    <a href="{{ route($child['route']) }}"
                                       class="sidebar-submenu-link relative flex items-center gap-2.5 pl-6 py-2 pr-3 rounded-lg text-sm {{ $childActive ? 'bg-amber-50 text-amber-700 font-medium' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                                        @if($childActive)
                                            <span class="absolute left-0 top-1.5 bottom-1.5 w-0.5 rounded-r-full bg-amber-600" aria-hidden="true"></span>
                                        @endif
                                        <span class="sidebar-text">{{ $child['title'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    {{-- Single link --}}
                    @php $isActive = request()->routeIs($menu['active'] ?? ''); @endphp
                    <li>
                        <a href="{{ route($menu['route']) }}"
                           class="sidebar-link group relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ $isActive ? 'is-active' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
                           title="{{ $menu['title'] }}">
                            <span class="sidebar-link-accent absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-amber-600 transition-opacity {{ $isActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                            @include('admin.layouts.partials.sidebar-icon', ['icon' => $menu['icon'] ?? 'circle'])
                            <span class="sidebar-text">{{ $menu['title'] }}</span>
                            @if(isset($menu['badge']) && $menu['badge'])
                                <span class="ml-auto sidebar-text inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-amber-600 bg-amber-100 rounded-full">
                                    {{ $menu['badge'] }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>

    {{-- Logout --}}
<div class="p-3 border-t border-gray-100 mt-auto">
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        class="logout-link group relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium
                  text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-150" title="Logout">
        <span
            class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-red-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        <span class="sidebar-text">Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</div>

</aside>

<style>
    /* Sidebar link active state — single place to change active color */
    #app-sidebar .sidebar-link.is-active {
        background-color: rgb(255 251 235); /* amber-50 */
        color: rgb(180 83 9); /* amber-700 */
    }
    #app-sidebar .sidebar-link.is-active .sidebar-link-accent {
        opacity: 1;
    }

    /* Dropdown parent button when a child is active */
    #app-sidebar .sidebar-folder-toggle.is-active {
        background-color: rgb(255 251 235);
        color: rgb(180 83 9);
    }
    #app-sidebar .sidebar-folder-toggle.is-active .sidebar-link-accent {
        opacity: 1;
    }

    /* Dropdown children: transition + active color */
    #app-sidebar .sidebar-submenu .sidebar-submenu-link {
        transition: background-color 0.15s ease, color 0.15s ease;
    }
    #app-sidebar .sidebar-submenu .sidebar-submenu-link.bg-amber-50 {
        background-color: rgb(255 251 235);
        color: rgb(180 83 9);
    }

    .sidebar-submenu {
        max-height: 0;
        opacity: 0;
        transform: translateY(-4px);
        overflow: hidden;
        transition: max-height 0.2s ease, opacity 0.2s ease, transform 0.2s ease;
    }

    .sidebar-submenu.is-open {
        max-height: 200px;
        opacity: 1;
        transform: translateY(0);
    }

    @media (min-width: 1024px) {
        .sidebar-collapsed #app-sidebar {
            width: 5rem;
        }

        .sidebar-collapsed #app-sidebar .brand-wrap {
            justify-content: center;
        }

        .sidebar-collapsed #app-sidebar .brand-text,
        .sidebar-collapsed #app-sidebar .sidebar-text,
        .sidebar-collapsed #app-sidebar .chevron,
        .sidebar-collapsed #app-sidebar .orders-submenu {
            display: none;
        }

        .sidebar-collapsed #app-sidebar .sidebar-link,
        .sidebar-collapsed #app-sidebar .orders-toggle,
        .sidebar-collapsed #app-sidebar .logout-link {
            justify-content: center;
            gap: 0;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        /* Hide collapse toggle button text & keep icon centred */
        .sidebar-collapsed #app-sidebar #sidebar-collapse-btn {
            margin-left: 0;
        }
    }
</style>

<script>
    (function () {
        // Basic guards in case DOM elements are missing
        const sidebarEl = document.getElementById('app-sidebar');
        const collapseBtn = document.getElementById('sidebar-collapse-btn');
        const iconOpen = document.getElementById('collapse-icon-open');
        const iconClosed = document.getElementById('collapse-icon-closed');

        if (!sidebarEl) return;

        // ── Collapse toggle (state remembered) ─────────────────────────
        const COLLAPSE_KEY = 'sidebar_collapsed';
        const isCollapsed = localStorage.getItem(COLLAPSE_KEY) === 'true';

        function applyCollapse(collapsed) {
            const root = document.documentElement;
            root.classList.toggle('sidebar-collapsed', collapsed);
            if (iconOpen && iconClosed) {
                iconOpen.classList.toggle('hidden', collapsed);
                iconClosed.classList.toggle('hidden', !collapsed);
            }
            localStorage.setItem(COLLAPSE_KEY, collapsed ? 'true' : 'false');
        }

        // Restore saved collapse state on load
        applyCollapse(isCollapsed);

        if (collapseBtn) {
            collapseBtn.addEventListener('click', () => {
                const root = document.documentElement;
                applyCollapse(!root.classList.contains('sidebar-collapsed'));
            });
        }

        // ── Folder (submenu) state helpers ─────────────────────────────
        const FOLDERS_KEY = 'sidebar_open_folders';

        function getOpenFolders() {
            try {
                const raw = localStorage.getItem(FOLDERS_KEY);
                return raw ? JSON.parse(raw) : [];
            } catch (e) {
                return [];
            }
        }

        function setOpenFolders(keys) {
            try {
                localStorage.setItem(FOLDERS_KEY, JSON.stringify(keys));
            } catch (e) {
                // ignore
            }
        }

        function syncFoldersToStorage() {
            const openKeys = [];
            document.querySelectorAll('.sidebar-folder').forEach(folder => {
                const btn = folder.querySelector('.sidebar-folder-toggle');
                const key = btn?.getAttribute('data-folder');
                if (key && folder.classList.contains('is-open')) {
                    openKeys.push(key);
                }
            });
            setOpenFolders(openKeys);
        }

        function restoreFolderOpenState() {
            const saved = getOpenFolders();
            if (!Array.isArray(saved) || !saved.length) return;

            document.querySelectorAll('.sidebar-folder').forEach(folder => {
                const btn = folder.querySelector('.sidebar-folder-toggle');
                const submenu = folder.querySelector('.orders-submenu');
                const chevron = folder.querySelector('.chevron');
                const key = btn?.getAttribute('data-folder');

                if (!key || !submenu) return;

                const shouldBeOpen = saved.includes(key);

                if (shouldBeOpen) {
                    submenu.classList.add('is-open');
                    chevron?.classList.add('rotate-180');
                    btn.setAttribute('aria-expanded', 'true');
                    folder.classList.add('is-open');
                } else {
                    submenu.classList.remove('is-open');
                    chevron?.classList.remove('rotate-180');
                    btn.setAttribute('aria-expanded', 'false');
                    folder.classList.remove('is-open');
                }
            });
        }

        // Apply saved open/closed state on load,
        // overriding the Blade "request()->routeIs" defaults
        restoreFolderOpenState();

        // ── Submenu (accordion) toggle with persistence ────────────────
        document.querySelectorAll('.sidebar-folder-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                // Do nothing when sidebar is collapsed on desktop
                if (window.innerWidth >= 1024 && document.documentElement.classList.contains('sidebar-collapsed')) {
                    return;
                }

                const folder = btn.closest('.sidebar-folder');
                const submenu = folder.querySelector('.orders-submenu');
                const chevron = btn.querySelector('.chevron');
                const isOpen = submenu.classList.contains('is-open');

                // Close all open submenus first (accordion behaviour)
                document.querySelectorAll('.sidebar-folder').forEach(f => {
                    f.querySelector('.orders-submenu')?.classList.remove('is-open');
                    f.querySelector('.chevron')?.classList.remove('rotate-180');
                    f.querySelector('.sidebar-folder-toggle')?.setAttribute('aria-expanded', 'false');
                    f.classList.remove('is-open');
                });

                // Open this one if it was closed
                if (!isOpen) {
                    submenu.classList.add('is-open');
                    chevron?.classList.add('rotate-180');
                    btn.setAttribute('aria-expanded', 'true');
                    folder.classList.add('is-open');
                }

                // Persist the new state
                syncFoldersToStorage();
            });
        });
    })();
</script>