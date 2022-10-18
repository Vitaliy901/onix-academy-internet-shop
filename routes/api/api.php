<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CartDeleteController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderDeleteController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VoteController;
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

Route::get('reviews/{review}', [ReviewController::class, 'show'])->middleware('guest');

Route::middleware('auth:sanctum')->group(function () {

    // Users
    Route::controller(UserController::class)->group(function () {
        Route::get('/users/me', 'show');
        Route::match(['put', 'patch'], '/users/me', 'update')->name('me.update');
        Route::delete('/users/me', 'destroy')->withTrashed();
    });

    Route::apiResource('users', UserController::class)->except('store');

    // Products
    Route::apiResource('products', ProductController::class);

    // Images
    Route::apiResource('images', ImageController::class)->except('update');

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Cart
    Route::delete('cart', CartDeleteController::class);

    Route::apiResource('cart', CartController::class);

    // reviews for all
    Route::controller(ReviewController::class)->group(function () {
        Route::get('reviews', 'index');
        Route::get('reviews/{review}', 'show');
    });

    // answers for all
    Route::controller(AnswerController::class)->group(function () {
        Route::get('answers', 'index');
        Route::get('answers/{answer}', 'show');
    });

    // questions for all
    Route::controller(QuestionController::class)->group(function () {
        Route::get('questions', 'index');
        Route::get('questions/{question}', 'show');
    });

    Route::middleware('verified')->group(function () {

        // Orders
        Route::apiResource('orders', OrderController::class);

        Route::delete('orders', OrderDeleteController::class);

        // Items of Orders
        Route::apiResource('orders.items', OrderItemController::class);

        // reviews
        Route::apiResource('reviews', ReviewController::class)->except('index', 'show');

        // questions
        Route::apiResource('questions', QuestionController::class)->except('index', 'show');

        // votes 
        Route::apiResource('votes', VoteController::class)->except('show');

        // answers
        Route::apiResource('answers', AnswerController::class)->except('index', 'show');
    });
});
