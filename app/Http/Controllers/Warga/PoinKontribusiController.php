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

        $riwayatPoin = PoinKontribusiLog::where('user_id', $user->id)
            ->with('laporan')
            ->latest('id')
            ->get();

        $totalPoin = (int) $user->total_poin;

        return view('warga.poinKontribusi.index', compact('user', 'riwayatPoin', 'totalPoin'));
    }
}