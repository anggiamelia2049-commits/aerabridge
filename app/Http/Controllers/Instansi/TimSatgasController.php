<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\TimSatgas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimSatgasController extends Controller
{
    public function index()
    {
        $timSatgas = TimSatgas::where('instansi_id', $this->instansiId())
            ->latest()
            ->paginate(10);

        return view('instansi.tim-satgas.index', compact('timSatgas'));
    }

    public function create()
    {
        return view('instansi.tim-satgas.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['instansi_id'] = $this->instansiId();

        TimSatgas::create($data);

        return redirect()->route('instansi.tim-satgas.index')
            ->with('success', 'Tim satgas berhasil ditambahkan.');
    }

    // Nama variabel $timSatgas cocok dengan parameter route {tim_satgas} (Laravel mengubah ke snake_case)
    public function show(TimSatgas $timSatgas)
    {
        $this->pastikanMilikInstansi($timSatgas);

        return view('instansi.tim-satgas.show', compact('timSatgas'));
    }

    public function edit(TimSatgas $timSatgas)
    {
        $this->pastikanMilikInstansi($timSatgas);

        return view('instansi.tim-satgas.edit', compact('timSatgas'));
    }

    public function update(Request $request, TimSatgas $timSatgas)
    {
        $this->pastikanMilikInstansi($timSatgas);

        $timSatgas->update($this->validated($request));

        return redirect()->route('instansi.tim-satgas.index')
            ->with('success', 'Data tim satgas berhasil diperbarui.');
    }

    public function destroy(TimSatgas $timSatgas)
    {
        $this->pastikanMilikInstansi($timSatgas);

        $timSatgas->delete();

        return redirect()->route('instansi.tim-satgas.index')
            ->with('success', 'Tim satgas berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_tim'       => 'required|string|max:255',
            'ketua'          => 'required|string|max:255',
            'jumlah_anggota' => 'required|integer|min:0',
            'kontak'         => 'required|string|max:50',
            'status'         => 'required|in:aktif,nonaktif',
        ]);
    }

    // Asumsi: tabel users punya kolom instansi_id. Sesuaikan jika berbeda.
    private function instansiId()
    {
        return Auth::user()->instansi_id;
    }

    private function pastikanMilikInstansi(TimSatgas $tim): void
    {
        abort_if($tim->instansi_id !== $this->instansiId(), 403);
    }
}
