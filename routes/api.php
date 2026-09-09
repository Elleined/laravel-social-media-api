<?php

use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => 'pong');

Route::prefix('/users')->group(function() {
    Route::get('', [UserController::class, 'index']);
});

Route::prefix('references')->group(function () {
    Route::get('/emojis', [ReferenceController::class, 'emojis']);
    Route::get('/provider-types', [ReferenceController::class, 'providerTypes']);
});