<?php

namespace App\Helpers;

class HelperSidebar
{
    public static function menus(): array
    {
        return [

            [
                'title' => __('app.dashboard'),
                'route' => 'admin.dashboard',
                'active' => 'admin.dashboard',
                'icon' => 'home',
            ],

           

            [
                'title' => __('app.categories'),
                'route' => 'admin.categories.index',
                'active' => 'admin.categories.*',
                'icon' => 'tag',
                
            ],

            
            [
                'title' => __('app.menu_items'),
                'route' => 'admin.menu_items.index',
                'active' => 'admin.menu_items.*',
                'icon' => 'menu',
            ],

            [
                'title'   => __('app.orders'),
                'icon'    => 'orders',
                'folder'  => 'orders',
                'active'  => 'admin.orders.*',
                'children' => [
                    ['title' => __('app.all_orders'), 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => []],
                    ['title' => __('app.pending'), 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'pending'], 'badge' => 'pending'],
                    ['title' => __('app.confirmed'), 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'confirmed'], 'badge' => 'confirmed'],
                    ['title' => __('app.delivered'), 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'delivered'], 'badge' => 'delivered'],
                    ['title' => __('app.completed'), 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'completed'], 'badge' => 'completed'],
                    ['title' => __('app.refunded'), 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'refunded'], 'badge' => 'refunded'],
                    ['title' => __('app.cancelled'), 'route' => 'admin.orders.index', 'active' => 'admin.orders.index', 'params' => ['status' => 'cancelled'], 'badge' => 'cancelled'],
                ],
            ],


            


            [
                'title' => __('app.tables'),
                'route' => 'admin.tables.index',
                'active' => 'admin.tables.*',
                'icon' => 'table',
            ],

            [
                'title' => __('app.reports'),
                'route' => 'admin.reports.index',
                'active' => 'admin.reports.*',
                'icon' => 'chart',
            ],

            [
                'title'   => __('app.payments'),
                'icon'    => 'payment',
                'folder'  => 'payments',
                'active'  => 'admin.payment.*',
                'children' => [
                    ['title' => __('app.all_payments'), 'route' => 'admin.payment.index', 'active' => 'admin.payment.index'],
                    ['title' => __('app.waiting_payment'), 'route' => 'admin.payment.index', 'active' => 'admin.payment.index', 'params' => ['status' => 'pending']],
                    ['title' => __('app.success'), 'route' => 'admin.payment.index', 'active' => 'admin.payment.index', 'params' => ['status' => 'paid']],
                    ['title' => __('app.error'), 'route' => 'admin.payment.index', 'active' => 'admin.payment.index', 'params' => ['status' => 'failed']],
                    ['title' => __('app.refunded'), 'route' => 'admin.payment.index', 'active' => 'admin.payment.index', 'params' => ['status' => 'refunded']],
                ],
            ],



            [
                'title'   => __('app.customers'),
                'icon'    => 'customer',
                'folder'  => 'customers',
                'active'  => 'admin.customers.*',
                'children' => [
                    ['title' => __('app.top_customer'), 'route' => 'admin.customers.best', 'active' => 'admin.customers.best'],
                    ['title' => __('app.all_customer'), 'route' => 'admin.customers.index', 'active' => 'admin.customers.index'],
                ],
            ], 


            [
                'title' => __('app.banners'),
                'route' => 'admin.banners.index',
                'active' => 'admin.banners.*',
                'icon' => 'image',
            ],

             [
                'title'   => __('app.users_and_roles'),
                'icon'    => 'users',
                'folder'  => 'users-roles',
                'active'  => 'admin.users.*|admin.roles.*',
                'children' => [
                    ['title' => __('app.users'),  'route' => 'admin.users.index',  'active' => 'admin.users.*'],
                    ['title' => __('app.roles'),  'route' => 'admin.roles.index',  'active' => 'admin.roles.*'],
                ],
            ],


            [
                'title' => __('app.settings'),
                'route' => 'admin.settings.index',
                'folder' => 'settings',
                'active' => 'admin.settings.*',
                'icon' => 'settings',
            ],

        ];
    }
}