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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route resource untuk semua fitur AERA Bridge
    // Sementara semua bisa diakses siapa saja yang login,
    // nanti middleware role ditambahkan per masing-masing setelah RBAC selesai
    Route::resource('user', UserController::class);
    Route::resource('instansi', InstansiController::class);
    Route::resource('kategori-kerusakan', KategoriKerusakanController::class);
    Route::resource('laporan', LaporanController::class);
    Route::resource('deteksi-ai', DeteksiAiController::class);
    Route::resource('tim-satgas', TimSatgasController::class);
    Route::resource('penugasan', PenugasanController::class);
    Route::resource('sla-konfigurasi', SlaKonfigurasiController::class);
    Route::resource('template-pesan', TemplatePesanController::class);
    Route::resource('konten-edukasi', KontenEdukasiController::class);
    Route::resource('user-edukasi-progress', UserEdukasiProgressController::class);
    Route::resource('aera-pay-transaksi', AeraPayTransaksiController::class);
    Route::resource('notifikasi', NotifikasiController::class);
    Route::resource('hadiah', HadiahController::class);
});

require __DIR__.'/auth.php';
