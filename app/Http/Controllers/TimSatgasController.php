<?php

namespace App\Http\Controllers;

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
            'tim_satgas.index',
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
            'tim_satgas.create',
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
            ->route('tim-satgas.index')
            ->with('success', 'Tim Satgas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TimSatgas $timSatgas)
    {
        $timSatgas->load('instansi');

        return view(
            'tim_satgas.show',
            compact('timSatgas')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $instansi = Instansi::all();

        return view(
            'tim_satgas.edit',
            compact('timSatgas', 'instansi')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TimSatgas $timSatgas)
    {
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
            ->route('tim-satgas.index')
            ->with('success', 'Tim Satgas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimSatgas $timSatgas)
    {
        $timSatgas->delete();

        return redirect()
            ->route('tim-satgas.index')
            ->with('success', 'Tim Satgas berhasil dihapus.');
    }
}
