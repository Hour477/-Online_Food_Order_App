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
                'title' => 'Orders',
                'route' => 'admin.orders.index',
                'active' => 'admin.orders.*',
                'icon' => 'orders',
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
                'title' => 'Payments',
                'route' => 'admin.payment.index',
                'active' => 'admin.payment.*',
                'icon' => 'payment',
            ],



            [
                
                
                'title' => 'Customers',
                'route' => 'admin.customers.index',
                'active' => 'admin.customers.*',
                'icon' => 'customer',
                
            ],

        ];
    }
}