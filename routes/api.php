
<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiOrderController;
use App\Http\Controllers\Api\ProductLikeController;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products/{product}/like', [ProductLikeController::class, 'like']);
    Route::delete('/products/{product}/unlike', [ProductLikeController::class, 'unlike']);
    Route::get('/user/liked-products', [ProductLikeController::class, 'likedProducts']);
});