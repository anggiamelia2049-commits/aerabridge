<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\PoinKontribusiLog;
use Illuminate\Support\Facades\Auth;

class PoinKontribusiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil seluruh riwayat poin milik user yang sedang login
        $riwayatPoin = PoinKontribusiLog::where('user_id', $user->id)
            ->with('laporan')
            ->latest()
            ->get();

        // Hitung total poin
        $totalPoin = PoinKontribusiLog::where('user_id', $user->id)
            ->sum('poin');

        return view('warga.poinKontribusi.index', compact(
            'user',
            'riwayatPoin',
            'totalPoin'
        ));
    }
}