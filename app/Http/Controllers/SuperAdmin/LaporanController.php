<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Services\PoinLaporanServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /**
     * Pemetaan filter dari sidebar (?status=...) ke status asli di database.
     * key   = nilai ?status= di URL
     * value = [status di DB (string atau array), judul halaman]
     */
    private const FILTER_STATUS = [
        'menunggu_verifikasi' => [['Menunggu', 'Terverifikasi AI'], 'Verifikasi Laporan'],
        'disposisi'           => ['Diverifikasi', 'Disposisi Laporan'],
        'diproses'            => ['Diproses',     'Tindak Lanjut'],
        'selesai'             => ['Selesai',      'Laporan Selesai'],
        'ditolak'             => ['Ditolak',      'Laporan Ditolak'],
    ];

    /** Status laporan yang masih boleh diverifikasi super admin. */
    private const STATUS_BISA_DIVERIFIKASI = ['Menunggu', 'Terverifikasi AI'];

    public function index(Request $request)
    {
        $filter = self::FILTER_STATUS[$request->query('status')] ?? null;

        $laporan = Laporan::with(['user', 'kategori', 'instansi', 'diverifikasiOleh'])
            ->when($filter, fn ($q) => $q->whereIn('status', (array) $filter[0]))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $judulHalaman = $filter[1] ?? 'Semua Laporan';

        return view('SuperAdmin.laporan.index', compact('laporan', 'judulHalaman'));
    }

    public function show(string $id)
    {
        $laporan = Laporan::with(['user', 'kategori', 'instansi', 'diverifikasiOleh', 'deteksiAi'])
            ->findOrFail($id);

        return view('SuperAdmin.laporan.show', compact('laporan'));
    }

    public function destroy(string $id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }

        $laporan->delete();

        return redirect()
            ->route('super_admin.laporan.index')
            ->with('success', 'Laporan berhasil dihapus');
    }

    /**
     * Verifikasi laporan: setujui (Diverifikasi) atau tolak (Ditolak).
     * Hanya untuk laporan berstatus Menunggu / Terverifikasi AI.
     * Status berikutnya (Diproses, Selesai) diatur lewat alur disposisi instansi.
     */
    public function verify(Request $request, string $id)
    {
        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Diverifikasi,Ditolak',
        ]);

        if (! in_array($laporan->status, self::STATUS_BISA_DIVERIFIKASI, true)) {
            return back()->with('error', 'Laporan ini sudah diverifikasi atau sudah diproses, statusnya tidak dapat diubah lagi.');
        }

        $laporan->update([
            'status'            => $request->status,
            'diverifikasi_oleh' => Auth::id(),
        ]);

        // Poin kontribusi untuk warga (anonim dilewati di dalam service)
        if ($request->status === 'Diverifikasi') {
            PoinLaporanServices::verifikasi($laporan->fresh());
        }

        return back()->with(
            'success',
            $request->status === 'Diverifikasi'
                ? 'Laporan berhasil diverifikasi.'
                : 'Laporan telah ditolak.'
        );
    }
}