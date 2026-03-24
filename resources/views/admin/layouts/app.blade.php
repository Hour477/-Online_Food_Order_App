<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $appTitle   = \App\Models\Setting::where('key', 'resturant_name')->value('value');
        $appFavicon = \App\Models\Setting::where('key', 'favicon')->value('value');
    @endphp

    <link rel="icon" type="image/x-icon"
          href="{{ !empty($appFavicon) ? asset('storage/settings/' . $appFavicon) : asset('Restaurant-System.ico') }}">
    <title>{{ $appTitle ?: config('app.name', 'Restaurant POS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Momo+Trust+Sans:wght@200..800&family=Roboto:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <script>
        // Apply saved sidebar collapsed state before first paint to avoid flash
        (function () {
            try {
                if (localStorage.getItem('sidebar_collapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {
                // localStorage might be unavailable (e.g. privacy mode); fail silently
            }
        })();
    </script>

 
    {{-- Google Material Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"
          crossorigin="anonymous">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {!! ToastMagic::styles() !!}
    @stack('styles')
</head>

<body class="font-sans">
    

    <div class="flex min-h-screen">

        {{-- Mobile Sidebar Backdrop --}}
        <div id="sidebar-backdrop"
             class="fixed inset-0 bg-black/60 z-40 lg:hidden transition-opacity duration-300 opacity-0 pointer-events-none">
        </div>

        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-screen">

            {{-- Header --}}
            <header class="bg-white shadow-sm sticky top-0 z-40">
                <div class="flex items-center justify-between px-4 py-3 lg:px-4">

                    
                    

                    {{-- Navbar --}}
                    @include('admin.layouts.navbar')

                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto lg:p-4">
                @yield('content')
            </main>

            @include('admin.layouts.footer')

        </div>
    </div>

    
    @stack('scripts')
    @yield('scripts')

    {{-- Delete Confirmation Modal --}}
    @include('admin.layouts.partials.delete-confirm')

    {{-- Notification Script --}}
    @auth
    <audio id="notification-sound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Use server time for initial check
            let lastCheck = "{{ now()->format('Y-m-d H:i:s') }}";
            // Initialize with the current max ID from the database to ensure ONLY TRULY NEW orders trigger notifications
            let lastId = {{ \App\Models\Order::max('id') ?? 0 }};
            let notificationCount = 0;
            const notificationBell = document.getElementById('notification-bell');
            const notificationPanel = document.getElementById('notification-panel');
            const notificationCountBadge = document.getElementById('notification-count');
            const notificationList = document.getElementById('notification-list');
            const clearNotificationsBtn = document.getElementById('clear-notifications');
            const notificationSound = document.getElementById('notification-sound');

            console.log('Notification system initialized. Last check:', lastCheck);

            // Track seen order IDs to prevent duplicates
            const seenOrders = new Set();

            // Toggle notification panel
            notificationBell?.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationPanel?.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (notificationPanel && !notificationPanel.contains(e.target) && !notificationBell?.contains(e.target)) {
                    notificationPanel.classList.add('hidden');
                }
            });

            clearNotificationsBtn?.addEventListener('click', function() {
                notificationCount = 0;
                if (notificationCountBadge) {
                    notificationCountBadge.innerText = '0';
                    notificationCountBadge.classList.add('hidden');
                }
                if (notificationList) {
                    notificationList.innerHTML = '<div class="px-4 py-8 text-center text-gray-500 italic text-sm">No new orders</div>';
                }
                seenOrders.clear();
            });

            function checkNewOrders() {
                const url = `{{ route('admin.orders.check') }}?last_check=${encodeURIComponent(lastCheck)}&last_id=${lastId}`;
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    console.log('Polling result:', data);
                    
                    if (data.count > 0) {
                        let actualNewOrders = 0;

                        data.orders.forEach(order => {
                            if (!seenOrders.has(order.id)) {
                                seenOrders.add(order.id);
                                actualNewOrders++;

                                // Add to list
                                if (notificationCount === 0 && actualNewOrders === 1 && notificationList) {
                                    notificationList.innerHTML = '';
                                }

                                const item = document.createElement('a');
                                item.href = `{{ url('/admin/orders') }}/${order.id}`;
                                item.className = "block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0 transition-colors";
                                item.innerHTML = `
                                    <div class="flex gap-3">
                                        <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                            <i class="fas fa-shopping-bag text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900">New ${order.order_type.charAt(0).toUpperCase() + order.order_type.slice(1)} Order #${order.order_no}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">${order.customer ? order.customer.name : 'Guest'}</p>
                                            <p class="text-xs text-amber-600 mt-1">$${parseFloat(order.total_amount).toFixed(2)}</p>
                                        </div>
                                    </div>
                                `;
                                notificationList?.prepend(item);
                            }
                        });

                        if (actualNewOrders > 0) {
                            notificationCount += actualNewOrders;
                            if (notificationCountBadge) {
                                notificationCountBadge.innerText = notificationCount;
                                notificationCountBadge.classList.remove('hidden');
                            }
                            
                            // Play sound
                            notificationSound?.play().catch(e => console.log('Sound play blocked by browser. User must interact with the page first.'));

                            // Toast notification
                            if (typeof ToastMagic !== 'undefined') {
                                ToastMagic.success(`You have ${actualNewOrders} new online order(s)!`);
                            } else {
                                console.warn('ToastMagic is not defined');
                            }
                        }
                    }
                    // Update for next check
                    lastCheck = data.timestamp;
                    lastId = data.max_id || lastId;
                })
                .catch(error => console.error('Error checking orders:', error));
            }

            // Check every 10 seconds
            setInterval(checkNewOrders, 10000);
            
            // Initial check just in case
            setTimeout(checkNewOrders, 2000);
        });
    </script>
    @endauth

    {!! ToastMagic::scripts() !!}

</body>
</html>