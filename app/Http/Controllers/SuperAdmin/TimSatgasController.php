<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TimSatgas;
use App\Models\Instansi;
use Illuminate\Http\Request;

class TimSatgasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timSatgas = TimSatgas::with('instansi')
            ->latest()
            ->get();

        return view(
            'SuperAdmin.timSatgas.index',
            compact('timSatgas')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instansi = Instansi::all();

        return view(
            'SuperAdmin.timSatgas.create',
            compact('instansi')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'instansi_id' => 'required|exists:instansi,id',
            'nama_tim' => 'required|string|max:255',
            'ketua' => 'required|string|max:255',
            'jumlah_anggota' => 'required|integer|min:0',
            'kontak' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        TimSatgas::create([
            'instansi_id' => $request->instansi_id,
            'nama_tim' => $request->nama_tim,
            'ketua' => $request->ketua,
            'jumlah_anggota' => $request->jumlah_anggota,
            'kontak' => $request->kontak,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('super_admin.tim-satgas.index')
            ->with('success', 'Tim Satgas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * FIX: sebelumnya pakai implicit route model binding (TimSatgas $timSatgas),
     * tapi nama parameter route yang dihasilkan Laravel dari 'tim-satgas' adalah
     * {tim_satgas} (garis bawah), bukan {timSatgas} (camelCase) — jadi binding
     * gagal diam-diam dan menghasilkan model kosong, bukan error. Diganti pakai
     * pencarian manual supaya tidak bergantung pada kecocokan nama parameter.
     */
    public function show(string $id)
    {
        $timSatgas = TimSatgas::with('instansi')->findOrFail($id);

        return view(
            'SuperAdmin.timSatgas.show',
            compact('timSatgas')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $timSatgas = TimSatgas::findOrFail($id);
        $instansi = Instansi::all();

        return view(
            'SuperAdmin.timSatgas.edit',
            compact('timSatgas', 'instansi')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $timSatgas = TimSatgas::findOrFail($id);

        $request->validate([
            'instansi_id' => 'required|exists:instansi,id',
            'nama_tim' => 'required|string|max:255',
            'ketua' => 'required|string|max:255',
            'jumlah_anggota' => 'required|integer|min:0',
            'kontak' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $timSatgas->update([
            'instansi_id' => $request->instansi_id,
            'nama_tim' => $request->nama_tim,
            'ketua' => $request->ketua,
            'jumlah_anggota' => $request->jumlah_anggota,
            'kontak' => $request->kontak,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('super_admin.tim-satgas.index')
            ->with('success', 'Tim Satgas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $timSatgas = TimSatgas::findOrFail($id);
        $timSatgas->delete();

        return redirect()
            ->route('super_admin.tim-satgas.index')
            ->with('success', 'Tim Satgas berhasil dihapus.');
    }
}