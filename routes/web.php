<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
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
use App\Http\Controllers\Warga\DashboardController as WargaDashboardController;

use App\Http\Controllers\Petugas\LaporanController as PetugasLaporanController;
use App\Http\Controllers\Petugas\PenugasanController as PetugasPenugasanController;
use App\Http\Controllers\Petugas\NotifikasiController as PetugasNotifikasiController;

use App\Http\Controllers\Instansi\LaporanController as InstansiLaporanController;
use App\Http\Controllers\Instansi\PenugasanController as InstansiPenugasanController;
use App\Http\Controllers\Instansi\NotifikasiController as InstansiNotifikasiController;
use App\Http\Controllers\Instansi\DeteksiAiController as InstansiDeteksiAiController;
use App\Http\Controllers\Instansi\KategoriKerusakanController as InstansiKategoriKerusakanController;
use App\Http\Controllers\Instansi\TimSatgasController as InstansiTimSatgasController;
use App\Http\Controllers\Instansi\DashboardController as InstansiDashboardController;
use App\Http\Controllers\Instansi\StatistikController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'super_admin' => redirect()->route('super_admin.dashboard'),
        'warga' => redirect()->route('warga.dashboard'),
        'petugas' => redirect()->route('petugas.penugasan.index'),
        'instansi' => redirect()->route('instansi.dashboard'),
        default => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==== Khusus Super Admin ====
    Route::name('super_admin.')->prefix('super_admin')->middleware('role:super_admin')->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('user', SuperAdminUserController::class);
        Route::resource('instansi', SuperAdminInstansiController::class);

        Route::resource('laporan', SuperAdminLaporanController::class)->except(['create', 'store']);
        Route::put('laporan/{id}/verify', [SuperAdminLaporanController::class, 'verify'])
            ->name('laporan.verify');

        Route::resource('kategori', SuperAdminKategoriKerusakanController::class);
        Route::resource('tim-satgas', SuperAdminTimSatgasController::class);
        Route::resource('sla-konfigurasi', SuperAdminSlaKonfigurasiController::class);
        Route::resource('template-pesan', SuperAdminTemplatePesanController::class);
        Route::resource('hadiah', SuperAdminHadiahController::class);
        Route::resource('konten-edukasi', SuperAdminKontenEdukasiController::class);
        Route::resource('notifikasi', SuperAdminNotifikasiController::class);

        // Monitoring-only: hanya index & show, controller tidak punya
        // method create/store/edit/update/destroy.
        Route::resource('deteksi-ai', SuperAdminDeteksiAiController::class)
            ->only(['index', 'show']);

        Route::resource('penugasan', SuperAdminPenugasanController::class)
            ->only(['index', 'show']);

        Route::resource('aeraPay', SuperAdminAeraPayTransaksiController::class)
            ->only(['index', 'show']);
    });

    // ==== Khusus Warga ====
    Route::name('warga.')->prefix('warga')->middleware('role:warga')->group(function () {
        Route::get('/dashboard', [WargaDashboardController::class, 'index'])->name('dashboard');

        Route::resource('laporan', WargaLaporanController::class);
        Route::resource('notifikasi', WargaNotifikasiController::class);
        Route::resource('poin', WargaPoinKontribusiController::class)->only(['index']);
        Route::resource('aeraPay', WargaAeraPayController::class)->only(['index', 'show', 'create', 'store']);
        Route::resource('user-edukasi', WargaUserEdukasiProgressController::class);
    });

    // ==== Khusus Petugas ====
    Route::name('petugas.')->prefix('petugas')->middleware('role:petugas')->group(function () {
        // Laporan: read-only, controller meng-abort(403) pada create/store/edit/update/destroy.
        Route::resource('laporan', PetugasLaporanController::class)
            ->only(['index', 'show']);

        // Penugasan: bukan resource penuh — method custom (mulai tugas,
        // closing report), bukan create/store/destroy standar.
        Route::get('penugasan', [PetugasPenugasanController::class, 'index'])
            ->name('penugasan.index');
        Route::get('penugasan/{id}', [PetugasPenugasanController::class, 'show'])
            ->name('penugasan.show');
        Route::get('penugasan/{id}/edit', [PetugasPenugasanController::class, 'edit'])
            ->name('penugasan.edit');
        Route::put('penugasan/{id}', [PetugasPenugasanController::class, 'update'])
            ->name('penugasan.update');

        Route::resource('notifikasi', PetugasNotifikasiController::class);
    });

    // ==== Khusus Instansi ====
    Route::name('instansi.')->prefix('instansi')->middleware('role:instansi')->group(function () {
        Route::get('/dashboard', [InstansiDashboardController::class, 'index'])->name('dashboard');

        Route::resource('laporan', InstansiLaporanController::class)->only(['index', 'show']);

        Route::resource('penugasan', InstansiPenugasanController::class);
        Route::resource('notifikasi', InstansiNotifikasiController::class);

        // Deteksi AI: read-only, controller meng-abort(403) pada aksi tulis.
        Route::resource('deteksi-ai', InstansiDeteksiAiController::class)->only(['index', 'show']);
        Route::get('/verifikasi', [InstansiLaporanController::class, 'verifikasi'])->name('verifikasi.index');
        Route::post('/laporan/{laporan}/verify', [InstansiLaporanController::class, 'verify'])->name('laporan.verify');

        Route::resource('tim-satgas', InstansiTimSatgasController::class)->only(['index', 'show'])->parameters(['tim-satgas' => 'tim_satgas']);
        Route::resource('kategori-kerusakan', InstansiKategoriKerusakanController::class)->only('index');
        Route::get('statistik', [StatistikController::class, 'index'])->name('statistik.index');
    });
});

require __DIR__ . '/auth.php';
