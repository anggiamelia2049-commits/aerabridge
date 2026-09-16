<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Kotak notifikasi untuk akun instansi.
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

        $notifikasi = $query->latest()->get();

        $jumlahBelumDibaca = Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->count();

        return view('instansi.Notifikasi.index', compact(
            'notifikasi',
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

        return view('instansi.Notifikasi.show', compact('notifikasi'));
    }

    public function edit(string $id)
    {
        abort(403, 'Notifikasi tidak dapat diubah.');
    }

    /**
     * Tandai satu notifikasi (atau semua) sebagai sudah dibaca.
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
            ->route('instansi.notifikasi.index')
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
