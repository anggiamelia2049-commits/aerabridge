<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = now()->startOfMonth();

        // ===== Kartu statistik =====
        $stats = [
            'total'          => Laporan::count(),
            'bulan_ini'      => Laporan::where('created_at', '>=', $bulanIni)->count(),
            'belum_direspon' => Laporan::where('status', 'Menunggu')->count(),
            'direspon'       => Laporan::where('status', '!=', 'Menunggu')->count(),
            'dalam_progress' => Laporan::where('status', 'Diproses')->count(),
            'selesai'        => Laporan::where('status', 'Selesai')->count(),
        ];

        // ===== Grafik: jumlah aduan bulan ini per tingkat prioritas =====
        $perPrioritas = Laporan::where('created_at', '>=', $bulanIni)
            ->select('tingkat_prioritas', DB::raw('COUNT(*) as total'))
            ->groupBy('tingkat_prioritas')
            ->pluck('total', 'tingkat_prioritas');

        $chart = [
            'labels' => ['Krisis', 'Sedang', 'Rendah'],
            'data'   => [
                (int) ($perPrioritas['Krisis'] ?? 0),
                (int) ($perPrioritas['Sedang'] ?? 0),
                (int) ($perPrioritas['Rendah'] ?? 0),
            ],
        ];

        // ===== Daftar laporan terbaru (berdasarkan prioritas) =====
        // Di MySQL, ORDER BY kolom ENUM mengikuti urutan definisi: Krisis, Sedang, Rendah
        $laporanTerbaru = Laporan::whereNotIn('status', ['Selesai', 'Ditolak'])
            ->orderBy('tingkat_prioritas')
            ->latest()
            ->limit(10)
            ->get();

        return view('SuperAdmin.dashboard', compact('stats', 'chart', 'laporanTerbaru'));
    }
}