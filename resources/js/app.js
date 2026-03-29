import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('app-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const toggleButton = document.getElementById('sidebar-toggle');
    const collapseButton = document.getElementById('sidebar-collapse-btn');
    const menuIcon = document.getElementById('icon-menu');
    const closeIcon = document.getElementById('icon-close');
    const collapseOpenIcon = document.getElementById('collapse-icon-open');
    const collapseClosedIcon = document.getElementById('collapse-icon-closed');
    const desktopQuery = window.matchMedia('(min-width: 1024px)');
    const COLLAPSE_KEY = 'sidebar_collapsed';
    const FOLDERS_KEY = 'sidebar_open_folders';

    let sidebarOpen = false;

    const isDesktop = () => desktopQuery.matches;

    const getOpenFolders = () => {
        try {
            const raw = localStorage.getItem(FOLDERS_KEY);
            return raw ? JSON.parse(raw) : [];
        } catch (_) {
            return [];
        }
    };

    const setOpenFolders = (keys) => {
        try {
            localStorage.setItem(FOLDERS_KEY, JSON.stringify(keys));
        } catch (_) {
            // Ignore storage failures.
        }
    };

    const applyCollapse = (collapsed) => {
        document.documentElement.classList.toggle('sidebar-collapsed', collapsed);
        if (collapseOpenIcon && collapseClosedIcon) {
            collapseOpenIcon.classList.toggle('hidden', collapsed);
            collapseClosedIcon.classList.toggle('hidden', !collapsed);
        }
        localStorage.setItem(COLLAPSE_KEY, collapsed ? 'true' : 'false');
    };

    const setMobileState = (open) => {
        if (!sidebar || !backdrop) return;

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

    const setFolderState = (folder, open) => {
        if (!folder) return;

        const button = folder.querySelector('.sidebar-folder-toggle');
        const submenu = folder.querySelector('.orders-submenu');
        const chevron = folder.querySelector('.chevron');

        folder.classList.toggle('is-open', open);
        button?.setAttribute('aria-expanded', open ? 'true' : 'false');
        chevron?.classList.toggle('rotate-180', open);

        if (submenu) {
            submenu.classList.toggle('is-open', open);
            submenu.style.maxHeight = open ? `${submenu.scrollHeight}px` : null;
        }
    };

    const syncFoldersToStorage = () => {
        if (!sidebar) return;

        const openKeys = [];
        sidebar.querySelectorAll('.sidebar-folder').forEach((folder) => {
            const key = folder.querySelector('.sidebar-folder-toggle')?.dataset.folder;
            if (key && folder.classList.contains('is-open')) {
                openKeys.push(key);
            }
        });

        setOpenFolders(openKeys);
    };

    const restoreFolderOpenState = () => {
        if (!sidebar) return;

        const savedFolders = getOpenFolders();
        const activeFolder = sidebar.querySelector('.sidebar-submenu-link.bg-amber-50')?.closest('.sidebar-folder');

        sidebar.querySelectorAll('.sidebar-folder').forEach((folder) => {
            const key = folder.querySelector('.sidebar-folder-toggle')?.dataset.folder;
            const shouldBeOpen = activeFolder === folder || (key && savedFolders.includes(key));
            setFolderState(folder, Boolean(shouldBeOpen));
        });
    };

    const syncByViewport = () => {
        if (isDesktop()) {
            setMobileState(false);
            applyCollapse(localStorage.getItem(COLLAPSE_KEY) === 'true');
            restoreFolderOpenState();
            return;
        }

        document.documentElement.classList.remove('sidebar-collapsed');
        setMobileState(false);
    };

    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            if (isDesktop()) {
                applyCollapse(!document.documentElement.classList.contains('sidebar-collapsed'));
                return;
            }

            setMobileState(!sidebarOpen);
        });
    }

    if (collapseButton) {
        collapseButton.addEventListener('click', () => {
            applyCollapse(!document.documentElement.classList.contains('sidebar-collapsed'));
        });
    }

    backdrop?.addEventListener('click', () => setMobileState(false));
    desktopQuery.addEventListener('change', syncByViewport);

    if (sidebar) {
        sidebar.querySelectorAll('.sidebar-folder-toggle').forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                if (document.documentElement.classList.contains('sidebar-collapsed')) {
                    return;
                }

                const folder = button.closest('.sidebar-folder');
                if (!folder) return;

                const shouldOpen = !folder.classList.contains('is-open');

                sidebar.querySelectorAll('.sidebar-folder').forEach((otherFolder) => {
                    setFolderState(otherFolder, otherFolder === folder ? shouldOpen : false);
                });

                syncFoldersToStorage();
            });
        });
    }

    const deleteModal = document.getElementById('delete-modal');
    const deleteModalBackdrop = document.getElementById('delete-modal-backdrop');
    const deleteModalContent = document.getElementById('delete-modal-content');
    const deleteModalCancel = document.getElementById('delete-modal-cancel');
    const deleteModalForm = document.getElementById('delete-modal-form');
    const deleteModalMessage = document.getElementById('delete-modal-message');

    if (deleteModal && deleteModalBackdrop && deleteModalContent && deleteModalCancel && deleteModalForm) {
        window.showDeleteModal = (url, message = null) => {
            deleteModalForm.action = url;
            deleteModalMessage.textContent =
                message || 'Are you sure you want to delete this item? This action cannot be undone.';

            deleteModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            requestAnimationFrame(() => {
                deleteModalBackdrop.classList.replace('opacity-0', 'opacity-100');
                deleteModalContent.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                deleteModalContent.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            });
        };

        const hideDeleteModal = () => {
            deleteModalBackdrop.classList.replace('opacity-100', 'opacity-0');
            deleteModalContent.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            deleteModalContent.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

            setTimeout(() => {
                deleteModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        };

        deleteModalCancel.addEventListener('click', hideDeleteModal);
        deleteModalBackdrop.addEventListener('click', hideDeleteModal);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                hideDeleteModal();
            }
        });
    }

    syncByViewport();
});



