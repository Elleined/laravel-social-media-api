<?php

use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('', fn () => 'pong');

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('', 'index');
    Route::get('/{user}', 'show');
    Route::post('', 'store');
    Route::put('/{user}', 'update');
    Route::delete('/{user}', 'destroy');
});

Route::prefix('references')->controller(ReferenceController::class)->group(function () {
    Route::get('/emojis', 'emojis');
    Route::get('/provider-types', 'providerTypes');
});
