<?php

namespace App\Http\Controllers;

use App\Models\KontenEdukasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KontenEdukasiController extends Controller
{
    /**
     * Menampilkan semua konten edukasi.
     */
    public function index()
    {
        $konten = KontenEdukasi::with('super_admin')->get();

        return view('KontenEdukasi.index', compact('konten'));
    }

    /**
     * Menampilkan form tambah konten.
     */
    public function create()
    {
        return view('KontenEdukasi.create');
    }

    /**
     * Menyimpan konten baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'isi' => 'required|string',
            'kategori' => 'required|string|max:255',
            'status' => 'nullable|in:draft,publish,nonaktif'
        ]);

        $thumbnail = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->store('thumbnail', 'public');
        }

        KontenEdukasi::create([
            'judul' => $request->judul,
            'thumbnail' => $thumbnail,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'super_admin' => 1,
            'status' => $request->status ?? 'draft'
        ]);

        return redirect()
            ->route('KontenEdukasi.index')
            ->with('success', 'Konten edukasi berhasil dibuat');
    }

    /**
     * Menampilkan detail konten.
     */
    public function show(string $id)
    {
        $konten = KontenEdukasi::with('super_admin')->findOrFail($id);

        return view('KontenEdukasi.show', compact('konten'));
    }

    /**
     * Menampilkan form edit konten.
     */
    public function edit(string $id)
    {
        $konten = KontenEdukasi::findOrFail($id);

        return view('KontenEdukasi.edit', compact('konten'));
    }

    /**
     * Memperbarui konten.
     */
    public function update(Request $request, string $id)
    {
        $konten = KontenEdukasi::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'isi' => 'required|string',
            'kategori' => 'required|string|max:255',
            'status' => 'nullable|in:draft,publish,nonaktif'
        ]);

        $thumbnail = $konten->thumbnail;

        if ($request->hasFile('thumbnail')) {
            if ($konten->thumbnail) {
                Storage::disk('public')->delete($konten->thumbnail);
            }

            $thumbnail = $request->file('thumbnail')->store('thumbnail', 'public');
        }

        $konten->update([
            'judul' => $request->judul,
            'thumbnail' => $thumbnail,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'status' => $request->status ?? 'draft'
        ]);

        return redirect()
            ->route('KontenEdukasi.index')
            ->with('success', 'Konten edukasi berhasil diperbarui');
    }

    /**
     * Menghapus konten.
     */
    public function destroy(string $id)
    {
        $konten = KontenEdukasi::findOrFail($id);

        if ($konten->thumbnail) {
            Storage::disk('public')->delete($konten->thumbnail);
        }

        $konten->delete();

        return redirect()
            ->route('KontenEdukasi.index')
            ->with('success', 'Konten edukasi berhasil dihapus');
    }
}