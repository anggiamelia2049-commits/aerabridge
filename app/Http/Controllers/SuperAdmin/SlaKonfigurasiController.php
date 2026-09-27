<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SlaKonfigurasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SlaKonfigurasiController extends Controller
{
    public function index()
    {
        $slaKonfigurasi = SlaKonfigurasi::latest()->get();

        return view('SuperAdmin.slakonfigurasi.index', compact('slaKonfigurasi'));
    }

    public function create()
    {
        return view('SuperAdmin.slakonfigurasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prioritas' => [
                'required',
                Rule::in(['kritis', 'sedang', 'rendah']),
                Rule::unique('sla_konfigurasi', 'prioritas')
                    ->where(fn ($query) => $query->where('status', 'aktif')),
            ],
            'waktu_respon' => 'required|integer|min:0',
            'waktu_penyelesaian' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        SlaKonfigurasi::create($validated);

        return redirect()
            ->route('super_admin.sla-konfigurasi.index')
            ->with('success', 'Konfigurasi SLA berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $slaKonfigurasi = SlaKonfigurasi::findOrFail($id);

        return view('SuperAdmin.slakonfigurasi.show', compact('slaKonfigurasi'));
    }

    public function edit(string $id)
    {
        $slaKonfigurasi = SlaKonfigurasi::findOrFail($id);

        return view('SuperAdmin.slakonfigurasi.edit', compact('slaKonfigurasi'));
    }

    public function update(Request $request, string $id)
    {
        $slaKonfigurasi = SlaKonfigurasi::findOrFail($id);

        $validated = $request->validate([
            'prioritas' => [
                'required',
                Rule::in(['kritis', 'sedang', 'rendah']),
                Rule::unique('sla_konfigurasi', 'prioritas')
                    ->ignore($slaKonfigurasi->id)
                    ->where(fn ($query) => $query->where('status', 'aktif')),
            ],
            'waktu_respon' => 'required|integer|min:0',
            'waktu_penyelesaian' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $slaKonfigurasi->update($validated);

        return redirect()
            ->route('super_admin.sla-konfigurasi.index')
            ->with('success', 'Konfigurasi SLA berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $slaKonfigurasi = SlaKonfigurasi::findOrFail($id);
        $slaKonfigurasi->delete();

        return redirect()
            ->route('super_admin.sla-konfigurasi.index')
            ->with('success', 'Konfigurasi SLA berhasil dihapus.');
    }
}