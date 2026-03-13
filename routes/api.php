
<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiOrderController;


Route::get('/orders', [ApiOrderController::class, 'index']);
Route::get('/orders/{id}', [ApiOrderController::class, 'show']);