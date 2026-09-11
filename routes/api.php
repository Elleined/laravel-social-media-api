<?php

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('', fn () => 'pong');

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')
    ->group(function () {
        // Logout
        Route::post('logout', [LoginController::class, 'logout']);

        // Users
        Route::prefix('users')
            ->controller(UserController::class)
            ->group(function () {
                Route::post('change-password', 'changePassword');
                Route::get('', 'index');
                Route::get('{user}', 'show');
                Route::put('{user}', 'update');
                Route::delete('{user}', 'destroy');
            });

        // References
        Route::prefix('references')
            ->controller(ReferenceController::class)
            ->group(function () {
                Route::get('emojis', 'emojis');
                Route::get('provider-types', 'providerTypes');
            });
    });
