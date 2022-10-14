<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CartDeleteController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderDeleteController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Products for all
Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'index');
    Route::get('products/{product}', 'show');
});

// Categories for all
Route::controller(CategoryController::class)->group(function () {
    Route::get('categories', 'index');
    Route::get('categories/{category}', 'show');
});

Route::middleware('auth:sanctum')->group(function () {

    // Users
    Route::controller(UserController::class)->group(function () {
        Route::get('/users/me', 'show');
        Route::match(['put', 'patch'], '/users/me', 'update')->name('me.update');
        Route::delete('/users/me', 'destroy')->withTrashed();
    });

    Route::apiResource('users', UserController::class)->except('store');

    // Products
    Route::apiResource('products', ProductController::class)->except('index', 'show');

    Route::delete('products/{product}/images/{image}', ProductImageController::class)
        ->can('delete', 'image');

    /*     Route::apiResource('products.questions', ProductQuestionController::class)->except('show'); */

    // Categories
    Route::apiResource('categories', CategoryController::class)->except('index', 'show');

    // Cart
    Route::delete('cart', CartDeleteController::class);

    Route::apiResource('cart', CartController::class);

    // Orders
    Route::middleware('verified')->group(function () {

        Route::apiResource('orders', OrderController::class);

        Route::delete('orders', OrderDeleteController::class);
        // Items of Orders
        Route::apiResource('orders.items', OrderItemController::class);
    });
});
