<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SlaKonfigurasi;
use Illuminate\Http\Request;

class TenggatPerbaikanController extends Controller
{
    public function index()
    {
        $tenggatPerbaikan = SlaKonfigurasi::orderByRaw("
            FIELD(prioritas, 'kritis', 'sedang', 'rendah')
        ")->get();

        return view(
            'SuperAdmin.tenggatperbaikan.index',
            compact('tenggatPerbaikan')
        );
    }

    public function edit(string $id)
    {
        $tenggat = SlaKonfigurasi::findOrFail($id);

        return view(
            'SuperAdmin.tenggatperbaikan.edit',
            compact('tenggat')
        );
    }

    public function update(Request $request, string $id)
    {
        $tenggat = SlaKonfigurasi::findOrFail($id);

        $validated = $request->validate([
            'waktu_penyelesaian' => [
                'required',
                'integer',
                'min:1',
            ],
        ], [
            'waktu_penyelesaian.required' =>
                'Tenggat penyelesaian wajib diisi.',
            'waktu_penyelesaian.integer' =>
                'Tenggat harus berupa angka.',
            'waktu_penyelesaian.min' =>
                'Tenggat minimal 1 jam.',
        ]);

        $tenggat->update($validated);

        return redirect()
            ->route('super_admin.tenggat-perbaikan.index')
            ->with('success', 'Tenggat perbaikan berhasil diperbarui.');
    }
}