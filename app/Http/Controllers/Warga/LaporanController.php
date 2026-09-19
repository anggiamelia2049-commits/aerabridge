<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\KategoriKerusakan;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Laporan::with(['kategori', 'instansi'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('warga.laporan.index', compact('laporan'));
    }

    public function create()
    {
        $kategoris = KategoriKerusakan::where('status', 'Aktif')->get();
        $instansis = Instansi::where('status', 'Aktif')->get();

        return view('warga.laporan.create', compact('kategoris', 'instansis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_kerusakan,id',
            'instansi_id' => 'required|exists:instansi,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto_base64' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'alamat' => 'nullable|string',
        ]);

        if (!$request->filled('foto_base64') && !$request->hasFile('lampiran')) {
            return back()
                ->withInput()
                ->withErrors(['foto_base64' => 'Wajib mengisi foto dari kamera ATAU lampiran file.']);
        }

        $foto = null;
        $lampiran = null;

        if ($request->filled('foto_base64')) {
            $fotoBase64 = $request->foto_base64;
            $fotoBase64 = str_replace('data:image/jpeg;base64,', '', $fotoBase64);
            $fotoBase64 = str_replace(' ', '+', $fotoBase64);

            $namaFile = 'laporan/' . uniqid() . '.jpg';
            Storage::disk('public')->put($namaFile, base64_decode($fotoBase64));

            $foto = $namaFile;
        }

        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran')->store('laporan/lampiran', 'public');
        }

        Laporan::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'instansi_id' => $request->instansi_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
            'lampiran' => $lampiran,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'alamat' => $request->alamat,
            'tingkat_prioritas' => 'Sedang',
            'status' => 'Menunggu',
            'diverifikasi_oleh' => null,
        ]);

        return redirect()
            ->route('warga.laporan.index')
            ->with('success', 'Laporan berhasil dikirim dan sedang menunggu verifikasi.');
    }

    public function show(string $id)
    {
        $laporan = Laporan::with([
            'kategori',
            'instansi',
            'diverifikasiOleh'
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('warga.laporan.show', compact('laporan'));
    }

    public function edit(string $id)
    {
        abort(403, 'Warga tidak dapat mengedit laporan.');
    }

    public function update(Request $request, string $id)
    {
        abort(403, 'Warga tidak dapat mengubah laporan.');
    }

    public function destroy(string $id)
    {
        abort(403, 'Warga tidak dapat menghapus laporan.');
    }
}