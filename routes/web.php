<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\MonitoringKaryawanController;
use App\Http\Controllers\ArsipMasukController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\ManagementAkunController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ManajemenMediaController;

// ==========================================
// 1. AUTHENTICATION & LANDING
// ==========================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/visi-misi', [LandingController::class, 'visiMisi'])->name('visi-misi');
Route::get('/sejarah', [LandingController::class, 'sejarah'])->name('sejarah');
Route::get('/struktur-organisasi', [LandingController::class, 'struktur'])->name('struktur');
Route::get('/penghargaan', [LandingController::class, 'penghargaan'])->name('penghargaan');

// ==========================================
// 2. PROTECTED ROUTES (Requires Login)
// ==========================================
Route::middleware(['auth'])->group(function () {

    Route::get('/beranda', [DashboardController::class, 'index'])->name('beranda');

    // ==========================================
    // FITUR ARSIP
    // ==========================================
    // PERBAIKAN: Pindahkan Route Import & Lainnya ke atas sebelum Route {id}
    Route::get('/arsip/musnah', [ArsipController::class, 'musnah'])->name('arsip.musnah');
    Route::post('/arsip/export', [ArsipController::class, 'export']);
    Route::get('/arsip/import', [ArsipController::class, 'showImportForm'])->name('arsip.import');
    Route::post('/arsip/import', [ArsipController::class, 'import'])->name('arsip.import.process');

    Route::get('/arsip/import/progress', [ArsipController::class, 'checkProgress'])->name('arsip.import.progress');

    Route::get('/api/klasifikasi-options', [ArsipController::class, 'getKlasifikasiOptions']);

    // Core Resource Arsip
    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::get('/input-arsip', [ArsipController::class, 'create']);
    Route::post('/input-arsip', [ArsipController::class, 'store'])->name('arsip.store');
    Route::get('/arsip/{id}/edit', [ArsipController::class, 'edit'])->name('arsip.edit');
    Route::put('/arsip/{id}', [ArsipController::class, 'update'])->name('arsip.update');
    Route::delete('/arsip/{id}', [ArsipController::class, 'destroy'])->name('arsip.destroy');

    // ==========================================
    // FITUR PEMINJAMAN
    // ==========================================
    Route::get('/peminjaman/export', [PeminjamanController::class, 'export']);
    Route::patch('/peminjaman/{id}/complete', [PeminjamanController::class, 'complete']);
    Route::post('/peminjaman/bulk-delete', [PeminjamanController::class, 'bulkDelete']);
    Route::resource('peminjaman', PeminjamanController::class);

    // ==========================================
    // FITUR ARSIP MASUK
    // ==========================================
    Route::get('/arsip-masuk', [ArsipMasukController::class, 'index'])->name('arsip-masuk.index');
    Route::post('/arsip-masuk/export', [ArsipMasukController::class, 'export'])->name('arsip-masuk.export');
    Route::get('/arsip-masuk/create', [ArsipMasukController::class, 'create'])->name('arsip-masuk.create');
    Route::get('/arsip-masuk/{id}/edit', [ArsipMasukController::class, 'edit'])->name('arsip-masuk.edit');
    Route::put('/arsip-masuk/{id}', [ArsipMasukController::class, 'update'])->name('arsip-masuk.update');
    Route::delete('/arsip-masuk/{id}', [ArsipMasukController::class, 'destroy'])->name('arsip-masuk.destroy');
    Route::post('/arsip-masuk', [ArsipMasukController::class, 'store'])->name('arsip-masuk.store');
    Route::get('/arsip-masuk/get-klasifikasi-options', [ArsipMasukController::class, 'getKlasifikasiOptions'])->name('arsip-masuk.get-klasifikasi-options');

    // ==========================================
    // FITUR MONITORING KARYAWAN
    // ==========================================
    Route::get('/monitoring', [MonitoringKaryawanController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/create', [MonitoringKaryawanController::class, 'create'])->name('monitoring.create');
    Route::post('/monitoring', [MonitoringKaryawanController::class, 'store'])->name('monitoring.store');
    Route::get('/monitoring/{id}/edit', [MonitoringKaryawanController::class, 'edit'])->name('monitoring.edit');
    Route::put('/monitoring/{id}', [MonitoringKaryawanController::class, 'update'])->name('monitoring.update');
    Route::delete('/monitoring/{id}', [MonitoringKaryawanController::class, 'destroy'])->name('monitoring.destroy');
    Route::patch('/monitoring/{id}/advance-stage', [MonitoringKaryawanController::class, 'advanceStage'])->name('monitoring.advance-stage');
    Route::get('/monitoring/{id}/history', [MonitoringKaryawanController::class, 'history'])->name('monitoring.history');
    Route::post('/monitoring/{id}/progress', [MonitoringKaryawanController::class, 'addProgress'])->name('monitoring.add-progress');

    // ==========================================
    // FITUR MANAGEMENT AKUN & MEDIA (Admin)
    // ==========================================
    Route::resource('management-akun', ManagementAkunController::class);
    Route::resource('manajemen-media', ManajemenMediaController::class);
    Route::resource('manajemen-unit', \App\Http\Controllers\ManajemenUnitController::class);

    // ==========================================
    // PROFILE
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
