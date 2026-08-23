<?php

namespace App\Http\Controllers;

use App\Models\SlaKonfigurasi;
use Illuminate\Http\Request;

class SlaKonfigurasiController extends Controller
{
        public function index()
    {
        $slaKonfigurasi = SlaKonfigurasi::latest()->get();

        return view('sla_konfigurasi.index', compact('slaKonfigurasi'));
    }

        public function create()
    {
        return view('sla_konfigurasi.create');
    }

        public function store(Request $request)
    {
        $request->validate([
            'prioritas' => 'required|in:kritis,sedang,rendah',
            'waktu_respon' => 'required|integer|min:0',
            'waktu_penyelesaian' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        SlaKonfigurasi::create([
            'prioritas' => $request->prioritas,
            'waktu_respon' => $request->waktu_respon,
            'waktu_penyelesaian' => $request->waktu_penyelesaian,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('sla-konfigurasi.index')
            ->with('success', 'Konfigurasi SLA berhasil ditambahkan.');
    }

        public function show(SlaKonfigurasi $slaKonfigurasi)
    {
        return view(
            'sla_konfigurasi.show',
            compact('slaKonfigurasi')
        );
    }

        public function edit(SlaKonfigurasi $slaKonfigurasi)
    {
        return view(
            'sla_konfigurasi.edit',
            compact('slaKonfigurasi')
        );
    }

        public function update(
        Request $request,
        SlaKonfigurasi $slaKonfigurasi
    ) {
        $request->validate([
            'prioritas' => 'required|in:kritis,sedang,rendah',
            'waktu_respon' => 'required|integer|min:0',
            'waktu_penyelesaian' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $slaKonfigurasi->update([
            'prioritas' => $request->prioritas,
            'waktu_respon' => $request->waktu_respon,
            'waktu_penyelesaian' => $request->waktu_penyelesaian,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('sla-konfigurasi.index')
            ->with('success', 'Konfigurasi SLA berhasil diperbarui.');
    }

    public function destroy(SlaKonfigurasi $slaKonfigurasi)
    {
        $slaKonfigurasi->delete();
        return redirect()
            ->route('sla-konfigurasi.index')
            ->with('success', 'Konfigurasi SLA berhasil dihapus.');
    }

}




