<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenugasanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penugasan::with(['laporan', 'timSatgas'])
            ->where('petugas_id', Auth::id());

        if ($request->filled('filter')) {
            match ($request->filter) {
                'aktif' => $query->whereIn('status', [
                    'ditugaskan',
                    'dalam_proses'
                ]),

                'prioritas' => $query->whereHas('laporan', function ($q) {
                    $q->where('tingkat_prioritas', 'Krisis');
                }),

                'selesai' => $query->where('status', 'selesai'),

                default => null,
            };
        }

        $penugasan = $query
            ->latest('tanggal_penugasan')
            ->get();

        return view('Petugas.penugasan.index', compact('penugasan'));
    }

    public function show(string $id)
    {
        $penugasan = Penugasan::with([
            'laporan',
            'timSatgas'
        ])->findOrFail($id);

        abort_unless(
            $penugasan->petugas_id === Auth::id(),
            403,
            'Anda tidak memiliki akses ke tugas ini.'
        );

        return view('Petugas.penugasan.show', compact('penugasan'));
    }

    public function update(Request $request, string $id)
    {
        $penugasan = Penugasan::findOrFail($id);

        abort_unless(
            $penugasan->petugas_id === Auth::id(),
            403,
            'Anda tidak memiliki akses ke tugas ini.'
        );

        if ($request->status === 'dalam_proses') {

            if ($penugasan->status !== 'ditugaskan') {
                return back()->with('error', 'Tugas ini tidak dapat dimulai.');
            }

            $penugasan->update([
                'status' => 'dalam_proses'
            ]);

            return back()->with('success', 'Tugas berhasil dimulai.');
        }

        return back()->with('error', 'Status tidak valid.');
    }
}