<?php

namespace App\Http\Controllers;

use App\Models\Hadiah;
use Illuminate\Http\Request;

class HadiahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hadiahs = Hadiah::latest()->get();
        return view('hadiah.index', compact('hadiahs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hadiah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_hadiah' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'poin_dibutuhkan' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:tersedia,habis,nonaktif',
        ]);

        Hadiah::create([
            'nama_hadiah' => $request->nama_hadiah,
            'deskripsi' => $request->deskripsi,
            'poin_dibutuhkan' => $request->poin_dibutuhkan,
            'stok' => $request->stok,
            'gambar' => $request->gambar,
            'status' => $request->status,
        ]);

        return redirect()->route('hadiah.index')->with('success', 'Hadiah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hadiah = Hadiah::findOrFail($id);
        return view('Hadiah.edit', compact('hadiah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_hadiah' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'poin_dibutuhkan' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:tersedia,habis,nonaktif',
        ]);

        $hadiah = Hadiah::findOrFail($id);
        $hadiah->update([
            'nama_hadiah' => $request->nama_hadiah,
            'deskripsi' => $request->deskripsi,
            'poin_dibutuhkan' => $request->poin_dibutuhkan,
            'stok' => $request->stok,
            'gambar' => $request->gambar,
            'status' => $request->status,
        ]);

        return redirect()->route('hadiah.index')->with('success', 'Hadiah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hadiah = Hadiah::findOrFail($id);
        $hadiah->delete();

        return redirect()->route('hadiah.index')->with('success', 'Instansi berhasil dihapus.');
    }
}
