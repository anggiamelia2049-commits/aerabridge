<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriKerusakanController;
use App\Http\Controllers\KontenEdukasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\SlaKonfigurasiController;
use App\Http\Controllers\TemplatePesanController;
use App\Http\Controllers\TimSatgasController;

Route::get('/', function () {
    return view('welcome');
});

// ==================== ROUTE KATEGORI KERUSAKAN ====================
Route::get('/KategoriKerusakan', [KategoriKerusakanController::class, 'index'])->name('KategoriKerusakan.index');
Route::get('/KategoriKerusakan/create', [KategoriKerusakanController::class, 'create'])->name('KategoriKerusakan.create');
Route::post('/KategoriKerusakan', [KategoriKerusakanController::class, 'store'])->name('KategoriKerusakan.store');
Route::get('/KategoriKerusakan/{id}', [KategoriKerusakanController::class, 'show'])->name('KategoriKerusakan.show');
Route::get('/KategoriKerusakan/{id}/edit', [KategoriKerusakanController::class, 'edit'])->name('KategoriKerusakan.edit');
Route::put('/KategoriKerusakan/{id}', [KategoriKerusakanController::class, 'update'])->name('KategoriKerusakan.update');
Route::delete('/KategoriKerusakan/{id}', [KategoriKerusakanController::class, 'destroy'])->name('KategoriKerusakan.destroy');

// ==================== ROUTE KONTEN EDUKASI ====================
Route::get('/KontenEdukasi', [KontenEdukasiController::class, 'index'])->name('KontenEdukasi.index');
Route::get('/KontenEdukasi/create', [KontenEdukasiController::class, 'create'])->name('KontenEdukasi.create');
Route::post('/KontenEdukasi', [KontenEdukasiController::class, 'store'])->name('KontenEdukasi.store');
Route::get('/KontenEdukasi/{id}', [KontenEdukasiController::class, 'show'])->name('KontenEdukasi.show');
Route::get('/KontenEdukasi/{id}/edit', [KontenEdukasiController::class, 'edit'])->name('KontenEdukasi.edit');
Route::put('/KontenEdukasi/{id}', [KontenEdukasiController::class, 'update'])->name('KontenEdukasi.update');
Route::delete('/KontenEdukasi/{id}', [KontenEdukasiController::class, 'destroy'])->name('KontenEdukasi.destroy');

// ==================== ROUTE LAPORAN ====================
Route::get('/Laporan', [LaporanController::class, 'index'])->name('Laporan.index');
Route::get('/Laporan/create', [LaporanController::class, 'create'])->name('Laporan.create');
Route::post('/Laporan', [LaporanController::class, 'store'])->name('Laporan.store');
Route::get('/Laporan/{id}', [LaporanController::class, 'show'])->name('Laporan.show');
Route::get('/Laporan/{id}/edit', [LaporanController::class, 'edit'])->name('Laporan.edit');
Route::put('/Laporan/{id}', [LaporanController::class, 'update'])->name('Laporan.update');
Route::delete('/Laporan/{id}', [LaporanController::class, 'destroy'])->name('Laporan.destroy');

// ==================== ROUTE NOTIFIKASI ====================
Route::get('/Notifikasi', [NotifikasiController::class, 'index'])->name('Notifikasi.index');
Route::get('/Notifikasi/create', [NotifikasiController::class, 'create'])->name('Notifikasi.create');
Route::post('/Notifikasi', [NotifikasiController::class, 'store'])->name('Notifikasi.store');
Route::get('/Notifikasi/{id}', [NotifikasiController::class, 'show'])->name('Notifikasi.show');
Route::get('/Notifikasi/{id}/edit', [NotifikasiController::class, 'edit'])->name('Notifikasi.edit');
Route::put('/Notifikasi/{id}', [NotifikasiController::class, 'update'])->name('Notifikasi.update');
Route::delete('/Notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('Notifikasi.destroy');

// route penugasan
Route::get('/penugasan', [PenugasanController::class, 'index'])->name('penugasan.index');
Route::get('/penugasan/create', [PenugasanController::class, 'create'])->name('penugasan.create');
Route::post('/penugasan', [PenugasanController::class, 'store'])->name('penugasan.store');
Route::get('/penugasan/{penugasan}', [PenugasanController::class, 'show'])->name('penugasan.show');
Route::get('/penugasan/{penugasan}/edit', [PenugasanController::class, 'edit'])->name('penugasan.edit');
Route::put('/penugasan/{penugasan}', [PenugasanController::class, 'update'])->name('penugasan.update');
Route::delete('/penugasan/{penugasan}', [PenugasanController::class, 'destroy'])->name('penugasan.destroy');

// route slaKonfigurasi
Route::get('/sla-konfigurasi', [SlaKonfigurasiController::class, 'index'])->name('sla-konfigurasi.index');
Route::get('/sla-konfigurasi/create', [SlaKonfigurasiController::class, 'create'])->name('sla-konfigurasi.create');
Route::post('/sla-konfigurasi', [SlaKonfigurasiController::class, 'store'])->name('sla-konfigurasi.store');
Route::get('/sla-konfigurasi/{slaKonfigurasi}', [SlaKonfigurasiController::class, 'show'])->name('sla-konfigurasi.show');
Route::get('/sla-konfigurasi/{slaKonfigurasi}/edit', [SlaKonfigurasiController::class, 'edit'])->name('sla-konfigurasi.edit');
Route::put('/sla-konfigurasi/{slaKonfigurasi}', [SlaKonfigurasiController::class, 'update'])->name('sla-konfigurasi.update');
Route::delete('/sla-konfigurasi/{slaKonfigurasi}', [SlaKonfigurasiController::class, 'destroy'])->name('sla-konfigurasi.destroy');

// route templatePesan
Route::get('/template-pesan', [TemplatePesanController::class, 'index'])->name('template-pesan.index');
Route::get('/template-pesan/create', [TemplatePesanController::class, 'create'])->name('template-pesan.create');
Route::post('/template-pesan', [TemplatePesanController::class, 'store'])->name('template-pesan.store');
Route::get('/template-pesan/{templatePesan}', [TemplatePesanController::class, 'show'])->name('template-pesan.show');
Route::get('/template-pesan/{templatePesan}/edit', [TemplatePesanController::class, 'edit'])->name('template-pesan.edit');
Route::put('/template-pesan/{templatePesan}', [TemplatePesanController::class, 'update'])->name('template-pesan.update');
Route::delete('/template-pesan/{templatePesan}', [TemplatePesanController::class, 'destroy'])->name('template-pesan.destroy');

// route timSatgas
Route::get('/tim-satgas', [TimSatgasController::class, 'index'])->name('tim-satgas.index');
Route::get('/tim-satgas/create', [TimSatgasController::class, 'create'])->name('tim-satgas.create');
Route::post('/tim-satgas', [TimSatgasController::class, 'store'])->name('tim-satgas.store');
Route::get('/tim-satgas/{timSatgas}', [TimSatgasController::class, 'show'])->name('tim-satgas.show');
Route::get('/tim-satgas/{timSatgas}/edit', [TimSatgasController::class, 'edit'])->name('tim-satgas.edit');
Route::put('/tim-satgas/{timSatgas}', [TimSatgasController::class, 'update'])->name('tim-satgas.update');
Route::delete('/tim-satgas/{timSatgas}', [TimSatgasController::class, 'destroy'])->name('tim-satgas.destroy');