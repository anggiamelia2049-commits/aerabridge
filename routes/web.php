<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\AeraPayTransaksiController as SuperAdminAeraPayTransaksiController;
use App\Http\Controllers\SuperAdmin\DeteksiAiController as SuperAdminDeteksiAiController;
use App\Http\Controllers\SuperAdmin\HadiahController as SuperAdminHadiahController;
use App\Http\Controllers\SuperAdmin\InstansiController as SuperAdminInstansiController;
use App\Http\Controllers\SuperAdmin\KategoriKerusakanController as SuperAdminKategoriKerusakanController;
use App\Http\Controllers\SuperAdmin\KontenEdukasiController as SuperAdminKontenEdukasiController;
use App\Http\Controllers\SuperAdmin\NotifikasiController as SuperAdminNotifikasiController;
use App\Http\Controllers\SuperAdmin\PenugasanController as SuperAdminPenugasanController;
use App\Http\Controllers\SuperAdmin\SlaKonfigurasiController as SuperAdminSlaKonfigurasiController;
use App\Http\Controllers\SuperAdmin\TemplatePesanController as SuperAdminTemplatePesanController;
use App\Http\Controllers\SuperAdmin\TimSatgasController as SuperAdminTimSatgasController;
use App\Http\Controllers\SuperAdmin\LaporanController as SuperAdminLaporanController;

use App\Http\Controllers\Warga\LaporanController as WargaLaporanController;
use App\Http\Controllers\Warga\PoinKontribusiController as WargaPoinKontribusiController;
use App\Http\Controllers\Warga\UserEdukasiProgressController as WargaUserEdukasiProgressController;
use App\Http\Controllers\Warga\NotifikasiController as WargaNotifikasiController;
use App\Http\Controllers\Warga\AeraPayController as WargaAeraPayController;

use App\Http\Controllers\Petugas\LaporanController as PetugasLaporanController;
use App\Http\Controllers\Petugas\PenugasanController as PetugasPenugasanController;
use App\Http\Controllers\Petugas\NotifikasiController as PetugasNotifikasiController;

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
        Route::resource('user', SuperAdminUserController::class);
        Route::resource('instansi', SuperAdminInstansiController::class);
        Route::resource('laporan', SuperAdminLaporanController::class);
        Route::resource('kategori', SuperAdminKategoriKerusakanController::class);
        Route::resource('tim-satgas', SuperAdminTimSatgasController::class);
        Route::resource('sla-konfigurasi', SuperAdminSlaKonfigurasiController::class);
        Route::resource('template-pesan', SuperAdminTemplatePesanController::class);
        Route::resource('hadiah', SuperAdminHadiahController::class);
        Route::resource('konten-edukasi', SuperAdminKontenEdukasiController::class);
        Route::resource('deteksi-ai', SuperAdminDeteksiAiController::class);
        Route::resource('notifikasi', SuperAdminNotifikasiController::class);
        Route::resource('penugasan', SuperAdminPenugasanController::class);
        Route::resource('aeraPay', SuperAdminAeraPayTransaksiController::class);
    });

    // ==== Khusus Warga ====
    Route::name('warga.')->prefix('warga')->middleware('role:warga')->group(function () {
        Route::resource('laporan', WargaLaporanController::class);
        Route::resource('notifikasi', WargaNotifikasiController::class);
        Route::resource('poin', WargaPoinKontribusiController::class)->only(['index']);
        Route::resource('aeraPay', WargaAeraPayController::class)->only(['index', 'show', 'create', 'store']);
        Route::resource('user-edukasi', WargaUserEdukasiProgressController::class);
    });

    // ==== Khusus Petugas ====
    Route::name('petugas.')->prefix('petugas')->middleware('role:petugas')->group(function () {
        Route::resource('laporan', PetugasLaporanController::class);
        Route::resource('penugasan', PetugasPenugasanController::class);
        Route::resource('notifikasi', PetugasNotifikasiController::class);
    });
});

require __DIR__.'/auth.php';