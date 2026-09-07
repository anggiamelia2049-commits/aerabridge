<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Laporan;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifikasi = Notifikasi::with('laporan')->get();
        
        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Untuk API biasanya tidak digunakan
        // return view('notifikasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'laporan_id' => 'nullable|exists:laporan,id',
            'judul' => 'required|string|max:150',
            'isi' => 'required|string',
            'tipe' => 'required|in:informasi,warning,reward,lainnya',
            'dibaca' => 'nullable|boolean'
        ]);

        $notifikasi = Notifikasi::create([
            'laporan_id' => $request->laporan_id,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tipe' => $request->tipe,
            'dibaca' => $request->dibaca ?? false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dibuat',
            'data' => $notifikasi
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notifikasi = Notifikasi::with('laporan')->find($id);
        
        if (!$notifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Untuk API biasanya tidak digunakan
        // $notifikasi = Notifikasi::find($id);
        // return view('notifikasi.edit', compact('notifikasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $notifikasi = Notifikasi::find($id);
        
        if (!$notifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'laporan_id' => 'nullable|exists:laporan,id',
            'judul' => 'sometimes|required|string|max:150',
            'isi' => 'sometimes|required|string',
            'tipe' => 'sometimes|required|in:informasi,warning,reward,lainnya',
            'dibaca' => 'nullable|boolean'
        ]);

        $notifikasi->update($request->only([
            'laporan_id',
            'judul',
            'isi',
            'tipe',
            'dibaca'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil diperbarui',
            'data' => $notifikasi
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notifikasi = Notifikasi::find($id);
        
        if (!$notifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notifikasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus'
        ]);
    }

    /**
     * Get unread notifications
     */
    public function getUnread()
    {
        $notifikasi = Notifikasi::with('laporan')
            ->where('dibaca', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(string $id)
    {
        $notifikasi = Notifikasi::find($id);
        
        if (!$notifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notifikasi->update(['dibaca' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai dibaca',
            'data' => $notifikasi
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notifikasi::where('dibaca', false)->update(['dibaca' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sebagai dibaca'
        ]);
    }

    /**
     * Get notifications by type
     */
    public function getByType($tipe)
    {
        $notifikasi = Notifikasi::with('laporan')
            ->where('tipe', $tipe)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    /**
     * Get notifications count (total and unread)
     */
    public function getCount()
    {
        $total = Notifikasi::count();
        $unread = Notifikasi::where('dibaca', false)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'unread' => $unread
            ]
        ]);
    }

    /**
     * Get latest notifications with limit
     */
    public function getLatest(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $limit = $request->limit ?? 10;

        $notifikasi = Notifikasi::with('laporan')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }
}