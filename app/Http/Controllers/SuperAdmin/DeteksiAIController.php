<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DeteksiAi;
use Illuminate\Http\Request;

class DeteksiAiController extends Controller
{
    
    public function index(Request $request)
    {
        $query = DeteksiAi::with('laporan');

        if ($request->filled('tingkat_kerusakan')) {
            $query->where('tingkat_kerusakan', $request->tingkat_kerusakan);
        }

        if ($request->filled('estimasi_prioritas')) {
            $query->where('estimasi_prioritas', $request->estimasi_prioritas);
        }

        if ($request->filled('hasil_validasi')) {
            $query->where('hasil_validasi', $request->hasil_validasi);
        }

        $deteksiAIs = $query->latest()
            ->paginate(20)
            ->withQueryString();

        return view('SuperAdmin.deteksiAi.index', compact('deteksiAIs'));
    }

    public function show(string $id)
    {
        $deteksiAi = DeteksiAi::with('laporan')->findOrFail($id);

        return view('SuperAdmin.deteksiAi.show', compact('deteksiAi'));
    }
}