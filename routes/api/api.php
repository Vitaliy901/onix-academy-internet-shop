<?php

use App\Http\Controllers\Api\CategoryController;
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

Route::middleware('auth:sanctum')->group(function () {

    // Users
    Route::controller(UserController::class)->group(function () {
        Route::get('/users/me', 'show');
        Route::match(['put', 'patch'], '/users/me', 'update')->name('me.update');
        Route::delete('/users/me', 'destroy')->withTrashed();
    });

    Route::apiResource('users', UserController::class)->except('store');

    // Products
    Route::controller(ProductController::class)->group(function () {
        Route::get('products', 'index');
        Route::get('products/{product}', 'show');
    });

    Route::apiResource('products', ProductController::class)->except('index', 'show');

    Route::delete('products/{product}/images/{image}', ProductImageController::class);

    // Categories
    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories', 'index');
        Route::get('categories/{category}', 'show');
    });

    Route::apiResource('categories', CategoryController::class)->except('index', 'show');
});
