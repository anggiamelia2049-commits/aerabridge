<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'masuk');

        $query = Laporan::with(['penugasan' => function ($q) {
            $q->where('petugas_id', Auth::id());
        }])
        ->whereHas('penugasan', function ($q) {
            $q->where('petugas_id', Auth::id());
        });

        if ($tab === 'selesai') {
            $query->whereHas('penugasan', function ($q) {
                $q->where('petugas_id', Auth::id())
                    ->where('status', 'selesai');
            });
        } else {
            $query->whereHas('penugasan', function ($q) {
                $q->where('petugas_id', Auth::id())
                    ->whereIn('status', ['ditugaskan', 'dalam_proses']);
            });
        }

        $laporan = $query->latest()->get();

        return view('Petugas.laporan.index', compact('laporan', 'tab'));
    }

    public function create()
    {
        abort(403, 'Petugas tidak memiliki akses untuk membuat laporan baru.');
    }

    public function store(Request $request)
    {
        abort(403, 'Petugas tidak memiliki akses untuk membuat laporan baru.');
    }

    public function show(string $id)
    {
        $laporan = Laporan::with(['penugasan' => function ($q) {
            $q->where('petugas_id', Auth::id());
        }])->findOrFail($id);

        $this->authorizeLaporan($laporan);

        return view('Petugas.laporan.show', compact('laporan'));
    }

    public function edit(string $id)
    {
        abort(403, 'Petugas tidak memiliki akses untuk mengubah isi laporan.');
    }

    public function update(Request $request, string $id)
    {
        abort(403, 'Petugas tidak memiliki akses untuk mengubah isi laporan.');
    }

    public function destroy(string $id)
    {
        abort(403, 'Petugas tidak memiliki akses untuk menghapus laporan.');
    }

    private function authorizeLaporan(Laporan $laporan): void
    {
        $adaTugasMilikPetugas = $laporan->penugasan()
            ->where('petugas_id', Auth::id())
            ->exists();

        abort_unless(
            $adaTugasMilikPetugas,
            403,
            'Anda tidak memiliki akses ke laporan ini.'
        );
    }
}