<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeysController;
use App\Http\Controllers\SubscribersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [KeysController::class, 'index']);

Route::resource('/keys', KeysController::class);

Route::resource('/subscribers', SubscribersController::class);