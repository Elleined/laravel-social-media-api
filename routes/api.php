<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => 'Hello world');

Route::get('/users', [UserController::class, 'index']);