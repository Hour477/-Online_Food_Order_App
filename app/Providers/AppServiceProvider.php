<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Tailwind-styled pagination for all ->links() calls
        Paginator::defaultView('pagination::tailwind');
        Paginator::defaultSimpleView('pagination::simple-tailwind');

        // Share order status counts with the sidebar (and all other views)
        view()->composer('admin.layouts.sidebar', function ($view) {
            $view->with('orderStatusCounts', \App\Models\Order::getStatusCounts());
        });
    }
}
