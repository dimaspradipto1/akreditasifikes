<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VmtsController;
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
    Route::resource('vmts', VmtsController::class);

    // Update Password (extra route di luar resource)
    Route::get('/user/{user}/update-password', [UserController::class, 'updatePasswordForm'])
        ->name('user.updatePasswordForm');
    Route::put('/user/{user}/update-password', [UserController::class, 'updatePassword'])
        ->name('user.updatePassword');

    // VMTS Custom Routes (diperlukan untuk update narasi dan CRUD bukti)
    Route::put('/vmts/narasi/{narasi}', [VmtsController::class, 'updateNarasi'])->name('vmts.narasi.update');
    Route::post('/vmts/bukti', [VmtsController::class, 'storeBukti'])->name('vmts.bukti.store');
    Route::put('/vmts/bukti/{bukti}', [VmtsController::class, 'updateBukti'])->name('vmts.bukti.update');
    Route::delete('/vmts/bukti/{bukti}', [VmtsController::class, 'destroyBukti'])->name('vmts.bukti.destroy');

    // Kurikulum Resource and Custom Routes
    Route::resource('kurikulum', KurikulumController::class);
    Route::put('/kurikulum/narasi/{narasi}', [KurikulumController::class, 'updateNarasi'])->name('kurikulum.narasi.update');
    Route::post('/kurikulum/bukti', [KurikulumController::class, 'storeBukti'])->name('kurikulum.bukti.store');
    Route::put('/kurikulum/bukti/{bukti}', [KurikulumController::class, 'updateBukti'])->name('kurikulum.bukti.update');
    Route::delete('/kurikulum/bukti/{bukti}', [KurikulumController::class, 'destroyBukti'])->name('kurikulum.bukti.destroy');

    // Penilaian Resource and Custom Routes
    Route::resource('penilaian', App\Http\Controllers\PenilaianController::class);
    Route::put('/penilaian/narasi/{narasi}', [App\Http\Controllers\PenilaianController::class, 'updateNarasi'])->name('penilaian.narasi.update');
    Route::post('/penilaian/bukti', [App\Http\Controllers\PenilaianController::class, 'storeBukti'])->name('penilaian.bukti.store');
    Route::put('/penilaian/bukti/{bukti}', [App\Http\Controllers\PenilaianController::class, 'updateBukti'])->name('penilaian.bukti.update');
    Route::delete('/penilaian/bukti/{bukti}', [App\Http\Controllers\PenilaianController::class, 'destroyBukti'])->name('penilaian.bukti.destroy');
});
