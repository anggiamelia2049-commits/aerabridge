<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan semua notifikasi terkait laporan milik warga yang login.
     */
    public function index()
    {
        $notifikasis = Notifikasi::whereHas('laporan', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with('laporan')
            ->latest()
            ->get();

        return view('warga.notifikasi.index', compact('notifikasis'));
    }

    /**
     * Tampilkan detail 1 notifikasi (pastikan terkait laporan milik warga ini).
     */
    public function show(string $id)
    {
        $notifikasi = Notifikasi::whereHas('laporan', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with('laporan')
            ->findOrFail($id);

        // Tandai sudah dibaca saat dibuka
        if (!$notifikasi->dibaca) {
            $notifikasi->update(['dibaca' => true]);
        }

        return view('warga.notifikasi.show', compact('notifikasi'));
    }

    // create(), store(), edit(), update(), destroy() TIDAK ADA.
    // Notifikasi dibuat otomatis oleh sistem (saat laporan diverifikasi,
    // ditugaskan, selesai, dll), bukan diinput manual oleh warga.
}