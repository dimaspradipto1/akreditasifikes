<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VmtsController;
use App\Http\Controllers\MatriksController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\DokumenBersamaController;
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
    Route::get('/matriks', [MatriksController::class, 'index'])->name('matriks.index');
    Route::get('/tracker-bukti', [TrackerController::class, 'index'])->name('tracker.index');
    
    // Dokumen Bersama
    Route::resource('dokumen-bersama', DokumenBersamaController::class);

    // User Password Routes
    Route::get('user/{user}/update-password', [UserController::class, 'updatePasswordForm'])->name('user.updatePasswordForm');
    Route::put('user/{user}/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
    
    // User CRUD Resource
    Route::resource('user', UserController::class);
    // VMTS Resource
    Route::resource('vmts', VmtsController::class);

    // Kurikulum Resource
    Route::resource('kurikulum', KurikulumController::class);

    // Penilaian Resource
    Route::resource('penilaian', App\Http\Controllers\PenilaianController::class);

    // Mahasiswa Resource
    Route::resource('mahasiswa', App\Http\Controllers\MahasiswaController::class);

    // Doenpkm Resource
    Route::resource('doenpkm', App\Http\Controllers\DoenpkmController::class);

    // Sarpraskeuangan Resource
    Route::resource('sarpraskeuangan', App\Http\Controllers\SarpraskeuanganController::class);

    // Mutu Resource
    Route::resource('mutu', App\Http\Controllers\MutuController::class);

    // Tatakelola Resource
    Route::resource('tatakelola', App\Http\Controllers\TatakelolaController::class);
});
