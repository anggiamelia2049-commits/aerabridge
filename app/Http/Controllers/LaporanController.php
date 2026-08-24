<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\KategoriKerusakan;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporan = Laporan::with(['user', 'kategori', 'instansi', 'diverifikasiOleh'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Untuk API biasanya tidak digunakan
        // return view('laporan.create');
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
            'foto' => 'nullable|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'alamat' => 'nullable|string',
            'tingkat_prioritas' => 'nullable|in:Krisis,Sedang,Rendah',
            'status' => 'nullable|in:Menunggu,Diverifikasi,Diproses,Selesai,Ditolak'
        ]);

        $laporan = Laporan::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'instansi_id' => $request->instansi_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'foto' => $request->foto,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'alamat' => $request->alamat,
            'tingkat_prioritas' => $request->tingkat_prioritas ?? 'Sedang',
            'status' => $request->status ?? 'Menunggu',
            'diverifikasi_oleh' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dibuat',
            'data' => $laporan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporan = Laporan::with(['user', 'kategori', 'instansi', 'diverifikasiOleh'])->find($id);
        
        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Untuk API biasanya tidak digunakan
        // $laporan = Laporan::find($id);
        // return view('laporan.edit', compact('laporan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $laporan = Laporan::find($id);
        
        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'kategori_id' => 'sometimes|required|exists:kategori_kerusakan,id',
            'instansi_id' => 'sometimes|required|exists:instansi,id',
            'judul' => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|required|string',
            'foto' => 'nullable|string|max:255',
            'latitude' => 'sometimes|required|numeric|between:-90,90',
            'longitude' => 'sometimes|required|numeric|between:-180,180',
            'alamat' => 'nullable|string',
            'tingkat_prioritas' => 'nullable|in:Krisis,Sedang,Rendah',
            'status' => 'nullable|in:Menunggu,Diverifikasi,Diproses,Selesai,Ditolak',
            'diverifikasi_oleh' => 'nullable|exists:users,id'
        ]);

        $laporan->update($request->only([
            'kategori_id', 
            'instansi_id', 
            'judul', 
            'deskripsi', 
            'foto', 
            'latitude', 
            'longitude', 
            'alamat', 
            'tingkat_prioritas', 
            'status',
            'diverifikasi_oleh'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diperbarui',
            'data' => $laporan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $laporan = Laporan::find($id);
        
        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus'
        ]);
    }

    /**
     * Additional method: Verify laporan
     */
    public function verify(Request $request, string $id)
    {
        $laporan = Laporan::find($id);
        
        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:Diverifikasi,Diproses,Selesai,Ditolak'
        ]);

        $laporan->update([
            'status' => $request->status,
            'diverifikasi_oleh' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status laporan berhasil diperbarui',
            'data' => $laporan
        ]);
    }

    /**
     * Get laporan by status
     */
    public function getByStatus($status)
    {
        $laporan = Laporan::with(['user', 'kategori', 'instansi'])
            ->where('status', $status)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /**
     * Get laporan by user
     */
    public function getByUser($userId)
    {
        $laporan = Laporan::with(['kategori', 'instansi', 'diverifikasiOleh'])
            ->where('user_id', $userId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }
}