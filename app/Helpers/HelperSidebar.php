<?php

namespace App\Helpers;

class HelperSidebar
{
    public static function menus(): array
    {
        return [

            [
                'title' => 'Dashboard',
                'route' => 'admin.dashboard',
                'active' => 'admin.dashboard',
                'icon' => 'home',
            ],

            [
                'title'   => 'Users & Roles',
                'icon'    => 'users',
                'folder'  => 'users-roles',
                'active'  => 'admin.users.*|admin.roles.*',
                'children' => [
                    ['title' => 'Users',  'route' => 'admin.users.index',  'active' => 'admin.users.*'],
                    ['title' => 'Roles',  'route' => 'admin.roles.index',  'active' => 'admin.roles.*'],
                ],
            ],

            [
                'title' => 'Categories',
                'route' => 'admin.categories.index',
                'active' => 'admin.categories.*',
                'icon' => 'tag',
                
            ],

            [
                'title' => 'Menu Items',
                'route' => 'admin.menu_items.index',
                'active' => 'admin.menu_items.*',
                'icon' => 'menu',
            ],

            [
                'title'   => 'Orders',
                'icon'    => 'orders',
                'folder'  => 'orders',
                'active'  => 'admin.orders.*',
                'children' => [
                    ['title' => 'All Orders', 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => []],
                    ['title' => 'Pending', 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'pending'], 'badge' => 'pending'],
                    ['title' => 'Confirmed', 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'confirmed'], 'badge' => 'confirmed'],
                    ['title' => 'Delivered', 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'delivered'], 'badge' => 'delivered'],
                    ['title' => 'Completed', 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'completed'], 'badge' => 'completed'],
                    ['title' => 'Refunded', 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'refunded'], 'badge' => 'refunded'],
                    ['title' => 'Cancelled', 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'cancelled'], 'badge' => 'cancelled'],
                ],
            ],
            


            [
                'title' => 'Tables',
                'route' => 'admin.tables.index',
                'active' => 'admin.tables.*',
                'icon' => 'table',
            ],

            [
                'title' => 'Reports',
                'route' => 'admin.reports.index',
                'active' => 'admin.reports.*',
                'icon' => 'chart',
            ],

            [
                'title'   => 'Payments',
                'icon'    => 'payment',
                'folder'  => 'payments',
                'active'  => 'admin.payment.*',
                'children' => [
                    ['title' => 'All Payments', 'route' => 'admin.payment.index', 'active' => 'admin.payment.index'],
                    ['title' => 'Waiting Payment', 'route' => 'admin.payment.index', 'active' => 'admin.payment.index', 'params' => ['status' => 'pending']],
                    ['title' => 'Success', 'route' => 'admin.payment.index', 'active' => 'admin.payment.index', 'params' => ['status' => 'paid']],
                    ['title' => 'Error', 'route' => 'admin.payment.index', 'active' => 'admin.payment.index', 'params' => ['status' => 'failed']],
                    ['title' => 'Refunded', 'route' => 'admin.payment.index', 'active' => 'admin.payment.index', 'params' => ['status' => 'refunded']],
                ],
            ],



            [
                'title'   => 'Customers',
                'icon'    => 'customer',
                'folder'  => 'customers',
                'active'  => 'admin.customers.*',
                'children' => [
                    ['title' => 'Top Customer', 'route' => 'admin.customers.best', 'active' => 'admin.customers.best'],
                    ['title' => 'All Customer', 'route' => 'admin.customers.index', 'active' => 'admin.customers.index'],
                ],
            ],

            [
                'title' => 'Settings',
                'route' => 'admin.settings.index',
                'folder' => 'settings',
                'active' => 'admin.settings.*',
                'icon' => 'settings',
            ],

        ];
    }
}