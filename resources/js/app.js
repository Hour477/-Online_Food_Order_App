import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('app-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const toggleButton = document.getElementById('sidebar-toggle');
    const menuIcon = document.getElementById('icon-menu');
    const closeIcon = document.getElementById('icon-close');
    const desktopQuery = window.matchMedia('(min-width: 1024px)');

    if (!sidebar || !backdrop || !toggleButton || !menuIcon || !closeIcon) {
        return;
    }

    let sidebarOpen = false;

    const setMobileState = (open) => {
        sidebarOpen = open;
        sidebar.classList.toggle('translate-x-0', open);
        sidebar.classList.toggle('-translate-x-full', !open);
        backdrop.classList.toggle('opacity-100', open);
        backdrop.classList.toggle('pointer-events-auto', open);
        backdrop.classList.toggle('opacity-0', !open);
        backdrop.classList.toggle('pointer-events-none', !open);
        menuIcon.classList.toggle('hidden', open);
        closeIcon.classList.toggle('hidden', !open);
    };

    const setDesktopState = (collapsed) => {
        sidebar.classList.toggle('sidebar-collapsed', collapsed);
        localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');
    };

    const isDesktop = () => desktopQuery.matches;

    const syncByViewport = () => {
        if (isDesktop()) {
            setMobileState(false);
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            setDesktopState(collapsed);
        } else {
            sidebar.classList.remove('sidebar-collapsed');
            setMobileState(false);
        }
    };

    toggleButton.addEventListener('click', () => {
        if (isDesktop()) {
            const collapsed = !sidebar.classList.contains('sidebar-collapsed');
            setDesktopState(collapsed);
            return;
        }

        setMobileState(!sidebarOpen);
    });

    backdrop.addEventListener('click', () => setMobileState(false));
    desktopQuery.addEventListener('change', syncByViewport);

    document.querySelectorAll('.orders-toggle-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const menu = button.closest('.orders-menu');
            if (!menu) return;

            const submenu = menu.querySelector('.orders-submenu');
            const chevron = menu.querySelector('.chevron');
            const indicator = menu.querySelector('.orders-indicator');
            const isOpen = submenu ? submenu.classList.contains('hidden') : false;
            const shouldOpen = isOpen;

            if (submenu) {
                submenu.classList.toggle('hidden', !shouldOpen);
            }

            if (chevron) {
                chevron.classList.toggle('rotate-180', shouldOpen);
            }

            if (indicator) {
                indicator.classList.toggle('scale-y-100', shouldOpen);
            }

            menu.classList.toggle('is-open', shouldOpen);
            button.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
        });
    });

    syncByViewport();
});
