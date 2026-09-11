<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\AeraPayTransaksiController;
use App\Http\Controllers\SuperAdmin\DeteksiAiController;
use App\Http\Controllers\SuperAdmin\HadiahController;
use App\Http\Controllers\SuperAdmin\InstansiController;
use App\Http\Controllers\SuperAdmin\KategoriKerusakanController;
use App\Http\Controllers\SuperAdmin\KontenEdukasiController;
use App\Http\Controllers\SuperAdmin\NotifikasiController;
use App\Http\Controllers\SuperAdmin\PenugasanController;
use App\Http\Controllers\SuperAdmin\SlaKonfigurasiController;
use App\Http\Controllers\SuperAdmin\TemplatePesanController;
use App\Http\Controllers\SuperAdmin\TimSatgasController;
use App\Http\Controllers\SuperAdmin\LaporanController as SuperAdminLaporanController;
use App\Http\Controllers\Warga\LaporanController as WargaLaporanController;
use App\Http\Controllers\Warga\PoinKontribusiController;
use App\Http\Controllers\UserEdukasiProgressController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==== Khusus Super Admin ====
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('user', UserController::class);
        Route::resource('instansi', InstansiController::class);
        Route::resource('laporan', SuperAdminLaporanController::class);
        Route::resource('kategori', KategoriKerusakanController::class);
        Route::resource('tim-satgas', TimSatgasController::class);
        Route::resource('sla-konfigurasi', SlaKonfigurasiController::class);
        Route::resource('template-pesan', TemplatePesanController::class);
        Route::resource('hadiah', HadiahController::class);
        Route::resource('konten-edukasi', KontenEdukasiController::class);
        Route::resource('aeraPay', AeraPayTransaksiController::class);
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
    Route::middleware('role:instansi,petugas,warga')->group(function () {
        Route::resource('laporan', WargaLaporanController::class);
        Route::resource('notifikasi', NotifikasiController::class);
    });

    // ==== Khusus Warga ====
    Route::middleware('role:warga')->group(function () {
        Route::get('/poin-kontribusi', [PoinKontribusiController::class, 'index'])
        ->name('warga.poinKontribusi.index');

        Route::resource('userEdukasi', UserEdukasiProgressController::class);
    });
});
require __DIR__.'/auth.php';