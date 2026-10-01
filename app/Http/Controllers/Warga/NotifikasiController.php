<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Kotak notifikasi untuk akun warga yang login.
     * Scoping langsung ke kolom user_id (bukan lewat relasi laporan),
     * supaya notifikasi tanpa laporan_id (mis. poin masuk, konversi
     * AERA Pay) tetap muncul.
     */
    public function index(Request $request)
    {
        $query = Notifikasi::with('laporan')
            ->where('user_id', Auth::id());

        if ($request->get('filter') === 'belum_dibaca') {
            $query->where('dibaca', false);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $notifikasis = $query->latest()->get();

        $jumlahBelumDibaca = Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->count();

        return view('warga.notifikasi.index', compact(
            'notifikasis',
            'jumlahBelumDibaca'
        ));
    }

    public function create()
    {
        abort(403, 'Notifikasi dibuat otomatis oleh sistem.');
    }

    public function store(Request $request)
    {
        abort(403, 'Notifikasi dibuat otomatis oleh sistem.');
    }

    /**
     * Buka notifikasi, otomatis ditandai sudah dibaca.
     */
    public function show(string $id)
    {
        $notifikasi = Notifikasi::with('laporan')->findOrFail($id);

        $this->authorizeNotifikasi($notifikasi);

        if (! $notifikasi->dibaca) {
            $notifikasi->update(['dibaca' => true]);
        }

        return view('warga.notifikasi.show', compact('notifikasi'));
    }

    public function edit(string $id)
    {
        abort(403, 'Notifikasi tidak dapat diubah.');
    }

    /**
     * Tandai satu notifikasi (atau semua, lewat id="semua") sebagai dibaca.
     */
    public function update(Request $request, string $id)
    {
        if ($id === 'semua') {
            Notifikasi::where('user_id', Auth::id())
                ->where('dibaca', false)
                ->update(['dibaca' => true]);

            return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
        }

        $notifikasi = Notifikasi::findOrFail($id);

        $this->authorizeNotifikasi($notifikasi);

        $notifikasi->update([
            'dibaca' => $request->boolean('dibaca', true),
        ]);

        return back()->with('success', 'Notifikasi diperbarui.');
    }

    public function destroy(string $id)
    {
        $notifikasi = Notifikasi::findOrFail($id);

        $this->authorizeNotifikasi($notifikasi);

        $notifikasi->delete();

        return redirect()
            ->route('warga.notifikasi.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    private function authorizeNotifikasi(Notifikasi $notifikasi): void
    {
        abort_unless(
            $notifikasi->user_id === Auth::id(),
            403,
            'Anda tidak memiliki akses ke notifikasi ini.'
        );
    }
}