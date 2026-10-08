<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\KategoriKerusakan;
use App\Models\Laporan;
use App\Models\Penugasan;
use Illuminate\Support\Facades\Auth;

class StatistikController extends Controller
{
    public function index()
    {
        $instansiId = Auth::user()->instansi_id;

        $laporan = Laporan::where('instansi_id', $instansiId);

        // penugasan tidak punya instansi_id, jadi difilter lewat laporannya
        $penugasan = Penugasan::whereHas('laporan', fn ($q) => $q->where('instansi_id', $instansiId));

        // jumlah laporan per status dan per prioritas: ['Menunggu' => 3, ...]
        $perStatus = (clone $laporan)->selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        $perPrioritas = (clone $laporan)->selectRaw('tingkat_prioritas, count(*) as total')
            ->groupBy('tingkat_prioritas')->pluck('total', 'tingkat_prioritas');

        $perKategori = (clone $laporan)->selectRaw('kategori_id, count(*) as total')
            ->groupBy('kategori_id')->pluck('total', 'kategori_id');

        // Warna mengikuti palet proposal
        $statusWarna = [
            'Menunggu'     => '#9E9E9E',
            'Diverifikasi' => '#45818E',
            'Diproses'     => '#FF9800',
            'Selesai'      => '#4CAF50',
            'Ditolak'      => '#4A4A4A',
        ];
        $prioritasWarna = [
            'Krisis' => '#E53935',
            'Sedang' => '#FF9800',
            'Rendah' => '#FFC107',
        ];

        $grafikStatus = collect($statusWarna)->map(fn ($warna, $label) => [
            'label'  => $label,
            'jumlah' => $perStatus[$label] ?? 0,
            'warna'  => $warna,
        ])->values();

        $grafikPrioritas = collect($prioritasWarna)->map(fn ($warna, $label) => [
            'label'  => $label,
            'jumlah' => $perPrioritas[$label] ?? 0,
            'warna'  => $warna,
        ])->values();

        $grafikKategori = KategoriKerusakan::orderBy('nama_kategori')->get()->map(fn ($k) => [
            'label'  => $k->nama_kategori,
            'jumlah' => $perKategori[$k->id] ?? 0,
            'warna'  => $k->warna_marker ?: '#45818E',
        ]);

        return view('instansi.statistik.index', [
            'totalLaporan'       => (clone $laporan)->count(),
            'menungguVerifikasi' => $perStatus['Menunggu'] ?? 0,
            'laporanDiproses'    => $perStatus['Diproses'] ?? 0,
            'laporanSelesai'     => $perStatus['Selesai'] ?? 0,
            'penugasanAktif'     => (clone $penugasan)->whereIn('status', ['ditugaskan', 'dalam_proses'])->count(),
            'grafikStatus'       => $grafikStatus,
            'grafikPrioritas'    => $grafikPrioritas,
            'grafikKategori'     => $grafikKategori,
        ]);
    }
}
