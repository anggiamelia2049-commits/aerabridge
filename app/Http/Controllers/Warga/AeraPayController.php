<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\AeraPayTransaksi;
use App\Models\PoinKontribusiLog;
use Illuminate\Http\Request;

class AeraPayController extends Controller
{
    /**
     * Display a listing of the resource.
     * Tampilkan riwayat transaksi & saldo milik warga yang login.
     */
    public function index()
    {
        $user = auth()->user();

        $transaksis = AeraPayTransaksi::where('user_id', $user->id)
            ->latest()
            ->get();

        $totalPoin = $this->getTotalPoin($user->id);
        $saldoSaatIni = $this->getSaldoTerakhir($user->id);

        return view('warga.aera-pay.index', compact('transaksis', 'totalPoin', 'saldoSaatIni'));
    }

    /**
     * Show the form for creating a new resource.
     * Form konversi poin ke saldo simulasi.
     */
    public function create()
    {
        $totalPoin = $this->getTotalPoin(auth()->id());
        return view('warga.aera-pay.create', compact('totalPoin'));
    }

    /**
     * Store a newly created resource in storage.
     * Proses konversi poin -> saldo (sesuai proposal 6.B.5: 1.000 poin = Rp500).
     */
    public function store(Request $request)
    {
        $request->validate([
            'jumlah_poin' => 'required|integer|min:1000',
        ]);

        $user = auth()->user();
        $jumlahPoin = $request->jumlah_poin;
        $totalPoin = $this->getTotalPoin($user->id);

        if ($totalPoin < $jumlahPoin) {
            return back()->with('error', 'Poin tidak cukup untuk konversi.');
        }

        $nominal = intdiv($jumlahPoin, 1000) * 500;
        $saldoSebelum = $this->getSaldoTerakhir($user->id);
        $saldoSesudah = $saldoSebelum + $nominal;

        AeraPayTransaksi::create([
            'user_id' => $user->id,
            'laporan_id' => null,
            'jenis_transaksi' => 'redeem',
            'nominal' => $nominal,
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $saldoSesudah,
            'status' => 'berhasil',
        ]);

        PoinKontribusiLog::create([
            'user_id' => $user->id,
            'laporan_id' => null,
            'jenis_aktivitas' => 'redeem_aera_pay',
            'poin' => -$jumlahPoin,
            'keterangan' => "Konversi {$jumlahPoin} poin ke saldo AERA Pay Rp{$nominal}",
        ]);

        return redirect()->route('warga.aeraPay.index')
            ->with('success', 'Poin berhasil dikonversi ke saldo AERA Pay.');
    }

    /**
     * Display the specified resource.
     * Detail satu transaksi milik warga (bukan milik user lain).
     */
    public function show(string $id)
    {
        $transaksi = AeraPayTransaksi::where('user_id', auth()->id())
            ->findOrFail($id);

        return view('warga.aera-pay.show', compact('transaksi'));
    }

    /**
     * Helper: hitung total poin user dari seluruh riwayat log.
     */
    private function getTotalPoin(int $userId): int
    {
        return PoinKontribusiLog::where('user_id', $userId)->sum('poin');
    }

    /**
     * Helper: ambil saldo AERA Pay terakhir user (dari transaksi paling baru).
     */
    private function getSaldoTerakhir(int $userId): int
    {
        $transaksiTerakhir = AeraPayTransaksi::where('user_id', $userId)
            ->latest()
            ->first();

        return $transaksiTerakhir ? $transaksiTerakhir->saldo_sesudah : 0;
    }

    // Method edit(), update(), destroy() SENGAJA TIDAK ADA.
    // Warga tidak boleh mengubah/menghapus riwayat transaksi yang sudah tercatat.
}