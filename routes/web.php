<?php

use App\Http\Controllers\Admin\CategoryController; // Moved to Admin
use App\Http\Controllers\Admin\CustomerController; // Moved to Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuItemController; // Moved to Admin
use App\Http\Controllers\Admin\ReportController;   // Moved to Admin
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TableController;    // Moved to Admin
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\CustomerAuthController;
use App\Http\Controllers\Frontend\CustomerPortalController;
use App\Http\Controllers\Kitchen\KitchenController;
// use App\Http\Controllers\Waiter\CartController;
use App\Http\Controllers\UserController; // Still in base folder
// use App\Http\Controllers\Api\ApiOrderController;

use App\Http\Controllers\Admin\OrderController;      // Moved to Waiter
use App\Http\Controllers\Admin\OrderItemsController; // Moved to Waiter
use App\Http\Controllers\Admin\CartController; // Waiter cart API
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| AUTH REQUIRED
|--------------------------------------------------------------------------
*/

Route::prefix('frontend')->name('frontend.')->group(function () {
    // Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    // Route::post('/otp/send', [CustomerAuthController::class, 'sendOtp'])->name('otp.send');
    // Route::get('/otp/verify', [CustomerAuthController::class, 'showVerifyForm'])->name('verify.form');
    // Route::post('/otp/verify', [CustomerAuthController::class, 'verifyOtp'])->name('verify.submit');

    Route::middleware('frontend.customer.auth')->group(function () {
        Route::get('/dashboard', [CustomerPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders/{order}/track', [CustomerPortalController::class, 'track'])->name('orders.track');
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
    });
});

// Public auth routes
Route::middleware(['guest'])->group(function () {
    
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    // note check condition login user 
    Route::post('/', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

});
Route::post('/login', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware(['auth'])->group(function () {

    // 1. Put the CREATE/STORE routes FIRST (Specific routes)
    

    // 3. Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/dashboard', [DashboardController::class,'totalOrders'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('menu_items', MenuItemController::class);
        Route::resource('tables', TableController::class);
        Route::resource('customers', CustomerController::class); 
      
        // Route::resource('/api/orders', OrderController::class);
        // Route::resource('/api/orders', ApiOrderController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('settings', SettingController::class);
        
        
    
        Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen');
        Route::get('/reports/orders', [ReportController::class, 'orders'])->name('reports.orders');
        Route::get('/reports/income', [ReportController::class, 'income'])->name('reports.income');
        Route::resource('payment', ReportController::class);
    });

   
    

    

});
