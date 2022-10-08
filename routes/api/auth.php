<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\EmailVerifyController;
use Illuminate\Support\Facades\Route;


Route::middleware('throttle:5,1')->group(function () {
	Route::post('/register', [AuthController::class, 'create']);
	Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
	Route::delete('/logout', [AuthController::class, 'logout']);

	// Verification email

	Route::get('/email/verify', [EmailVerifyController::class, 'notice'])
		->name('verification.notice');

	Route::get('/email/verify/{id}/{hash}', [EmailVerifyController::class, 'verifyEmail'])
		->middleware('signed')->name('verification.verify');

	Route::post('/email/verification-notification', [EmailVerifyController::class, 'send'])
		->middleware('throttle:3,1')->name('verification.send');

	// Verification new email
	Route::get('/pendingEmail/verify/{token}', [EmailVerifyController::class, 'verifyNewEmail'])
		->middleware('signed')
		->name('myPendingEmail.verifyNewEmail');
});

// Password reset
Route::middleware('guest')->group(function () {
	Route::post('/forgot-password', [EmailVerifyController::class, 'passwordResetEmail'])
		->name('password.email');

	Route::post('/reset-password', [EmailVerifyController::class, 'passwordUpdate'])
		->name('password.update');
});
