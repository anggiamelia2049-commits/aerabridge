<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Menampilkan semua laporan yang masuk ke instansi ini.
     */
    public function index(Request $request)
    {
        $instansiId = $this->instansiId();

        $status = $request->get('status');
        $prioritas = $request->get('prioritas');

        $query = Laporan::with(['kategori', 'user'])
            ->where('instansi_id', $instansiId);

        if (in_array($status, ['Menunggu', 'Diverifikasi', 'Diproses', 'Selesai', 'Ditolak'], true)) {
            $query->where('status', $status);
        }

        if (in_array($prioritas, ['Krisis', 'Sedang', 'Rendah'], true)) {
            $query->where('tingkat_prioritas', $prioritas);
        }

        $laporan = $query->latest()->get();

        return view('instansi.laporan.index', compact('laporan', 'status', 'prioritas'));
    }

    /**
     * Menampilkan detail laporan untuk ditinjau instansi.
     */
    public function show(string $id)
    {
        $laporan = Laporan::with(['kategori', 'user', 'diverifikasiOleh'])
            ->where('instansi_id', $this->instansiId())
            ->findOrFail($id);

        return view('instansi.laporan.show', compact('laporan'));
    }

    /**
     * Verifikasi laporan: tandai valid (lanjut diproses) atau ditolak.
     * Sesuai flowchart 6.C: "Apakah Laporan Dinyatakan Valid?"
     */
    public function verify(Request $request, string $id)
    {
        $laporan = Laporan::where('instansi_id', $this->instansiId())
            ->findOrFail($id);

        $request->validate([
            'status' => 'required|in:Diverifikasi,Ditolak',
            'tingkat_prioritas' => 'nullable|in:Krisis,Sedang,Rendah',
        ]);

        $laporan->update([
            'status' => $request->status,
            'tingkat_prioritas' => $request->tingkat_prioritas ?? $laporan->tingkat_prioritas,
            'diverifikasi_oleh' => Auth::id(),
        ]);

        $pesan = $request->status === 'Diverifikasi'
            ? 'Laporan berhasil diverifikasi, siap didisposisikan ke tim satgas.'
            : 'Laporan ditolak.';

        return redirect()
            ->route('instansi.laporan.index')
            ->with('success', $pesan);
    }

    // create(), store(), edit(), update(), destroy() TIDAK RELEVAN.
    // Instansi tidak membuat/mengedit/menghapus laporan — laporan datang dari warga,
    // instansi hanya meninjau dan mengubah status verifikasi lewat method verify().

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        abort(404);
    }

    public function update(Request $request, string $id)
    {
        abort(404);
    }

    public function destroy(string $id)
    {
        abort(404);
    }

    /**
     * Instansi milik user yang sedang login.
     */
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