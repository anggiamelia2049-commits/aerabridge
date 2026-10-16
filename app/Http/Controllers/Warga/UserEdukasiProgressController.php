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
    public function index()
    {
        $user = Auth::user();

        $kontenList = KontenEdukasi::with([
            'progress' => fn ($q) => $q->where('user_id', $user->id),
        ])->get();

        return view('warga.userEdukasi.index', compact('kontenList'));
    }

    public function show(string $user_edukasi)
    {
        $konten = KontenEdukasi::findOrFail($user_edukasi);

        $progress = UserEdukasiProgress::firstOrCreate(
            ['user_id' => Auth::id(), 'konten_id' => $konten->id],
            ['status' => 'belum_dibaca', 'progress' => 0]
        );

        return view('warga.userEdukasi.show', compact('konten', 'progress'));
    }

    public function tandaiSelesai(Request $request, string $user_edukasi)
    {
        $user = Auth::user();
        $konten = KontenEdukasi::findOrFail($user_edukasi);

        $sudahSelesai = DB::transaction(function () use ($user, $konten) {
            $progress = UserEdukasiProgress::firstOrCreate(
                ['user_id' => $user->id, 'konten_id' => $konten->id],
                ['status' => 'belum_dibaca', 'progress' => 0]
            );

            $progress = UserEdukasiProgress::whereKey($progress->id)->lockForUpdate()->first();

            if ($progress->status === 'selesai') {
                return true;
            }

            $progress->update([
                'status' => 'selesai',
                'progress' => 100,
                'selesai_pada' => now(),
            ]);

            PoinKontribusiLog::tambahPoin(
                $user->id, 10, 'baca_edukasi', null,
                'Menyelesaikan konten edukasi: ' . $konten->judul,
                $konten->id
            );

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