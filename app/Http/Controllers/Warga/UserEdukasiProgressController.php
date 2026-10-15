<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\KontenEdukasi;
use App\Models\PoinKontribusiLog;
use App\Models\UserEdukasiProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserEdukasiProgressController extends Controller
{
    /**
     * Tampilkan daftar konten edukasi dan progress warga.
     */
    public function index()
    {
        $user = Auth::user();

        $kontenList = KontenEdukasi::with([
            'progress' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
        ])->get();

        return view('warga.userEdukasi.index', compact('kontenList'));
    }

    /**
     * Tampilkan detail konten edukasi.
     */
    public function show(string $user_edukasi)
    {
        $konten = KontenEdukasi::findOrFail($user_edukasi);

        $progress = UserEdukasiProgress::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'konten_id' => $konten->id,
            ],
            [
                'status' => 'belum_dibaca',
                'progress' => 0,
            ]
        );

        return view('warga.userEdukasi.show', compact('konten', 'progress'));
    }

    /**
     * Tandai konten selesai dan berikan 10 poin kontribusi.
     */
    public function tandaiSelesai(Request $request, string $user_edukasi)
    {
        $user = Auth::user();
        $konten = KontenEdukasi::findOrFail($user_edukasi);

        $sudahSelesai = DB::transaction(function () use ($user, $konten) {
            // firstOrCreate agar tidak error kalau baris progress belum ada
            $progress = UserEdukasiProgress::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'konten_id' => $konten->id,
                ],
                [
                    'status' => 'belum_dibaca',
                    'progress' => 0,
                ]
            );

            // Kunci baris agar tidak terjadi double klik / race condition
            $progress = UserEdukasiProgress::whereKey($progress->id)
                ->lockForUpdate()
                ->first();

            // Jangan berikan poin dua kali untuk konten yang sama.
            if ($progress->status === 'selesai') {
                return true;
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
                'keterangan' => 'Menyelesaikan konten edukasi: ' . $konten->judul,
            ]);

            return false;
        });

        if ($sudahSelesai) {
            return back()->with('info', 'Konten ini sudah pernah kamu selesaikan.');
        }

        return redirect()
            ->route('warga.user-edukasi.show', $konten->id)
            ->with('success', 'Konten edukasi berhasil diselesaikan (+10 poin).');
    }
}