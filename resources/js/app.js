import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('app-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const toggleButton = document.getElementById('sidebar-toggle');
    const menuIcon = document.getElementById('icon-menu');
    const closeIcon = document.getElementById('icon-close');
    const desktopOpenIcon = document.getElementById('collapse-icon-open');
    const desktopClosedIcon = document.getElementById('collapse-icon-closed');
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
        if (menuIcon) menuIcon.classList.toggle('hidden', open);
        if (closeIcon) closeIcon.classList.toggle('hidden', !open);
    };

    const setDesktopState = (collapsed) => {
        document.documentElement.classList.toggle('sidebar-collapsed', collapsed);
        localStorage.setItem('sidebar_collapsed', collapsed ? 'true' : 'false');
    };

    const isDesktop = () => desktopQuery.matches;

    const syncByViewport = () => {
        if (isDesktop()) {
            setMobileState(false);
            const collapsed = localStorage.getItem('sidebar_collapsed') === 'true';
            setDesktopState(collapsed);
        } else {
            document.documentElement.classList.remove('sidebar-collapsed');
            setMobileState(false);
        }
    };

    if (sidebar && backdrop && toggleButton) {
        toggleButton.addEventListener('click', () => {
            if (isDesktop()) {
                const isCollapsed = document.documentElement.classList.contains('sidebar-collapsed');
                setDesktopState(!isCollapsed);
                return;
            }

            setMobileState(!sidebarOpen);
        });

        backdrop.addEventListener('click', () => setMobileState(false));
        desktopQuery.addEventListener('change', syncByViewport);

        // Initial sync
        syncByViewport();
    }

    // Delete Modal Logic
    const deleteModal = document.getElementById('delete-modal');
    const deleteModalBackdrop = document.getElementById('delete-modal-backdrop');
    const deleteModalContent = document.getElementById('delete-modal-content');
    const deleteModalCancel = document.getElementById('delete-modal-cancel');
    const deleteModalForm = document.getElementById('delete-modal-form');
    const deleteModalMessage = document.getElementById('delete-modal-message');

    if (deleteModal && deleteModalBackdrop && deleteModalContent && deleteModalCancel && deleteModalForm) {
        // Show Modal
        window.showDeleteModal = function(url, message = null) {
            deleteModalForm.action = url;
            if (message) {
                deleteModalMessage.textContent = message;
            } else {
                deleteModalMessage.textContent = 'Are you sure you want to delete this item? This action cannot be undone.';
            }
            
            // Show container
            deleteModal.classList.remove('hidden');
            // Prevent body scroll
            document.body.classList.add('overflow-hidden');

            // Animate in (using a tiny timeout to ensure classes are applied after hidden is removed)
            requestAnimationFrame(() => {
                deleteModalBackdrop.classList.replace('opacity-0', 'opacity-100');
                deleteModalContent.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                deleteModalContent.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            });
        };

        // Hide Modal
        const hideDeleteModal = function() {
            // Animate out
            deleteModalBackdrop.classList.replace('opacity-100', 'opacity-0');
            deleteModalContent.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            deleteModalContent.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

            // Wait for animation to finish before hiding container
            setTimeout(() => {
                deleteModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 300); // Match duration-300
        };

        deleteModalCancel.addEventListener('click', hideDeleteModal);
        deleteModalBackdrop.addEventListener('click', hideDeleteModal);

        // Escape key to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                hideDeleteModal();
            }
        });
    }

    // Handle folder toggle clicks (accordion behavior)
    document.querySelectorAll('.sidebar-folder-toggle').forEach((button) => {
        button.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent event from bubbling up

            // If sidebar is collapsed, don't allow opening folders
            if (document.documentElement.classList.contains('sidebar-collapsed')) {
                return;
            }

            const menu = button.closest('.sidebar-folder');
            if (!menu) return;

            const submenu = menu.querySelector('.orders-submenu');
            const chevron = menu.querySelector('.chevron');
            const isOpen = submenu && submenu.classList.contains('is-open');

            // Close all OTHER folders first (accordion behavior)
            sidebar?.querySelectorAll('.sidebar-folder').forEach((otherMenu) => {
                if (otherMenu === menu) return;

                const otherSubmenu = otherMenu.querySelector('.orders-submenu');
                const otherChevron = otherMenu.querySelector('.chevron');
                const otherToggle = otherMenu.querySelector('.sidebar-folder-toggle');

                if (otherSubmenu) {
                    otherSubmenu.classList.remove('is-open');
                    otherSubmenu.style.maxHeight = null;
                }
                if (otherChevron) {
                    otherChevron.classList.remove('rotate-180');
                }
                if (otherToggle) {
                    otherToggle.setAttribute('aria-expanded', 'false');
                }
                otherMenu.classList.remove('is-open');
            });

            // Toggle current folder
            if (submenu) {
                if (isOpen) {
                    // Close
                    submenu.classList.remove('is-open');
                    submenu.style.maxHeight = null;
                } else {
                    // Open
                    submenu.classList.add('is-open');
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                }
            }

            if (chevron) {
                chevron.classList.toggle('rotate-180', !isOpen);
            }

            const indicator = menu.querySelector('.folder-indicator');
            if (indicator) {
                indicator.classList.toggle('scale-y-100', !isOpen);
            }

            menu.classList.toggle('is-open', !isOpen);
            button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');

            // Save state to localStorage
            syncFoldersToStorage();
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
            item.addEventListener('click', (e) => {
                const key = getSidebarKey(item);
                if (key) {
                    localStorage.setItem('activeSidebarKey', key);
                }
                setSingleSidebarIndicator(item);

                // Don't bubble up the event for submenu links
                if (item.classList.contains('sidebar-submenu-link') || item.closest('.orders-submenu')) {
                    e.stopPropagation();
                }
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
                if (parentToggle && !parentFolder.classList.contains('is-open')) {
                    // Trigger click to open parent folder
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



// Toggle sidebar for mobile

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
})();

