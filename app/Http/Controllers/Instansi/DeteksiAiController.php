<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\DeteksiAi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeteksiAiController extends Controller
{
    /**
     * Hasil deteksi AI atas laporan yang masuk ke instansi ini.
     * Dipakai sebagai bahan pertimbangan saat verifikasi laporan.
     *
     * Data deteksi dihasilkan oleh sistem, instansi hanya membaca.
     */
    public function index(Request $request)
    {
        $instansiId = $this->instansiId();

        $query = DeteksiAi::with(['laporan.kategori', 'laporan.user'])
            ->whereHas('laporan', function ($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            });

        if ($request->filled('hasil_validasi')) {
            $query->where('hasil_validasi', $request->hasil_validasi);
        }

        if ($request->filled('estimasi_prioritas')) {
            $query->where('estimasi_prioritas', $request->estimasi_prioritas);
        }

        if ($request->filled('tingkat_kerusakan')) {
            $query->where('tingkat_kerusakan', $request->tingkat_kerusakan);
        }

        $deteksiAIs = $query->latest()->get();

        return view('instansi.deteksiAi.index', compact('deteksiAIs'));
    }

    public function create()
    {
        abort(403, 'Data deteksi AI dihasilkan oleh sistem, tidak dapat dibuat manual.');
    }

    public function store(Request $request)
    {
        abort(403, 'Data deteksi AI dihasilkan oleh sistem, tidak dapat dibuat manual.');
    }

    public function show(string $id)
    {
        $deteksiAi = DeteksiAi::with(['laporan.kategori', 'laporan.user'])
            ->findOrFail($id);

        abort_unless(
            $deteksiAi->laporan
                && $deteksiAi->laporan->instansi_id === $this->instansiId(),
            403,
            'Anda tidak memiliki akses ke data deteksi ini.'
        );

        return view('instansi.deteksiAi.show', compact('deteksiAi'));
    }

    public function edit(string $id)
    {
        abort(403, 'Instansi tidak memiliki akses untuk mengubah hasil deteksi AI.');
    }

    public function update(Request $request, string $id)
    {
        abort(403, 'Instansi tidak memiliki akses untuk mengubah hasil deteksi AI.');
    }

    public function destroy(string $id)
    {
        abort(403, 'Instansi tidak memiliki akses untuk menghapus hasil deteksi AI.');
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
