<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===== AUTH ROUTES =====
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('/login', 'loginProses')->name('login.proses');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'registerProses')->name('register.proses');
});

// ===== PROTECTED ROUTES =====
Route::middleware(['auth', 'checkrole'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User CRUD Resource
    Route::resource('user', UserController::class);

    // Update Password (extra route di luar resource)
    Route::get('/user/{user}/update-password', [UserController::class, 'updatePasswordForm'])
        ->name('user.updatePasswordForm');
    Route::put('/user/{user}/update-password', [UserController::class, 'updatePassword'])
        ->name('user.updatePassword');
});
