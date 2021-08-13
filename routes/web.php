<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeysController;
use App\Http\Controllers\SubscribersController;

Route::get('/', [KeysController::class, 'create']);
Route::post('/keys', [KeysController::class, 'store']);
Route::get('/keys/create', [KeysController::class, 'create']);

Route::group(['middleware' => ['AuthCheck']], function() {
    Route::resource('/subscribers', SubscribersController::class);
    Route::delete('/keys/{id}', [KeysController::class, 'destroy'])->name('keys.delete')->where(['id' => '[0-9]+']);
});