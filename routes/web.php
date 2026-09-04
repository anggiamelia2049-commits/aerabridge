<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AeraPayTransaksiController;
use App\Http\Controllers\DeteksiAiController;
use App\Http\Controllers\HadiahController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\KategoriKerusakanController;
use App\Http\Controllers\KontenEdukasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\SlaKonfigurasiController;
use App\Http\Controllers\TemplatePesanController;
use App\Http\Controllers\TimSatgasController;
use App\Http\Controllers\UserEdukasiProgressController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==== Khusus Super Admin ====
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('user', UserController::class);
        Route::resource('instansi', InstansiController::class);
        Route::resource('KategoriKerusakan', KategoriKerusakanController::class);
        Route::resource('tim-satgas', TimSatgasController::class);
        Route::resource('sla-konfigurasi', SlaKonfigurasiController::class);
        Route::resource('template-pesan', TemplatePesanController::class);
        Route::resource('hadiah', HadiahController::class);
        Route::resource('konten-edukasi', KontenEdukasiController::class);
    });

    // ==== Super Admin & Instansi ====
    Route::middleware('role:super_admin,instansi')->group(function () {
        Route::resource('deteksi-ai', DeteksiAiController::class);
    });

    // ==== Super Admin, Instansi & Petugas ====
    Route::middleware('role:super_admin,instansi,petugas')->group(function () {
        Route::resource('penugasan', PenugasanController::class);
    });

    // ==== Semua role bisa akses (laporan & notifikasi) ====
    Route::middleware('role:super_admin,instansi,petugas,warga')->group(function () {
        Route::resource('laporan', LaporanController::class);
        Route::resource('notifikasi', NotifikasiController::class);
    });

    // ==== Khusus Warga ====
    Route::middleware('role:warga')->group(function () {
        Route::resource('user-edukasi-progress', UserEdukasiProgressController::class);
        Route::resource('aera-pay-transaksi', AeraPayTransaksiController::class);
    });
});