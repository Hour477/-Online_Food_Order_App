@use('App\Helpers\HelperSidebar')
@use('App\Models\Order')
@php
    $sidebarSettings = \App\Models\Setting::query()
        ->whereIn('key', ['logo', 'resturant_name'])
        ->pluck('value', 'key');
    $sidebarLogo = $sidebarSettings['logo'] ?? null;
    $sidebarName = $sidebarSettings['resturant_name'] ?? config('app.name');
    $menus = HelperSidebar::menus();
    

    // use App\Helpers\DisplayImageHelper;

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
                                @php 
                                    $childActive = request()->routeIs($child['active'] ?? '');
                                    // Also check if params match for status filtering
                                    if (isset($child['params'])) {
                                        foreach ($child['params'] as $key => $value) {
                                            if (request()->get($key) !== $value) {
                                                $childActive = false;
                                            }
                                        }
                                        // If this child has params and current request has no params, it's not active
                                        if (empty(request()->all()) && !empty($child['params'])) {
                                            $childActive = false;
                                        }
                                    } elseif (empty($child['params']) && request()->get('status')) {
                                        // If child has no params but request has status, not active
                                        if ($child['title'] !== 'All Payments' && $child['title'] !== 'All Customer') {
                                            $childActive = false;
                                        }
                                    }
                                @endphp
                                <li>
                                    <a href="{{ route($child['route'], $child['params'] ?? []) }}"
                                       class="sidebar-submenu-link relative flex items-center gap-2.5 pl-6 py-2 pr-3 rounded-lg text-sm {{ $childActive ? 'bg-amber-50 text-amber-700 font-medium' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                                        @if($childActive)
                                            <span class="absolute left-0 top-1.5 bottom-1.5 w-0.5 rounded-r-full bg-amber-600" aria-hidden="true"></span>
                                        @endif
                                        <span class="sidebar-text">{{ $child['title'] }}</span>
                                        @if(isset($child['badge']) && isset($orderStatusCounts[$child['badge']]) && $orderStatusCounts[$child['badge']] > 0)
                                            <span class="ml-auto inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none rounded-full
                                                {{ $child['badge'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $child['badge'] === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $child['badge'] === 'delivered' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                                {{ $child['badge'] === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $child['badge'] === 'refunded' ? 'bg-purple-100 text-purple-800' : '' }}
                                                {{ $child['badge'] === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ $orderStatusCounts[$child['badge']] }}
                                            </span>
                                        @endif
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
   
</style>

