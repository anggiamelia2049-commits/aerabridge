<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\Laporan;
use App\Models\TimSatgas;
use App\Models\User;
use Illuminate\Http\Request;

class PenugasanController extends Controller
{
    public function index()
    {
        $penugasan = Penugasan::with([
            'laporan',
            'timSatgas',
            'petugas'
        ])
            ->latest()
            ->get();

        return view('penugasan.index', compact('penugasan'));
    }

    public function create()
    {
        $laporan = Laporan::all();
        $timSatgas = TimSatgas::where('status', 'aktif')->get();
        $petugas = User::where('role', 'petugas')
            ->where('status', 'Aktif')
            ->get();

        return view('penugasan.create', compact(
            'laporan',
            'timSatgas',
            'petugas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'laporan_id' => 'required|exists:laporan,id',
            'tim_satgas_id' => 'required|exists:tim_satgas,id',
            'petugas_id' => 'nullable|exists:users,id',
            'status' => 'required|in:ditugaskan,dalam_proses,selesai,dibatalkan',
            'tanggal_penugasan' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_penugasan',
            'catatan' => 'nullable|string',
        ]);

        Penugasan::create([
            'laporan_id' => $request->laporan_id,
            'tim_satgas_id' => $request->tim_satgas_id,
            'petugas_id' => $request->petugas_id,
            'status' => $request->status,
            'tanggal_penugasan' => $request->tanggal_penugasan,
            'tanggal_selesai' => $request->tanggal_selesai,
            'catatan' => $request->catatan,
        ]);

        return redirect()
            ->route('penugasan.index')
            ->with('success', 'Penugasan berhasil ditambahkan.');
    }

    public function show(Penugasan $penugasan)
    {
        $penugasan->load([
            'laporan',
            'timSatgas',
            'petugas'
        ]);

        return view('penugasan.show', compact('penugasan'));
    }

    public function edit(Penugasan $penugasan)
    {
        $laporan = Laporan::all();
        $timSatgas = TimSatgas::where('status', 'aktif')->get();
        $petugas = User::where('role', 'petugas')
            ->where('status', 'Aktif')
            ->get();

        return view('penugasan.edit', compact(
            'penugasan',
            'laporan',
            'timSatgas',
            'petugas'
        ));
    }

    public function update(Request $request, Penugasan $penugasan)
    {
        $request->validate([
            'laporan_id' => 'required|exists:laporan,id',
            'tim_satgas_id' => 'required|exists:tim_satgas,id',
            'petugas_id' => 'nullable|exists:users,id',
            'status' => 'required|in:ditugaskan,dalam_proses,selesai,dibatalkan',
            'tanggal_penugasan' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_penugasan',
            'catatan' => 'nullable|string',
        ]);

        $penugasan->update([
            'laporan_id' => $request->laporan_id,
            'tim_satgas_id' => $request->tim_satgas_id,
            'petugas_id' => $request->petugas_id,
            'status' => $request->status,
            'tanggal_penugasan' => $request->tanggal_penugasan,
            'tanggal_selesai' => $request->tanggal_selesai,
            'catatan' => $request->catatan,
        ]);

        return redirect()
            ->route('penugasan.index')
            ->with('success', 'Penugasan berhasil diperbarui.');
    }

    public function destroy(Penugasan $penugasan)
    {
        $penugasan->delete();

        return redirect()
            ->route('penugasan.index')
            ->with('success', 'Penugasan berhasil dihapus.');
    }
}
