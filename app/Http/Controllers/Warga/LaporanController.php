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
    /**
     * Menampilkan semua laporan milik warga yang sedang login.
     */
    public function index()
    {
        $laporan = Laporan::with(['kategori', 'instansi'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('warga.laporan.index', compact('laporan'));
    }

    /**
     * Menampilkan form untuk membuat laporan baru.
     */
    public function create()
    {
        $kategoris = KategoriKerusakan::where('status', 'Aktif')->get();

        $instansis = Instansi::where('status', 'Aktif')->get();

        return view('warga.laporan.create', compact(
            'kategoris',
            'instansis'
        ));
    }

    /**
     * Menyimpan laporan baru dari warga.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_kerusakan,id',

            'instansi_id' => 'required|exists:instansi,id',

            'judul' => 'required|string|max:255',

            'deskripsi' => 'required|string',

            'foto' => 'required|image|mimes:jpg,jpeg,png|max:5120',

            'latitude' => 'required|numeric|between:-90,90',

            'longitude' => 'required|numeric|between:-180,180',

            'alamat' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload foto laporan
        |--------------------------------------------------------------------------
        */

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')
                ->store('laporan', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan laporan
        |--------------------------------------------------------------------------
        */

        Laporan::create([
            'user_id' => Auth::id(),

            'kategori_id' => $request->kategori_id,

            'instansi_id' => $request->instansi_id,

            'judul' => $request->judul,

            'deskripsi' => $request->deskripsi,

            'foto' => $foto,

            'latitude' => $request->latitude,

            'longitude' => $request->longitude,

            'alamat' => $request->alamat,

            // Laporan baru selalu masuk sebagai Menunggu
            'tingkat_prioritas' => 'Sedang',

            'status' => 'Menunggu',

            // Belum diverifikasi
            'diverifikasi_oleh' => null,
        ]);

        return redirect()
            ->route('warga.laporan.index')
            ->with('success', 'Laporan berhasil dikirim dan sedang menunggu verifikasi.');
    }

    /**
     * Menampilkan detail laporan milik warga.
     */
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

    /**
     * Warga tidak perlu mengedit laporan.
     */
    public function edit(string $id)
    {
        abort(403, 'Warga tidak dapat mengedit laporan.');
    }

    /**
     * Warga tidak perlu mengupdate laporan.
     */
    public function update(Request $request, string $id)
    {
        abort(403, 'Warga tidak dapat mengubah laporan.');
    }

    /**
     * Warga tidak dapat menghapus laporan.
     */
    public function destroy(string $id)
    {
        abort(403, 'Warga tidak dapat menghapus laporan.');
    }
}