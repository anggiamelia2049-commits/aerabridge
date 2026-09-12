<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\KontenEdukasi;
use App\Models\PoinKontribusiLog;
use App\Models\UserEdukasiProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserEdukasiProgressController extends Controller
{
    /**
     * Tampilkan daftar konten edukasi & progress baca milik warga yang login.
     */
    public function index()
    {
        $user = Auth::user();

        // Semua konten edukasi, digabung dengan progress milik user (kalau ada)
        $kontenList = KontenEdukasi::with(['progress' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        return view('warga.user-edukasi.index', compact('kontenList'));
    }

    /**
     * Tampilkan detail 1 konten edukasi untuk dibaca warga.
     */
    public function show(string $kontenId)
    {
        $konten = KontenEdukasi::findOrFail($kontenId);

        $progress = UserEdukasiProgress::firstOrCreate(
            ['user_id' => Auth::id(), 'konten_id' => $kontenId],
            ['status' => 'belum_dibaca', 'progress' => 0]
        );

        return view('warga.user-edukasi.show', compact('konten', 'progress'));
    }

    /**
     * Tandai konten selesai dibaca oleh warga, otomatis dapat +10 poin
     * (sesuai proposal 6.B.5: "+10 (baca edukasi)").
     */
    public function tandaiSelesai(Request $request, string $kontenId)
    {
        $user = Auth::user();

        $progress = UserEdukasiProgress::firstOrCreate(
            ['user_id' => $user->id, 'konten_id' => $kontenId],
            ['status' => 'belum_dibaca', 'progress' => 0]
        );

        // Cegah dapat poin berkali-kali untuk konten yang sama
        if ($progress->status === 'selesai') {
            return back()->with('info', 'Konten ini sudah pernah kamu selesaikan.');
        }

        $progress->update([
            'status' => 'selesai',
            'progress' => 100,
            'selesai_pada' => now(),
        ]);

        PoinKontribusiLog::create([
            'user_id' => $user->id,
            'laporan_id' => null,
            'jenis_aktivitas' => 'baca_edukasi',
            'poin' => 10,
            'keterangan' => 'Menyelesaikan konten edukasi: ' . $progress->konten->judul,
        ]);

        return redirect()->route('warga.user-edukasi.index')
            ->with('success', 'Selamat! Kamu mendapat 10 poin kontribusi.');
    }

    // create(), store(), edit(), update(), destroy() generik TIDAK ADA.
    // Progress dibuat otomatis saat warga membuka konten (lihat show()),
    // dan diselesaikan lewat method tandaiSelesai(), bukan form CRUD manual.
}