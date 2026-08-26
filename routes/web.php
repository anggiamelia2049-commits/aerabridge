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
use App\Http\Controllers\UserEdukasiProgressController;
use App\Http\Controllers\HadiahController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\DeteksiAIController;
use App\Http\Controllers\AeraPayTransaksiController;


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

Route::get('/user-edukasi-progress', [UserEdukasiProgressController::class, 'index'])->name('user-edukasi-progress.index');
Route::get('/user-edukasi-progress/create', [UserEdukasiProgressController::class, 'create'])->name('user-edukasi-progress.create');
Route::post('/user-edukasi-progress', [UserEdukasiProgressController::class, 'store'])->name('user-edukasi-progress.store');
Route::get('/user-edukasi-progress/{userEdukasiProgress}', [UserEdukasiProgressController::class, 'show'])->name('user-edukasi-progress.show');
Route::get('/user-edukasi-progress/{userEdukasiProgress}/edit', [UserEdukasiProgressController::class, 'edit'])->name('user-edukasi-progress.edit');
Route::put('/user-edukasi-progress/{userEdukasiProgress}', [UserEdukasiProgressController::class, 'update'])->name('user-edukasi-progress.update');
Route::delete('/user-edukasi-progress/{userEdukasiProgress}', [UserEdukasiProgressController::class, 'destroy'])->name('user-edukasi-progress.destroy');

// Aera Pay Transaksi
Route::get('/aera_pay_transaksi', [AeraPayTransaksiController::class, 'index'])->name('aera_pay_transaksi.index');
Route::get('/aera_pay_transaksi/create', [AeraPayTransaksiController::class, 'create'])->name('aera_pay_transaksi.create');
Route::post('/aera_pay_transaksi', [AeraPayTransaksiController::class, 'store'])->name('aera_pay_transaksi.store');
Route::get('/aera_pay_transaksi/{id}/edit', [AeraPayTransaksiController::class, 'edit'])->name('aera_pay_transaksi.edit');
Route::put('/aera_pay_transaksi/{id}', [AeraPayTransaksiController::class, 'update'])->name('aera_pay_transaksi.update');
Route::delete('/aera_pay_transaksi/{id}', [AeraPayTransaksiController::class, 'destroy'])->name('aera_pay_transaksi.destroy');

// Hadiah
Route::get('/hadiah', [HadiahController::class, 'index'])->name('hadiah.index');
Route::get('/hadiah/create', [HadiahController::class, 'create'])->name('hadiah.create');
Route::post('/hadiah', [HadiahController::class, 'store'])->name('hadiah.store');
Route::get('/hadiah/{id}/edit', [HadiahController::class, 'edit'])->name('hadiah.edit');
Route::put('/hadiah/{id}', [HadiahController::class, 'update'])->name('hadiah.update');
Route::delete('/hadiah/{id}', [HadiahController::class, 'destroy'])->name('hadiah.destroy');

// Instansi
Route::get('/instansi', [InstansiController::class, 'index'])->name('instansi.index');
Route::get('/instansi/create', [InstansiController::class, 'create'])->name('instansi.create');
Route::post('/instansi', [InstansiController::class, 'store'])->name('instansi.store');
Route::get('/instansi/{id}/edit', [InstansiController::class, 'edit'])->name('instansi.edit');
Route::put('/instansi/{id}', [InstansiController::class, 'update'])->name('instansi.update');
Route::delete('/instansi/{id}', [InstansiController::class, 'index'])->name('instansi.destroy');

// Deteksi Ai
Route::get('/deteksi_ai', [DeteksiAIController::class, 'index'])->name('deteksi_ai.index');
Route::get('/deteksi_ai/create', [DeteksiAIController::class, 'create'])->name('deteksi_ai.create');
Route::post('/deteksi_ai', [DeteksiAIController::class, 'store'])->name('deteksi_ai.store');
Route::get('/deteksi_ai/{id}/edit', [DeteksiAIController::class, 'edit'])->name('deteksi_ai.edit');
Route::put('/deteksi_ai/{id}', [DeteksiAIController::class, 'update'])->name('deteksi_ai.update');
Route::delete('/deteksi_ai/{id}', [DeteksiAIController::class, 'destroy'])->name('deteksi_ai.destroy');
