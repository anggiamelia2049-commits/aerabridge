<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\AeraPayTransaksi;
use App\Models\PoinKontribusiLog;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // =========================
        // KONTRIBUSI & AERA PAY
        // =========================

        $totalPoin = PoinKontribusiLog::totalPoin($user->id);

        $saldoSaatIni = AeraPayTransaksi::where('user_id', $user->id)
            ->latest()
            ->value('saldo_sesudah') ?? 0;

        $riwayatPoinTerbaru = PoinKontribusiLog::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $badge = $this->tentukanBadge($totalPoin);


        // =========================
        // DATA LAPORAN WARGA
        // =========================

        $laporanWarga = Laporan::where('user_id', $user->id);

        $totalLaporan = (clone $laporanWarga)->count();

        $laporanDiproses = (clone $laporanWarga)
            ->whereIn('status', ['diproses', 'proses'])
            ->count();

        $laporanSelesai = (clone $laporanWarga)
            ->whereIn('status', ['selesai', 'ditangani'])
            ->count();

        $laporanTerbaru = Laporan::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();


        return view('warga.dashboard', compact(
            'user',
            'totalPoin',
            'saldoSaatIni',
            'riwayatPoinTerbaru',
            'badge',
            'totalLaporan',
            'laporanDiproses',
            'laporanSelesai',
            'laporanTerbaru'
        ));
    }


    private function tentukanBadge(int $totalPoin): array
    {
        return match (true) {
            $totalPoin >= 500 => [
                'label' => 'Pahlawan Kota',
                'warna' => 'merah'
            ],

            $totalPoin >= 100 => [
                'label' => 'Warga Aktif',
                'warna' => 'oranye'
            ],

            default => [
                'label' => 'Warga Pemula',
                'warna' => 'cyan-4'
            ],
        };
    }
}