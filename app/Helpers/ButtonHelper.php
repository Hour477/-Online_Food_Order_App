

<?php

if (!function_exists('action_btn_class')) {
    function action_btn_class(string $type = 'view'): string
    {
        $base = 'inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200';

        $styles = [
            'view'   => 'text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 border border-blue-100 dark:border-blue-800/30 hover:scale-105',
            'edit'   => 'text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/30 border border-amber-100 dark:border-amber-800/30 hover:scale-105',
            'delete' => 'text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 border border-red-100 dark:border-red-800/30 hover:scale-105',
            'status_on'  => 'text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/30 hover:scale-105',
            'status_off' => 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 bg-gray-50 dark:bg-gray-900/20 hover:bg-gray-100 dark:hover:bg-gray-900/30 border border-gray-100 dark:border-gray-800/30 hover:scale-105',
        ];

        return $base . ' ' . ($styles[$type] ?? $styles['view']);
    }
}

if (!function_exists('action_btn_icon')) {
    function action_btn_icon(string $type = 'view'): string
    {
        return match ($type) {
            'view'   => 'fa fa-regular fa-eye text-lg',
            'edit'   => 'fa-solid fa-edit text-lg',
            'delete' => 'fa-solid fa-trash-alt text-lg',
            'status_on'  => 'fa-solid fa-toggle-on text-lg', 
            'status_off' => 'fa-solid fa-toggle-off text-lg',
            default  => 'fa fa-regular fa-eye text-lg',
        };
    }
}

if (!function_exists('btn_primary_class')) {
    function btn_primary_class(): string
    {
        // Description: 'Primary button class';
        // Deactivate
        if (class_exists('dark')) {
            return '';
        } else {
            return 'inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg shadow-sm transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]';
        }
    }
}

if (!function_exists('btn_secondary_class')) {
    function btn_secondary_class(): string
    {
        return 'inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg shadow-sm transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]';
    }
}