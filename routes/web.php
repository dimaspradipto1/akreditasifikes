<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->name('logout');
});
