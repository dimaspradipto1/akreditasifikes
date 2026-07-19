<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VmtsController;
use App\Http\Controllers\MatriksController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\DokumenBersamaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login');
    Route::post('/login', 'loginProses')->name('login.proses');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'registerProses')->name('register.proses');
    Route::get('/switch-role/{role}', 'switchRole')->name('switch.role')->middleware('auth');
    Route::get('/switch-back', 'switchBack')->name('switch.back')->middleware('auth');
});

Route::middleware(['auth', 'checkrole'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/matriks', [MatriksController::class, 'index'])->name('matriks.index');
    Route::get('/tracker-bukti', [TrackerController::class, 'index'])->name('tracker.index');
    
    Route::resource('dokumen-bersama', DokumenBersamaController::class);

    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan/export-excel', [ReportController::class, 'exportExcel'])->name('laporan.export.excel');

    Route::get('/pengaturan', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/pengaturan', [SettingController::class, 'update'])->name('settings.update');

    Route::get('user/{user}/update-password', [UserController::class, 'updatePasswordForm'])->name('user.updatePasswordForm');
    Route::put('user/{user}/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
    
    Route::resource('user', UserController::class);

    Route::post('/dashboard/jadwal', [DashboardController::class, 'updateJadwal'])->name('dashboard.jadwal.update');

    Route::resource('kriteria1', VmtsController::class);
    Route::resource('kurikulum', KurikulumController::class);
    Route::resource('penilaian', App\Http\Controllers\PenilaianController::class);

    Route::resource('mahasiswa', App\Http\Controllers\MahasiswaController::class);
    Route::resource('doenpkm', App\Http\Controllers\DoenpkmController::class);
    Route::resource('sarpraskeuangan', App\Http\Controllers\SarpraskeuanganController::class);
    Route::resource('mutu', App\Http\Controllers\MutuController::class);
    Route::resource('tatakelola', App\Http\Controllers\TatakelolaController::class);
});
