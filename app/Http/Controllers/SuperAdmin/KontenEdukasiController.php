<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\KontenEdukasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KontenEdukasiController extends Controller
{
    public function index()
    {
        $konten = KontenEdukasi::with('penulis')
            ->latest()
            ->get();

        return view('super_admin.kontenEdukasi.index', compact('konten'));
    }

    public function create()
    {
        return view('super_admin.kontenEdukasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'isi' => 'required|string',
            'kategori' => 'required|string|max:255',
            'status' => 'nullable|in:draft,publish,nonaktif',
        ]);

        $thumbnail = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')
                ->store('thumbnail', 'public');
        }

        KontenEdukasi::create([
            'judul' => $request->judul,
            'thumbnail' => $thumbnail,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'penulis' => Auth::id(),
            'status' => $request->status ?? 'draft',
        ]);

        return redirect()
            ->route('konten-edukasi.index')
            ->with('success', 'Konten edukasi berhasil dibuat.');
    }

    public function show(string $id)
    {
        $konten = KontenEdukasi::with('penulis')
            ->findOrFail($id);

        return view('super_admin.kontenEdukasi.show', compact('konten'));
    }

    public function edit(string $id)
    {
        $konten = KontenEdukasi::findOrFail($id);

        return view('super_admin.kontenEdukasi.edit', compact('konten'));
    }

    public function update(Request $request, string $id)
    {
        $konten = KontenEdukasi::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'isi' => 'required|string',
            'kategori' => 'required|string|max:255',
            'status' => 'nullable|in:draft,publish,nonaktif',
        ]);

        $thumbnail = $konten->thumbnail;

        if ($request->hasFile('thumbnail')) {

            if ($konten->thumbnail) {
                Storage::disk('public')->delete($konten->thumbnail);
            }

            $thumbnail = $request->file('thumbnail')
                ->store('thumbnail', 'public');
        }

        $konten->update([
            'judul' => $request->judul,
            'thumbnail' => $thumbnail,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'status' => $request->status ?? 'draft',
        ]);

        return redirect()
            ->route('konten-edukasi.index')
            ->with('success', 'Konten edukasi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $konten = KontenEdukasi::findOrFail($id);

        if ($konten->thumbnail) {
            Storage::disk('public')->delete($konten->thumbnail);
        }

        $konten->delete();

        return redirect()
            ->route('konten-edukasi.index')
            ->with('success', 'Konten edukasi berhasil dihapus.');
    }
}