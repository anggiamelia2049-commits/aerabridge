<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan laporan yang terhubung dengan penugasan milik petugas login.
     * Dua tab: "Laporan Masuk" (belum selesai) & "Laporan Penyelesaian" (sudah selesai).
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'masuk'); // masuk | selesai

        $query = Laporan::with(['penugasan' => function ($q) {
                $q->where('petugas_id', Auth::id());
            }])
            ->whereHas('penugasan', fn ($q) => $q->where('petugas_id', Auth::id()));

        if ($tab === 'selesai') {
            $query->whereHas('penugasan', fn ($q) => $q->where('petugas_id', Auth::id())
                ->where('status', 'selesai'));
        } else {
            $query->whereHas('penugasan', fn ($q) => $q->where('petugas_id', Auth::id())
                ->whereIn('status', ['ditugaskan', 'dalam_proses']));
        }

        $laporan = $query->latest()->get();

        return view('Petugas.laporan.index', compact('laporan', 'tab'));
    }

    /**
     * Show the form for creating a new resource.
     * Tidak digunakan: laporan dibuat oleh Masyarakat, bukan Petugas.
     */
    public function create()
    {
        abort(403, 'Petugas tidak memiliki akses untuk membuat laporan baru.');
    }

    /**
     * Store a newly created resource in storage.
     * Tidak digunakan: lihat catatan pada method create().
     */
    public function store(Request $request)
    {
        abort(403, 'Petugas tidak memiliki akses untuk membuat laporan baru.');
    }

    /**
     * Display the specified resource.
     * Detail laporan beserta status penugasan yang terkait, sepanjang
     * laporan tersebut memang ditugaskan kepada petugas yang login.
     */
    public function show(string $id)
    {
        $laporan = Laporan::with(['penugasan' => function ($q) {
                $q->where('petugas_id', Auth::id());
            }])->findOrFail($id);

        $this->authorizeLaporan($laporan);

        return view('Petugas.laporan.show', compact('laporan'));
    }

    /**
     * Show the form for editing the specified resource.
     * Tidak digunakan: konten laporan tidak boleh diubah oleh Petugas.
     * Penutupan tugas dilakukan lewat closing report di Penugasan\PenugasanController.
     */
    public function edit(string $id)
    {
        abort(403, 'Petugas tidak memiliki akses untuk mengubah isi laporan.');
    }

    /**
     * Update the specified resource in storage.
     * Tidak digunakan: lihat catatan pada method edit().
     */
    public function update(Request $request, string $id)
    {
        abort(403, 'Petugas tidak memiliki akses untuk mengubah isi laporan.');
    }

    /**
     * Remove the specified resource from storage.
     * Tidak digunakan: petugas tidak berwenang menghapus laporan.
     */
    public function destroy(string $id)
    {
        abort(403, 'Petugas tidak memiliki akses untuk menghapus laporan.');
    }

    /**
     * Memastikan laporan yang diakses memang ditugaskan ke petugas yang login.
     */
    private function authorizeLaporan(Laporan $laporan): void
    {
        $adaTugasMilikPetugas = $laporan->penugasan()
            ->where('petugas_id', Auth::id())
            ->exists();

        abort_unless($adaTugasMilikPetugas, 403, 'Anda tidak memiliki akses ke laporan ini.');
    }
}