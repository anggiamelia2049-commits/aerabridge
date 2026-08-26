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
Route::prefix('KategoriKerusakan')->name('KategoriKerusakan.')->group(function () {
    Route::get('/', [KategoriKerusakanController::class, 'index'])->name('index');
    Route::get('/create', [KategoriKerusakanController::class, 'create'])->name('create');
    Route::post('/', [KategoriKerusakanController::class, 'store'])->name('store');
    Route::get('/{id}', [KategoriKerusakanController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [KategoriKerusakanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KategoriKerusakanController::class, 'update'])->name('update');
    Route::delete('/{id}', [KategoriKerusakanController::class, 'destroy'])->name('destroy');
});

// ==================== ROUTE KONTEN EDUKASI ====================
Route::prefix('KontenEdukasi')->name('KontenEdukasi.')->group(function () {
    Route::get('/', [KontenEdukasiController::class, 'index'])->name('index');
    Route::get('/create', [KontenEdukasiController::class, 'create'])->name('create');
    Route::post('/', [KontenEdukasiController::class, 'store'])->name('store');
    Route::get('/{id}', [KontenEdukasiController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [KontenEdukasiController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KontenEdukasiController::class, 'update'])->name('update');
    Route::delete('/{id}', [KontenEdukasiController::class, 'destroy'])->name('destroy');

    // Additional routes
    Route::get('/status/{status}', [KontenEdukasiController::class, 'getByStatus'])->name('status');
    Route::get('/kategori/{kategori}', [KontenEdukasiController::class, 'getByKategori'])->name('kategori');
    Route::get('/penulis/{penulisId}', [KontenEdukasiController::class, 'getByPenulis'])->name('penulis');
    Route::put('/{id}/publish', [KontenEdukasiController::class, 'publish'])->name('publish');
    Route::put('/{id}/nonaktif', [KontenEdukasiController::class, 'nonaktif'])->name('nonaktif');
});

// ==================== ROUTE LAPORAN ====================
Route::prefix('Laporan')->name('Laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
    Route::get('/create', [LaporanController::class, 'create'])->name('create');
    Route::post('/', [LaporanController::class, 'store'])->name('store');
    Route::get('/{id}', [LaporanController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [LaporanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [LaporanController::class, 'update'])->name('update');
    Route::delete('/{id}', [LaporanController::class, 'destroy'])->name('destroy');

    // Additional routes
    Route::put('/{id}/verify', [LaporanController::class, 'verify'])->name('verify');
    Route::get('/status/{status}', [LaporanController::class, 'getByStatus'])->name('status');
    Route::get('/user/{userId}', [LaporanController::class, 'getByUser'])->name('user');
    Route::get('/prioritas/{tingkat}', [LaporanController::class, 'getByPrioritas'])->name('prioritas');
    Route::get('/instansi/{instansiId}', [LaporanController::class, 'getByInstansi'])->name('instansi');
    Route::get('/kategori/{kategoriId}', [LaporanController::class, 'getByKategori'])->name('kategori');
    Route::put('/{id}/proses', [LaporanController::class, 'proses'])->name('proses');
    Route::put('/{id}/selesai', [LaporanController::class, 'selesai'])->name('selesai');
    Route::put('/{id}/tolak', [LaporanController::class, 'tolak'])->name('tolak');
});

// ==================== ROUTE NOTIFIKASI ====================
Route::prefix('Notifikasi')->name('Notifikasi.')->group(function () {
    Route::get('/', [NotifikasiController::class, 'index'])->name('index');
    Route::get('/create', [NotifikasiController::class, 'create'])->name('create');
    Route::post('/', [NotifikasiController::class, 'store'])->name('store');
    Route::get('/{id}', [NotifikasiController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [NotifikasiController::class, 'edit'])->name('edit');
    Route::put('/{id}', [NotifikasiController::class, 'update'])->name('update');
    Route::delete('/{id}', [NotifikasiController::class, 'destroy'])->name('destroy');

    // Additional routes
    Route::get('/unread', [NotifikasiController::class, 'getUnread'])->name('unread');
    Route::put('/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('read');
    Route::put('/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('read-all');
    Route::get('/type/{tipe}', [NotifikasiController::class, 'getByType'])->name('type');
    Route::get('/count', [NotifikasiController::class, 'getCount'])->name('count');
    Route::get('/latest', [NotifikasiController::class, 'getLatest'])->name('latest');
});

