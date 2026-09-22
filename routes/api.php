<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostReactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('', fn () => 'pong');

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::prefix('forgot-password')
    ->controller(ForgotPasswordController::class)
    ->group(function () {
        Route::post('', 'forgotPassword');
        Route::get('verify-reset-token', 'verifyResetToken');
        Route::put('reset-password', 'resetPassword');
    });

Route::middleware('auth:sanctum')
    ->group(function () {
        // Logout
        Route::post('logout', [LogoutController::class, 'logout']);

        // Admin
        Route::prefix('admin/users')
            ->controller(AdminController::class)
            ->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::get('{user}', 'show')->withTrashed();
                Route::put('{user}', 'update')->withTrashed();
                Route::delete('{user}', 'destroy')->withTrashed();
            });

        // Users
        Route::prefix('me/profile')
            ->controller(ProfileController::class)
            ->group(function () {
                Route::get('', 'show');
                Route::put('', 'update');
                Route::delete('', 'destroy');
                Route::patch('password', 'password');
            });

        // References
        Route::prefix('references')
            ->controller(ReferenceController::class)
            ->group(function () {
                Route::get('emojis', 'emojis');
                Route::get('provider-types', 'providerTypes');
            });

        // Posts
        Route::prefix('posts')
            ->controller(PostController::class)
            ->group(function () {
                Route::get('', 'index');
                Route::post('', 'store');
                Route::put('{post}', 'update')->withTrashed();
                Route::delete('{post}', 'destroy')->withTrashed();

                Route::prefix('{post}/reactions')
                    ->controller(PostReactionController::class)
                    ->group(function () {
                        Route::get('', 'index')->withTrashed();
                        Route::post('', 'toggle')->withTrashed();
                    });
            });
    });
