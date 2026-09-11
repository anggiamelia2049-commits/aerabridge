<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DeteksiAi;
use App\Models\Laporan;
use Illuminate\Http\Request;

class DeteksiAiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deteksiAIs = DeteksiAi::with('laporan')->latest()->get();

        return view('SuperAdmin.deteksiAi.index', compact('deteksiAIs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $laporans = Laporan::all();
        return view('SuperAdmin.deteksiAi.create', compact('laporans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'laporan_id' => 'required|exists:laporan,id|unique:deteksi_ai,laporan_id',
            'jenis_objek' => 'required|string|max:100',
            'confidence' => 'required|numeric|between:0,1',
            'tingkat_kerusakan' => 'required|in:ringan,sedang,berat',
            'estimasi_prioritas' => 'required|in:kritis,sedang,rendah',
            'hasil_validasi' => 'required|in:valid,tidak_valid',
            'response_llm' => 'nullable|string',
        ]);

        DeteksiAi::create([
            'laporan_id' => $request->laporan_id,
            'jenis_objek' => $request->jenis_objek,
            'confidence' => $request->confidence,
            'tingkat_kerusakan' => $request->tingkat_kerusakan,
            'estimasi_prioritas' => $request->estimasi_prioritas,
            'hasil_validasi' => $request->hasil_validasi,
            'response_llm' => $request->response_llm,
        ]);

        return redirect()->route('deteksi-ai.index')->with('success', 'Data deteksi AI berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $deteksiAi = DeteksiAi::with(['laporan'])->findOrFail($id);
        return view('SuperAdmin.deteksiAi.show', compact('deteksiAi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $deteksiAi = DeteksiAi::findOrFail($id);
        $laporans = Laporan::all();
        return view('SuperAdmin.deteksiAi.edit', compact('deteksiAi', 'laporans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $deteksiAi = DeteksiAi::findOrFail($id);

        $request->validate([
            'laporan_id' => 'required|exists:laporan,id|unique:deteksi_ai,laporan_id,' . $deteksiAi->id,
            'jenis_objek' => 'required|string|max:100',
            'confidence' => 'required|numeric|between:0,1',
            'tingkat_kerusakan' => 'required|in:ringan,sedang,berat',
            'estimasi_prioritas' => 'required|in:kritis,sedang,rendah',
            'hasil_validasi' => 'required|in:valid,tidak_valid',
            'response_llm' => 'nullable|string',
        ]);

        $deteksiAi->update([
            'laporan_id' => $request->laporan_id,
            'jenis_objek' => $request->jenis_objek,
            'confidence' => $request->confidence,
            'tingkat_kerusakan' => $request->tingkat_kerusakan,
            'estimasi_prioritas' => $request->estimasi_prioritas,
            'hasil_validasi' => $request->hasil_validasi,
            'response_llm' => $request->response_llm,
        ]);

        return redirect()->route('deteksi-ai.index')->with('success', 'Data deteksi AI berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deteksiAi = DeteksiAi::findOrFail($id);
        $deteksiAi->delete();
        return redirect()->route('deteksi-ai.index')->with('success', 'Data deteksi AI berhasil dihapus.');
    }
}