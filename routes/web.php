<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeysController;
use App\Http\Controllers\SubscribersController;

Route::get('/', [KeysController::class, 'index']);

Route::resource('/keys', KeysController::class);
Route::resource('/subscribers', SubscribersController::class);