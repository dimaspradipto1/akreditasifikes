<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===== AUTH ROUTES (Guest Only) =====
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProses'])->name('login.proses');
});

// ===== LOGOUT =====
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ===== PROTECTED ROUTES (Auth Only) =====
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pengguna
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AuthController::class, 'manajemenPengguna'])->name('index');
        Route::post('/store', [AuthController::class, 'registerProses'])->name('store');
        Route::put('/{user}', [AuthController::class, 'updatePengguna'])->name('update');
        Route::delete('/{user}', [AuthController::class, 'destroyPengguna'])->name('destroy');
    });
});
