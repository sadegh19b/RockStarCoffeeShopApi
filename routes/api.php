<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->as('api.v1.')->group(function () {
    Route::get('products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products');
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

        Route::post('order', [\App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
        Route::get('order/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('order.show');

        Route::middleware('is_admin')->group(function () {
            Route::patch('order/{order}/status', [\App\Http\Controllers\OrderStatusController::class, 'update'])->name('order.status.update');
            Route::patch('orders/status', [\App\Http\Controllers\OrderStatusController::class, 'updateAll'])->name('orders.status.update.all');
        });
    });
});
