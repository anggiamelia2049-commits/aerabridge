<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Services\PoinLaporanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    private const STATUS = ['Menunggu', 'Terverifikasi AI', 'Diverifikasi', 'Diproses', 'Selesai', 'Ditolak'];

    public function index(Request $request)
    {
        $instansiId = $this->instansiId();
        $status = $request->get('status');
        $prioritas = $request->get('prioritas');

        $query = Laporan::with(['kategori', 'user'])->where('instansi_id', $instansiId);

        if (in_array($status, self::STATUS, true)) {
            $query->where('status', $status);
        }
        if (in_array($prioritas, ['Kritis', 'Sedang', 'Rendah'], true)) {
            $query->where('tingkat_prioritas', $prioritas);
        }

        $laporan = $query->latest()->get();

        return view('instansi.laporan.index', compact('laporan', 'status', 'prioritas'));
    }

    public function verifikasi()
    {
        $status = 'Menunggu';
        $prioritas = null;

        $laporan = Laporan::with(['kategori', 'user'])
            ->where('instansi_id', $this->instansiId())
            ->whereIn('status', ['Menunggu', 'Terverifikasi AI'])
            ->orderByRaw("FIELD(tingkat_prioritas, 'Kritis', 'Sedang', 'Rendah')")
            ->latest()
            ->get();

        return view('instansi.laporan.index', compact('laporan', 'status', 'prioritas'));
    }

    public function show(string $id)
    {
        $laporan = Laporan::with(['kategori', 'user', 'diverifikasiOleh'])
            ->where('instansi_id', $this->instansiId())
            ->findOrFail($id);

        return view('instansi.laporan.show', compact('laporan'));
    }

    public function verify(Request $request, string $id)
    {
        $laporan = Laporan::where('instansi_id', $this->instansiId())->findOrFail($id);

        if (! in_array($laporan->status, ['Menunggu', 'Terverifikasi AI'], true)) {
            return back()->with('error', 'Laporan ini sudah diverifikasi atau sedang diproses.');
        }

        $request->validate([
            'status' => 'required|in:Diverifikasi,Ditolak',
            'tingkat_prioritas' => 'nullable|in:Kritis,Sedang,Rendah',
        ]);

        $laporan->update([
            'status' => $request->status,
            'tingkat_prioritas' => $request->tingkat_prioritas ?? $laporan->tingkat_prioritas,
            'diverifikasi_oleh' => Auth::id(),
        ]);

        if ($request->status === 'Diverifikasi') {
            PoinLaporanService::verifikasi($laporan);
        }

        $pesan = $request->status === 'Diverifikasi'
            ? 'Laporan berhasil diverifikasi, siap didisposisikan ke tim satgas.'
            : 'Laporan ditolak.';

        return redirect()->route('instansi.laporan.index')->with('success', $pesan);
    }

    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit(string $id) { abort(404); }
    public function update(Request $request, string $id) { abort(404); }
    public function destroy(string $id) { abort(404); }

    private function instansiId(): int
    {
        $instansiId = Auth::user()->instansi_id;
        abort_unless($instansiId, 403, 'Akun Anda belum terhubung dengan instansi mana pun.');
        return $instansiId;
    }
}