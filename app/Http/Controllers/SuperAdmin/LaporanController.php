<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\KategoriKerusakan;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporan = Laporan::with(['user', 'kategori', 'instansi', 'diverifikasiOleh'])->get();

        return view('Laporan.index', compact('laporan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = KategoriKerusakan::all();
        $instansis = Instansi::all();

        return view('Laporan.create', compact('kategoris', 'instansis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_kerusakan,id',
            'instansi_id' => 'required|exists:instansi,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'alamat' => 'nullable|string',
            'tingkat_prioritas' => 'nullable|in:Krisis,Sedang,Rendah',
            'status' => 'nullable|in:Menunggu,Diverifikasi,Diproses,Selesai,Ditolak'
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('laporan', 'public');
        }

        Laporan::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'instansi_id' => $request->instansi_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'alamat' => $request->alamat,
            'tingkat_prioritas' => $request->tingkat_prioritas ?? 'Sedang',
            'status' => $request->status ?? 'Menunggu',
            'diverifikasi_oleh' => null
        ]);

        return redirect()
            ->route('Laporan.index')
            ->with('success', 'Laporan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporan = Laporan::with(['user', 'kategori', 'instansi', 'diverifikasiOleh'])->findOrFail($id);

        return view('Laporan.show', compact('laporan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $laporan = Laporan::findOrFail($id);
        $kategoris = KategoriKerusakan::all();
        $instansis = Instansi::all();

        return view('Laporan.edit', compact('laporan', 'kategoris', 'instansis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'kategori_id' => 'required|exists:kategori_kerusakan,id',
            'instansi_id' => 'required|exists:instansi,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'alamat' => 'nullable|string',
            'tingkat_prioritas' => 'nullable|in:Krisis,Sedang,Rendah',
            'status' => 'nullable|in:Menunggu,Diverifikasi,Diproses,Selesai,Ditolak'
        ]);

        $foto = $laporan->foto;

        if ($request->hasFile('foto')) {
            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }

            $foto = $request->file('foto')->store('laporan', 'public');
        }

        $laporan->update([
            'kategori_id' => $request->kategori_id,
            'instansi_id' => $request->instansi_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'alamat' => $request->alamat,
            'tingkat_prioritas' => $request->tingkat_prioritas ?? 'Sedang',
            'status' => $request->status ?? 'Menunggu'
        ]);

        return redirect()
            ->route('Laporan.index')
            ->with('success', 'Laporan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }

        $laporan->delete();

        return redirect()
            ->route('Laporan.index')
            ->with('success', 'Laporan berhasil dihapus');
    }

    /**
     * Verify laporan.
     */
    public function verify(Request $request, string $id)
    {
        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Diverifikasi,Diproses,Selesai,Ditolak'
        ]);

        $laporan->update([
            'status' => $request->status,
            'diverifikasi_oleh' => Auth::id()
        ]);

        return redirect()
            ->route('Laporan.index')
            ->with('success', 'Status laporan berhasil diperbarui');
    }
}