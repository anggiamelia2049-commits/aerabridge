<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Support\Facades\Route;

class PetaKerusakanController extends Controller
{
    public function index()
    {
        // CATATAN: sesuaikan nama relasi/kolom dengan model Laporan Anda.
        $titikPeta = Laporan::with('kategori')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->latest()
            ->get()
            ->map(fn ($l) => [
                'id'                => $l->id,
                'judul'             => $l->judul,
                'alamat'            => $l->alamat,
                'latitude'          => (float) $l->latitude,
                'longitude'         => (float) $l->longitude,
                'tingkat_prioritas' => $l->tingkat_prioritas,
                'status'            => $l->status,
                'kategori'          => $l->kategori->nama ?? null,
                'foto_url'          => $l->foto_url ?? null,
                'dilaporkan'        => optional($l->created_at)->translatedFormat('d M Y, H:i'),
                'sla_batas'         => optional($l->sla_batas ?? null)->toIso8601String(),
                'url_detail'        => Route::has('instansi.laporan.show')
                                        ? route('instansi.laporan.show', $l->id)
                                        : null,
            ])
            ->values();

        return view('instansi.peta-kerusakan.index', compact('titikPeta'));
    }
}
