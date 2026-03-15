import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('app-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const toggleButton = document.getElementById('sidebar-toggle');
    const menuIcon = document.getElementById('icon-menu');
    const closeIcon = document.getElementById('icon-close');
    const desktopQuery = window.matchMedia('(min-width: 1024px)');

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

    if (sidebar && backdrop && toggleButton && menuIcon && closeIcon) {
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
    }

    document.querySelectorAll('.sidebar-folder-toggle').forEach((button) => {
        button.addEventListener('click', () => {
            const menu = button.closest('.sidebar-folder');
            if (!menu) return;

            // Keep only one folder open at a time.
            sidebar?.querySelectorAll('.sidebar-folder').forEach((otherMenu) => {
                if (otherMenu === menu) return;

                const otherSubmenu = otherMenu.querySelector('.sidebar-submenu');
                const otherChevron = otherMenu.querySelector('.chevron');
                const otherToggle = otherMenu.querySelector('.sidebar-folder-toggle');

                if (otherSubmenu) {
                    otherSubmenu.classList.add('hidden');
                }
                if (otherChevron) {
                    otherChevron.classList.remove('rotate-180');
                }
                if (otherToggle) {
                    otherToggle.setAttribute('aria-expanded', 'false');
                }
                otherMenu.classList.remove('is-open');
            });

            const submenu = menu.querySelector('.sidebar-submenu');
            const chevron = menu.querySelector('.chevron');
            const indicator = menu.querySelector('.folder-indicator');

            if (submenu) {
                submenu.classList.remove('hidden');
            }

            if (chevron) {
                chevron.classList.add('rotate-180');
            }

            if (indicator) {
                indicator.classList.add('scale-y-100');
            }

            menu.classList.add('is-open');
            button.setAttribute('aria-expanded', 'true');
        });
    });

    const setSingleSidebarIndicator = (sourceEl) => {
        if (!sidebar) return;

        const indicators = sidebar.querySelectorAll('.sidebar-link span.absolute, .folder-indicator');
        indicators.forEach((indicator) => {
            indicator.classList.remove('bg-amber-600', 'scale-y-75', 'scale-y-100');
            indicator.classList.add('scale-y-0');
        });

        const parentMenu = sourceEl.closest('.sidebar-folder');
        const targetIndicator = parentMenu
            ? parentMenu.querySelector('.folder-indicator')
            : sourceEl.querySelector('span.absolute');

        if (targetIndicator) {
            targetIndicator.classList.remove('scale-y-0');
            targetIndicator.classList.add('bg-amber-600', 'scale-y-75');
        }
    };

    if (sidebar) {
        const getSidebarKey = (element) => {
            if (element.classList.contains('sidebar-folder-toggle')) {
                return element.dataset.folder || null;
            }
            if (element.tagName === 'A') {
                return element.getAttribute('href');
            }
            return null;
        };

        const applyIndicatorByKey = (key) => {
            if (!key) return;

            let target = sidebar.querySelector(`.sidebar-folder-toggle[data-folder="${key}"]`);
            if (!target) {
                target = sidebar.querySelector(`.sidebar-link[href="${key}"], .sidebar-submenu a[href="${key}"]`);
            }

            if (target) {
                setSingleSidebarIndicator(target);
            }
        };

        const normalizePath = (value) => {
            try {
                const url = new URL(value, window.location.origin);
                return url.pathname.replace(/\/+$/, '') || '/';
            } catch (_) {
                return null;
            }
        };

        const getCurrentLinkByPath = () => {
            const currentPath = normalizePath(window.location.href);
            if (!currentPath) return null;

            const allLinks = sidebar.querySelectorAll('.sidebar-link[href], .sidebar-submenu a[href]');
            for (const link of allLinks) {
                const linkPath = normalizePath(link.getAttribute('href'));
                if (linkPath && linkPath === currentPath) {
                    return link;
                }
            }
            return null;
        };

        sidebar.querySelectorAll('.sidebar-link, .sidebar-folder-toggle, .sidebar-submenu a').forEach((item) => {
            item.addEventListener('click', () => {
                const key = getSidebarKey(item);
                if (key) {
                    localStorage.setItem('activeSidebarKey', key);
                }
                setSingleSidebarIndicator(item);
            });
        });

        const currentRouteActive =
            getCurrentLinkByPath() ||
            sidebar.querySelector(
                '.sidebar-submenu a.bg-amber-600\\/10, .sidebar-link.bg-amber-600, .sidebar-link.bg-amber-700'
            );

        if (currentRouteActive) {
            const key = getSidebarKey(currentRouteActive);
            if (key) {
                localStorage.setItem('activeSidebarKey', key);
            }

            // Ensure parent folder is open for active submenu links on load.
            const parentFolder = currentRouteActive.closest('.sidebar-folder');
            if (parentFolder) {
                const parentToggle = parentFolder.querySelector('.sidebar-folder-toggle');
                if (parentToggle) {
                    parentToggle.click();
                }
            }

            setSingleSidebarIndicator(currentRouteActive);
        } else {
            applyIndicatorByKey(localStorage.getItem('activeSidebarKey'));
        }
    }

    if (sidebar && backdrop && toggleButton && menuIcon && closeIcon) {
        syncByViewport();
    }
});
