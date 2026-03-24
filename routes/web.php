<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LanguageController;

// Admin controllers
use App\Http\Controllers\Admin\{
    DashboardController,
    UserController,
    CategoryController,
    MenuItemController,
    TableController,
    CustomerController,
    OrderController as AdminOrderController,
    SettingController,
    ReportController,
    RoleController,
    OrderItemsController,
    CheckoutController,
    PaymentController,
    BannerController,
};

// Customer (public) controllers
use App\Http\Controllers\CustomerOrder\{
    customerMenuController,
    customerCartController,
    customerCheckoutController,
    customerOrderController
};



Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Public Customer Routes (no login required)
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/menu');

/*
|--------------------------------------------------------------------------
| Language Switcher Route
|--------------------------------------------------------------------------
*/
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Handle incorrect URL pattern (for compatibility)
Route::get('/{locale}/language', function($locale) {
    return redirect()->route('language.switch', $locale);
})->where('locale', 'en|km');

Route::prefix('menu')->name('customerOrder.')->group(function () {
    Route::get('/', [customerMenuController::class, 'index'])->name('menu.index');
});

Route::prefix('cart')->name('customerOrder.cart.')->group(function () {
    Route::get('/',     [customerCartController::class, 'index'])->name('index');
    Route::post('/add',    [customerCartController::class, 'add'])->name('add');
    Route::post('/update', [customerCartController::class, 'update'])->name('update');
    Route::post('/remove', [customerCartController::class, 'remove'])->name('remove');
    Route::post('/clear',  [customerCartController::class, 'clear'])->name('clear');
    Route::post('/reorder', [customerCartController::class, 'reorder'])->name('reorder');
});

Route::prefix('checkout')->name('customerOrder.checkout.')->middleware('auth')->group(function () {
    Route::get('/',           [customerCheckoutController::class, 'index'])->name('index');
    Route::post('/place',     [customerCheckoutController::class, 'place'])->name('place');
    Route::get('/confirmation', [customerCheckoutController::class, 'confirmation'])->name('confirmation');
    Route::get('/khqr-payment/{order}', [customerCheckoutController::class, 'khqrPayment'])->name('khqr-payment');
    Route::get('/khqr-status/{order}', [customerCheckoutController::class, 'checkKHQRStatus'])->name('khqr-status');
    Route::post('/khqr-cancel/{order}', [customerCheckoutController::class, 'cancelKHQRPayment'])->name('khqr-cancel');
});

Route::get('/orders/history', [customerOrderController::class, 'history'])
    ->middleware('auth')                                      // ← only logged-in customers
    ->name('customerOrder.orders.history');

/*
|--------------------------------------------------------------------------
| Admin Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])

    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Style Guide
        
        Route::post('/dashboard/stats', [DashboardController::class, 'totalOrders'])->name('dashboard.stats');

        Route::resource('user',        UserController::class);
        Route::resource('categories',   CategoryController::class);
        Route::get('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        Route::resource('menu_items',   MenuItemController::class);     // ← hyphen, convention
        

        Route::resource('tables',       TableController::class);
        
        // Custom customer routes MUST be before resource
        Route::get('customers/best',   [CustomerController::class, 'best'])->name('customers.best');
        Route::get('customers/recent', [CustomerController::class, 'recent'])->name('customers.recent');
        Route::resource('customers',    CustomerController::class);

        Route::resource('orders', AdminOrderController::class);
        Route::post('orders/{order}/checkout', [AdminOrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('orders/{order}/payment', [AdminOrderController::class, 'processPayment'])->name('orders.payment');
        Route::get('orders/{order}/receipt', [AdminOrderController::class, 'generateReceipt'])->name('orders.receipt');

        Route::get('orders-check', [AdminOrderController::class, 'checkNewOrders'])->name('orders.check');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        Route::post('orders/{order}/items', [OrderItemsController::class, 'store'])->name('orders.items.store');




        Route::get('orders/{order}/items/create', [OrderItemsController::class, 'create'])->name('order-items.create');
        Route::patch('order-items/{orderItem}/qty', [OrderItemsController::class, 'updateQty'])->name('order-items.qty');
        Route::delete('order-items/{orderItem}', [OrderItemsController::class, 'destroy'])->name('order-items.destroy');




              // ← keep only one
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);



        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'reportsIndex'])->name('index');
            Route::get('/orders', [ReportController::class, 'orders'])->name('orders');
            Route::get('/income', [ReportController::class, 'income'])->name('income');
        });

        Route::resource('payment', PaymentController::class);
        Route::resource('settings', SettingController::class);
        Route::resource('banners', BannerController::class);
        Route::get('banners/{id}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
    });
