<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\TimSatgas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $instansiId = $this->instansiId();

        $namaInstansi = Auth::user()->nama ?? 'Instansi';

        $prioritas = $request->get('prioritas');
        $status = $request->get('status');

        $peta = Laporan::with(['kategori', 'user'])
            ->where('instansi_id', $instansiId)
            ->whereNotIn('status', ['Ditolak']);

        if (in_array($prioritas, ['Kritis', 'Sedang', 'Rendah'], true)) {
            $peta->where('tingkat_prioritas', $prioritas);
        }

        if (in_array($status, [
            'Menunggu',
            'Diverifikasi',
            'Diproses',
            'Selesai',
        ], true)) {
            $peta->where('status', $status);
        }

        $titikPeta = $peta->latest()->get();

        $queryLaporan = Laporan::where('instansi_id', $instansiId);

        $totalLaporan = (clone $queryLaporan)->count();

        $statistik = [
            'menunggu' => (clone $queryLaporan)
                ->where('status', 'Menunggu')
                ->count(),

            'diverifikasi' => (clone $queryLaporan)
                ->where('status', 'Diverifikasi')
                ->count(),

            'diproses' => (clone $queryLaporan)
                ->where('status', 'Diproses')
                ->count(),

            'selesai' => (clone $queryLaporan)
                ->where('status', 'Selesai')
                ->count(),

            'ditolak' => (clone $queryLaporan)
                ->where('status', 'Ditolak')
                ->count(),

            'kritis' => (clone $queryLaporan)
                ->whereIn('tingkat_prioritas', ['Kritis', 'Krisis'])
                ->whereIn('status', [
                    'Menunggu',
                    'Diverifikasi',
                    'Diproses',
                ])
                ->count(),
        ];

        $menungguVerifikasi = $statistik['menunggu'];

        $sedangDitangani =
            $statistik['diverifikasi'] +
            $statistik['diproses'];

        $selesaiBulanIni = (clone $queryLaporan)
            ->where('status', 'Selesai')
            ->whereBetween('updated_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->count();

        $laporanPrioritas = Laporan::with(['kategori', 'user'])
            ->where('instansi_id', $instansiId)
            ->whereNotIn('status', ['Ditolak'])
            ->orderByRaw("
                CASE
                    WHEN tingkat_prioritas IN ('Kritis', 'Krisis') THEN 1
                    WHEN tingkat_prioritas = 'Sedang' THEN 2
                    WHEN tingkat_prioritas = 'Rendah' THEN 3
                    ELSE 4
                END
            ")
            ->latest()
            ->limit(10)
            ->get();

        $laporanPerKategori = Laporan::query()
            ->where('laporan.instansi_id', $instansiId)
            ->leftJoin(
                'kategori_kerusakan',
                'laporan.kategori_id',
                '=',
                'kategori_kerusakan.id'
            )
            ->select(
                DB::raw("COALESCE(kategori_kerusakan.nama_kategori, 'Lainnya') AS nama"),
                DB::raw('COUNT(laporan.id) AS jumlah')
            )
            ->groupBy('kategori_kerusakan.nama_kategori')
            ->orderByDesc('jumlah')
            ->get();

        $rentang = (int) $request->get('rentang', 6);

        if (! in_array($rentang, [6, 12], true)) {
            $rentang = 6;
        }

        $awalBulan = now()
            ->startOfMonth()
            ->subMonths($rentang - 1);

        $dataBulanan = Laporan::where('instansi_id', $instansiId)
            ->where('created_at', '>=', $awalBulan)
            ->selectRaw('YEAR(created_at) AS tahun, MONTH(created_at) AS bulan, COUNT(*) AS jumlah')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(function ($item) {
                return sprintf(
                    '%04d-%02d',
                    $item->tahun,
                    $item->bulan
                );
            });

        $labelsBulanan = [];
        $jumlahBulanan = [];

        for ($i = 0; $i < $rentang; $i++) {
            $bulan = $awalBulan->copy()->addMonths($i);
            $key = $bulan->format('Y-m');

            $labelsBulanan[] = $bulan->translatedFormat('M Y');
            $jumlahBulanan[] = (int) (
                $dataBulanan->get($key)->jumlah ?? 0
            );
        }

        $laporanPerBulan = [
            'labels' => $labelsBulanan,
            'data' => $jumlahBulanan,
        ];

        $perluVerifikasi = Laporan::with(['kategori', 'user'])
            ->where('instansi_id', $instansiId)
            ->where('status', 'Menunggu')
            ->orderByRaw("
                CASE
                    WHEN tingkat_prioritas IN ('Kritis', 'Krisis') THEN 1
                    WHEN tingkat_prioritas = 'Sedang' THEN 2
                    WHEN tingkat_prioritas = 'Rendah' THEN 3
                    ELSE 4
                END
            ")
            ->latest()
            ->limit(10)
            ->get();

        $belumDidisposisi = Laporan::with('kategori')
            ->where('instansi_id', $instansiId)
            ->where('status', 'Diverifikasi')
            ->whereDoesntHave('penugasan', function ($q) {
                $q->whereIn('status', [
                    'ditugaskan',
                    'dalam_proses',
                    'selesai',
                ]);
            })
            ->latest()
            ->get();

        $menungguValidasi = Penugasan::with([
                'laporan',
                'petugas',
                'timSatgas',
            ])
            ->where('status', 'selesai')
            ->whereHas('laporan', function ($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId)
                    ->where('status', 'Diproses');
            })
            ->latest('tanggal_selesai')
            ->get();

        $penugasanAktif = Penugasan::with([
                'laporan',
                'petugas',
                'timSatgas',
            ])
            ->whereIn('status', ['ditugaskan', 'dalam_proses'])
            ->whereHas('laporan', function ($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            })
            ->latest('tanggal_penugasan')
            ->get();

        $jumlahOverdue = $penugasanAktif
            ->filter(fn ($item) => $item->isOverdue())
            ->count();

        $penugasanTerbaru = Penugasan::with([
                'laporan',
                'petugas',
                'timSatgas',
            ])
            ->whereHas('laporan', function ($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            })
            ->latest()
            ->limit(10)
            ->get();

        $timSatgas = TimSatgas::where('instansi_id', $instansiId)
            ->where('status', 'aktif')
            ->withCount([
                'penugasan' => function ($q) {
                    $q->whereIn('status', [
                        'ditugaskan',
                        'dalam_proses',
                    ]);
                },
            ])
            ->get();

        return view('instansi.dashboard', compact(
            'namaInstansi',
            'titikPeta',
            'statistik',
            'totalLaporan',
            'selesaiBulanIni',
            'menungguVerifikasi',
            'sedangDitangani',
            'laporanPrioritas',
            'laporanPerKategori',
            'laporanPerBulan',
            'perluVerifikasi',
            'belumDidisposisi',
            'menungguValidasi',
            'penugasanAktif',
            'penugasanTerbaru',
            'jumlahOverdue',
            'timSatgas',
            'prioritas',
            'status'
        ));
    }

    private function hitungLaporan(int $instansiId, string $status): int
    {
        return Laporan::where('instansi_id', $instansiId)
            ->where('status', $status)
            ->count();
    }

    private function instansiId(): int
    {
        $instansiId = Auth::user()->instansi_id;

        abort_unless(
            $instansiId,
            403,
            'Akun Anda belum terhubung dengan instansi mana pun.'
        );

        return $instansiId;
    }
}