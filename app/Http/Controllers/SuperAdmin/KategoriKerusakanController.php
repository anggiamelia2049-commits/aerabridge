<?php

namespace App\Http\Controllers;

use App\Models\KategoriKerusakan;
use Illuminate\Http\Request;


class KategoriKerusakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = KategoriKerusakan::all();
        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_kerusakan', // lowercase
            'icon' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'warna_marker' => 'nullable|string|max:7',
            'status' => 'required|in:Aktif,Nonaktif'
        ]);

        KategoriKerusakan::create([
            'nama_kategori' => $request->nama_kategori,
            'icon' => $request->icon,
            'deskripsi' => $request->deskripsi,
            'warna_marker' => $request->warna_marker, // DIPERBAIKI
            'status' => $request->status
        ]);

        return redirect()->route('KategoriKerusakan.index')
            ->with('success', 'Kategori Kerusakan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = KategoriKerusakan::findOrFail($id);
        return view('kategori.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = KategoriKerusakan::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kategori = KategoriKerusakan::findOrFail($id);
        
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_kerusakan,nama_kategori,' . $id,
            'icon' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'warna_marker' => 'nullable|string|max:7',
            'status' => 'required|in:Aktif,Nonaktif'
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'icon' => $request->icon,
            'deskripsi' => $request->deskripsi,
            'warna_marker' => $request->warna_marker,
            'status' => $request->status
        ]);

        return redirect()->route('KategoriKerusakan.index')
            ->with('success', 'Kategori Kerusakan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = KategoriKerusakan::findOrFail($id);
        $kategori->delete();

        return redirect()->route('KategoriKerusakan.index')
            ->with('success', 'Kategori Kerusakan berhasil dihapus.');
    }
}