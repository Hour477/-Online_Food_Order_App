<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// ── Admin controllers ────────────────────────────────────────
use App\Http\Controllers\Admin\{
    DashboardController,
    UserController,
    CategoryController,
    MenuItemController,
    TableController,
    CustomerController,
    OrderController as AdminOrderController,
    SettingController,
    ReportController
};

// ── Customer (public) controllers ────────────────────────────
use App\Http\Controllers\CustomerOrder\{
    customerMenuController,
    customerCartController,
    customerCheckoutController,
    customerOrderController
};

// ── Optional: Waiter/Staff area ──────────────────────────────
// use App\Http\Controllers\Waiter\{
//     OrderController as WaiterOrderController,
//     CartController as WaiterCartController,
//     // ...
// };

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Public Customer Routes (no auth required)
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/menu');
Route::prefix('menu')->name('customerOrder.')->group(function () {
    Route::get('/',[customerMenuController::class, 'index'])->name('menu.index');
});

Route::prefix('cart')->name('customerOrder.cart.')->group(function () {
    Route::get('/',     [customerCartController::class, 'index'])->name('index');
    Route::post('/add',    [customerCartController::class, 'add'])->name('add');
    Route::post('/update', [customerCartController::class, 'update'])->name('update');
    Route::post('/remove', [customerCartController::class, 'remove'])->name('remove');
    Route::post('/clear',  [customerCartController::class, 'clear'])->name('clear');
    Route::post('/reorder',[customerCartController::class, 'reorder'])->name('reorder');
});

Route::prefix('checkout')->name('customerOrder.checkout.')->group(function () {
    Route::get('/',           [customerCheckoutController::class, 'index'])->name('index');
    Route::post('/place',     [customerCheckoutController::class, 'place'])->name('place');
    Route::get('/confirmation',[customerCheckoutController::class, 'confirmation'])->name('confirmation');
});

Route::get('/orders/history', [customerOrderController::class, 'history'])
    ->name('customerOrder.orders.history');

/*
|--------------------------------------------------------------------------
| Admin Area (auth + role:admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/stats', [DashboardController::class, 'totalOrders'])->name('dashboard.stats');

    Route::resource('users',        UserController::class);
    Route::resource('categories',   CategoryController::class);
    Route::resource('menu_items',   MenuItemController::class);
    Route::resource('tables',       TableController::class);
    Route::resource('customers',    CustomerController::class);
    Route::resource('orders',       AdminOrderController::class);
    Route::resource('settings',     SettingController::class);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/orders', [ReportController::class, 'orders'])->name('orders');
        Route::get('/income', [ReportController::class, 'income'])->name('income');
    });

    // If you still use payment resource (uncommon naming)
    Route::resource('payment', ReportController::class);
    Route::resource('settings', ReportController::class);
});