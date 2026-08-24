<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriKerusakanController;
use App\Http\Controllers\KontenEdukasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;

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