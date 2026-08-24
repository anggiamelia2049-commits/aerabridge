<?php

namespace App\Http\Controllers;

use App\Models\KontenEdukasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KontenEdukasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $konten = KontenEdukasi::with('penulis')->get();
        return response()->json([
            'success' => true,
            'data' => $konten
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Untuk API biasanya tidak digunakan, tetapi bisa return view jika menggunakan blade
        // return view('konten_edukasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'isi' => 'required|string',
            'kategori' => 'required|string|max:255',
            'status' => 'nullable|in:draft,publish,nonaktif'
        ]);

        $konten = KontenEdukasi::create([
            'judul' => $request->judul,
            'thumbnail' => $request->thumbnail,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'penulis' => Auth::id(), // Mengambil ID user yang sedang login
            'status' => $request->status ?? 'draft'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Konten edukasi berhasil dibuat',
            'data' => $konten
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $konten = KontenEdukasi::with('penulis')->find($id);
        
        if (!$konten) {
            return response()->json([
                'success' => false,
                'message' => 'Konten edukasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $konten
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Untuk API biasanya tidak digunakan, tetapi bisa return view dengan data
        // $konten = KontenEdukasi::find($id);
        // return view('konten_edukasi.edit', compact('konten'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $konten = KontenEdukasi::find($id);
        
        if (!$konten) {
            return response()->json([
                'success' => false,
                'message' => 'Konten edukasi tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'isi' => 'sometimes|required|string',
            'kategori' => 'sometimes|required|string|max:255',
            'status' => 'nullable|in:draft,publish,nonaktif'
        ]);

        $konten->update($request->only(['judul', 'thumbnail', 'isi', 'kategori', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'Konten edukasi berhasil diperbarui',
            'data' => $konten
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $konten = KontenEdukasi::find($id);
        
        if (!$konten) {
            return response()->json([
                'success' => false,
                'message' => 'Konten edukasi tidak ditemukan'
            ], 404);
        }

        $konten->delete();

        return response()->json([
            'success' => true,
            'message' => 'Konten edukasi berhasil dihapus'
        ]);
    }
}