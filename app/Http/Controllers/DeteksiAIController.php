<?php

namespace App\Http\Controllers;

use App\Models\DeteksiAI;
use App\Models\Laporan;
use Illuminate\Http\Request;

class DeteksiAiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deteksiAIs = DeteksiAI::with('laporan')->latest()->get();
        return view('deteksi_ai.index', compact('deteksiAIs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $laporans = Laporan::all();
        return view('deteksi_ai.create', compact('laporans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'laporan_id' => 'required|exists:laporans,id|unique:deteksi_ai,laporan_id',
            'jenis_objek' => 'required|string|max:100',
            'confidence' => 'required|numeric|between:0,1',
            'tingkat_kerusakan' => 'required|in:ringan,sedang,berat',
            'estimasi_prioritas' => 'required|in:kritis,sedang,rendah',
            'hasil_validasi' => 'required|in:valid,tidak_valid',
            'response_llm' => 'nullable|string',
        ]);

        DeteksiAI::create([
            'laporan_id' => $request->laporan_id,
            'jenis_objek' => $request->jenis_objek,
            'confidence' => $request->confidence,
            'tingkat_kerusakan' => $request->tingkat_kerusakan,
            'estimasi_prioritas' => $request->estimasi_prioritas,
            'hasil_validasi' => $request->hasil_validasi,
            'response_llm' => $request->response_llm,
        ]);

        return redirect()->route('deteksi_ai.index')->with('success', 'Data deteksi AI berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $deteksiAi = DeteksiAI::with([
            'laporan'
        ])->findOrFail($id);

        return view(
            'deteksi_ai.show',
            compact('deteksiAi')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $deteksiAI = DeteksiAI::findOrFail($id);
        $laporans = Laporan::all();
        return view('deteksi_ai.edit', compact('deteksiAI', 'laporans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $deteksiAI = DeteksiAI::findOrFail($id);

        $request->validate([
            'laporan_id' => 'required|exists:laporans,id|unique:deteksi_ai,laporan_id,' . $deteksiAI->id,
            'jenis_objek' => 'required|string|max:100',
            'confidence' => 'required|numeric|between:0,1',
            'tingkat_kerusakan' => 'required|in:ringan,sedang,berat',
            'estimasi_prioritas' => 'required|in:kritis,sedang,rendah',
            'hasil_validasi' => 'required|in:valid,tidak_valid',
            'response_llm' => 'nullable|string',
        ]);

        $deteksiAI->update([
            'laporan_id' => $request->laporan_id,
            'jenis_objek' => $request->jenis_objek,
            'confidence' => $request->confidence,
            'tingkat_kerusakan' => $request->tingkat_kerusakan,
            'estimasi_prioritas' => $request->estimasi_prioritas,
            'hasil_validasi' => $request->hasil_validasi,
            'response_llm' => $request->response_llm,
        ]);

        return redirect()->route('deteksi_ai.index')->with('success', 'Data deteksi AI berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deteksiAI = DeteksiAI::findOrFail($id);
        $deteksiAI->delete();
        return redirect()->route('deteksi_ai.index')->with('success', 'Data deteksi AI berhasil dihapus.');
    }
}
