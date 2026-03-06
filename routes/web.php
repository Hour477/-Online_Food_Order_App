<?php

use App\Http\Controllers\Admin\CategoryController; // Moved to Admin
use App\Http\Controllers\Admin\CustomerController; // Moved to Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuItemController; // Moved to Admin
use App\Http\Controllers\Admin\ReportController;   // Moved to Admin
use App\Http\Controllers\Admin\TableController;    // Moved to Admin
use App\Http\Controllers\Auth\AuthController;
// use App\Http\Controllers\Waiter\CartController;
use App\Http\Controllers\UserController; // Still in base folder
use App\Http\Controllers\Waiter\OrderController;      // Moved to Waiter
use App\Http\Controllers\Waiter\OrderItemsController; // Moved to Waiter
use App\Http\Controllers\Waiter\CartController; // Waiter cart API
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| AUTH REQUIRED
|--------------------------------------------------------------------------
*/

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
    Route::middleware(['role:waiter,waiter'])->group(function () {
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    });

    // 2. Define shared routes (Index & Show) for BOTH Admin and Waiter
    // Assuming your role middleware accepts multiple roles like 'role:admin,waiter'
    Route::middleware(['role:admin,waiter'])->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{orders}/show', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{orders}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');


    });

    // 3. Admin-only routes
    Route::middleware(['role:admin,admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('menu_items', MenuItemController::class);
        Route::resource('tables', TableController::class);
        Route::resource('customers', CustomerController::class); 
      
        Route::resource('orders', OrderController::class)->only(['index', 'show','create', 'store']);
        
        
    
        Route::get('/kitchen', [OrderController::class, 'kitchen'])->name('kitchen');
        Route::get('/reports/orders', [ReportController::class, 'orders'])->name('reports.orders');
        Route::get('/reports/income', [ReportController::class, 'income'])->name('reports.income');
    });

    // 4. Waiter-only remaining routes
    Route::middleware(['role:waiter,waiter'])->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::resource('order_items', OrderItemsController::class)->only(['store', 'destroy']);
        Route::patch('/order-items/{orderItem}/qty', [OrderItemsController::class, 'updateQty'])->name('order-items.qty');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        
        // cart endpoints used during order creation
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

        Route::resource('tables', TableController::class)->only(['index', 'show', 'edit', 'update']);
        Route::patch('/tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.status');
       
    });
    

    

});