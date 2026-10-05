<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\AeraPayTransaksi;
use App\Models\PoinKontribusiLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Halaman Profil & Poin Kontribusi (AERA Pay) — sesuai proposal 7.4.D:
     * "Menampilkan riwayat kontribusi pengguna, total poin, dan fitur
     * konversi poin ke saldo simulasi AERA Pay sebagai bentuk apresiasi
     * gamifikasi" + 7.2.C.3: "Statistik dan lencana (badge) sebagai
     * apresiasi partisipasi aktif."
     *
     * Controller ini TERPISAH dari ProfileController bawaan Breeze
     * (yang menangani ganti nama/email/password) — ini murni untuk
     * ringkasan gamifikasi, bukan pengaturan akun.
     */
    public function index()
    {
        $user = Auth::user();

        $totalPoin = PoinKontribusiLog::totalPoin($user->id);

        $saldoSaatIni = AeraPayTransaksi::where('user_id', $user->id)
            ->latest()
            ->value('saldo_sesudah') ?? 0;

        $riwayatPoinTerbaru = PoinKontribusiLog::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $badge = $this->tentukanBadge($totalPoin);

        return view('warga.dashboard', compact(
            'user',
            'totalPoin',
            'saldoSaatIni',
            'riwayatPoinTerbaru',
            'badge'
        ));
    }

    /**
     * Badge sederhana berdasarkan akumulasi total poin kontribusi.
     * Ambang batas ini bisa disesuaikan sesuai kebutuhan proyek.
     */
    private function tentukanBadge(int $totalPoin): array
    {
        return match (true) {
            $totalPoin >= 500 => ['label' => 'Pahlawan Kota', 'warna' => 'merah'],
            $totalPoin >= 100 => ['label' => 'Warga Aktif', 'warna' => 'oranye'],
            default => ['label' => 'Warga Pemula', 'warna' => 'cyan-4'],
        };
    }
}