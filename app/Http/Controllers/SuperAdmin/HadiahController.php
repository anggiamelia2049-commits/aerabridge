<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Hadiah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HadiahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hadiahs = Hadiah::latest()->get();

        return view('super_admin.hadiah.index', compact('hadiahs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super_admin.hadiah.create');
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

        $data = [
            'nama_hadiah' => $request->nama_hadiah,
            'deskripsi' => $request->deskripsi,
            'poin_dibutuhkan' => $request->poin_dibutuhkan,
            'stok' => $request->stok,
            'status' => $request->status,
        ];

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('hadiah', 'public');
        }

        Hadiah::create($data);

        return redirect()
            ->route('hadiah.index')
            ->with('success', 'Hadiah berhasil ditambahkan.');
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

        return view('super_admin.hadiah.edit', compact('hadiah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $hadiah = Hadiah::findOrFail($id);

        $request->validate([
            'nama_hadiah' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'poin_dibutuhkan' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:tersedia,habis,nonaktif',
        ]);

        $data = [
            'nama_hadiah' => $request->nama_hadiah,
            'deskripsi' => $request->deskripsi,
            'poin_dibutuhkan' => $request->poin_dibutuhkan,
            'stok' => $request->stok,
            'status' => $request->status,
        ];

        if ($request->hasFile('gambar')) {

            // Hapus gambar lama jika ada
            if ($hadiah->gambar) {
                Storage::disk('public')->delete($hadiah->gambar);
            }

            // Simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('hadiah', 'public');
        }

        $hadiah->update($data);

        return redirect()
            ->route('hadiah.index')
            ->with('success', 'Hadiah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hadiah = Hadiah::findOrFail($id);

        // Hapus file gambar jika ada
        if ($hadiah->gambar) {
            Storage::disk('public')->delete($hadiah->gambar);
        }

        $hadiah->delete();

        return redirect()
            ->route('hadiah.index')
            ->with('success', 'Hadiah berhasil dihapus.');
    }
}