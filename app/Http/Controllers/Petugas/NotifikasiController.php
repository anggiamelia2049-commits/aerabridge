<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotifikasiController extends Controller
{
    /**
     * Kotak notifikasi petugas yang login.
     * Filter: semua, atau belum_dibaca.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'semua');

        if ($filter !== 'belum_dibaca') {
            $filter = 'semua';
        }

        $query = Notifikasi::where('user_id', Auth::id());

        if ($filter === 'belum_dibaca') {
            $query->where('dibaca', false);
        }

        $notifikasis = $query->latest()->get();

        $jumlahBelumDibaca = Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->count();

        return view('petugas.notifikasi.index', compact(
            'notifikasis',
            'jumlahBelumDibaca',
            'filter'
        ));
    }

    /**
     * Data ringkas untuk lonceng di navbar (dipanggil tiap 15 detik lewat JavaScript).
     * Mengembalikan JSON: jumlah belum dibaca + 5 notifikasi terbaru.
     */
    public function ringkas()
    {
        $jumlah = Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->count();

        $daftar = Notifikasi::where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'judul' => $n->judul,
                    'isi' => Str::limit($n->isi, 80),
                    'waktu' => $n->created_at->locale('id')->diffForHumans(),
                    'dibaca' => $n->dibaca,
                    'url' => route('petugas.notifikasi.show', $n->id),
                ];
            });

        return response()->json([
            'jumlah' => $jumlah,
            'daftar' => $daftar,
        ]);
    }

    /**
     * Buka satu notifikasi. Otomatis ditandai sudah dibaca.
     */
    public function show(string $id)
    {
        $notifikasi = Notifikasi::with('laporan')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if (! $notifikasi->dibaca) {
            $notifikasi->update(['dibaca' => true]);
        }

        // Cari tugas terkait supaya bisa langsung dibuka dari notifikasi
        $penugasan = null;

        if ($notifikasi->laporan_id) {
            $penugasan = Penugasan::where('laporan_id', $notifikasi->laporan_id)
                ->where('petugas_id', Auth::id())
                ->latest('tanggal_penugasan')
                ->first();
        }

        return view('petugas.notifikasi.show', compact('notifikasi', 'penugasan'));
    }

    /**
     * Tandai dibaca. id = "semua" menandai semua notifikasi sekaligus.
     */
    public function update(Request $request, string $id)
    {
        if ($id === 'semua') {
            Notifikasi::where('user_id', Auth::id())
                ->where('dibaca', false)
                ->update(['dibaca' => true]);

            return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
        }

        $notifikasi = Notifikasi::where('user_id', Auth::id())->findOrFail($id);

        $notifikasi->update([
            'dibaca' => $request->boolean('dibaca', true),
        ]);

        return back()->with('success', 'Notifikasi diperbarui.');
    }

    public function destroy(string $id)
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())->findOrFail($id);

        $notifikasi->delete();

        return redirect()
            ->route('petugas.notifikasi.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }
}