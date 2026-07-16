<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VmtsController;
use App\Http\Controllers\MatriksController;
use App\Http\Controllers\TrackerController;
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

    // Mahasiswa Resource and Custom Routes
    Route::resource('mahasiswa', App\Http\Controllers\MahasiswaController::class);
    Route::put('/mahasiswa/narasi/{narasi}', [App\Http\Controllers\MahasiswaController::class, 'updateNarasi'])->name('mahasiswa.narasi.update');
    Route::post('/mahasiswa/bukti', [App\Http\Controllers\MahasiswaController::class, 'storeBukti'])->name('mahasiswa.bukti.store');
    Route::put('/mahasiswa/bukti/{bukti}', [App\Http\Controllers\MahasiswaController::class, 'updateBukti'])->name('mahasiswa.bukti.update');
    Route::delete('/mahasiswa/bukti/{bukti}', [App\Http\Controllers\MahasiswaController::class, 'destroyBukti'])->name('mahasiswa.bukti.destroy');

    // Doenpkm Resource and Custom Routes
    Route::resource('doenpkm', App\Http\Controllers\DoenpkmController::class);
    Route::put('/doenpkm/narasi/{narasi}', [App\Http\Controllers\DoenpkmController::class, 'updateNarasi'])->name('doenpkm.narasi.update');
    Route::post('/doenpkm/bukti', [App\Http\Controllers\DoenpkmController::class, 'storeBukti'])->name('doenpkm.bukti.store');
    Route::put('/doenpkm/bukti/{bukti}', [App\Http\Controllers\DoenpkmController::class, 'updateBukti'])->name('doenpkm.bukti.update');
    Route::delete('/doenpkm/bukti/{bukti}', [App\Http\Controllers\DoenpkmController::class, 'destroyBukti'])->name('doenpkm.bukti.destroy');

    // Sarpraskeuangan Resource and Custom Routes
    Route::resource('sarpraskeuangan', App\Http\Controllers\SarpraskeuanganController::class);
    Route::put('/sarpraskeuangan/narasi/{narasi}', [App\Http\Controllers\SarpraskeuanganController::class, 'updateNarasi'])->name('sarpraskeuangan.narasi.update');
    Route::post('/sarpraskeuangan/bukti', [App\Http\Controllers\SarpraskeuanganController::class, 'storeBukti'])->name('sarpraskeuangan.bukti.store');
    Route::put('/sarpraskeuangan/bukti/{bukti}', [App\Http\Controllers\SarpraskeuanganController::class, 'updateBukti'])->name('sarpraskeuangan.bukti.update');
    Route::delete('/sarpraskeuangan/bukti/{bukti}', [App\Http\Controllers\SarpraskeuanganController::class, 'destroyBukti'])->name('sarpraskeuangan.bukti.destroy');

    // Mutu Resource and Custom Routes
    Route::resource('mutu', App\Http\Controllers\MutuController::class);
    Route::put('/mutu/narasi/{narasi}', [App\Http\Controllers\MutuController::class, 'updateNarasi'])->name('mutu.narasi.update');
    Route::post('/mutu/bukti', [App\Http\Controllers\MutuController::class, 'storeBukti'])->name('mutu.bukti.store');
    Route::put('/mutu/bukti/{bukti}', [App\Http\Controllers\MutuController::class, 'updateBukti'])->name('mutu.bukti.update');
    Route::delete('/mutu/bukti/{bukti}', [App\Http\Controllers\MutuController::class, 'destroyBukti'])->name('mutu.bukti.destroy');

    // Tatakelola Resource and Custom Routes
    Route::resource('tatakelola', App\Http\Controllers\TatakelolaController::class);
    Route::put('/tatakelola/narasi/{narasi}', [App\Http\Controllers\TatakelolaController::class, 'updateNarasi'])->name('tatakelola.narasi.update');
    Route::post('/tatakelola/bukti', [App\Http\Controllers\TatakelolaController::class, 'storeBukti'])->name('tatakelola.bukti.store');
    Route::put('/tatakelola/bukti/{bukti}', [App\Http\Controllers\TatakelolaController::class, 'updateBukti'])->name('tatakelola.bukti.update');
    Route::delete('/tatakelola/bukti/{bukti}', [App\Http\Controllers\TatakelolaController::class, 'destroyBukti'])->name('tatakelola.bukti.destroy');
});
