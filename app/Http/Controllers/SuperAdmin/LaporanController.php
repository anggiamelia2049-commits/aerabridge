<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\KategoriKerusakan;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /**
     * Pemetaan filter dari sidebar (?status=...) ke status asli di database.
     * key   = nilai ?status= di URL
     * value = [status di DB, judul halaman]
     */
    private const FILTER_STATUS = [
        'menunggu_verifikasi' => ['Menunggu',     'Verifikasi Laporan'],
        'disposisi'           => ['Diverifikasi', 'Disposisi Laporan'],
        'diproses'            => ['Diproses',     'Tindak Lanjut'],
        'selesai'             => ['Selesai',      'Laporan Selesai'],
        'ditolak'             => ['Ditolak',      'Laporan Ditolak'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Kalau ?status= kosong atau tidak dikenal, tampilkan semua laporan
        $filter = self::FILTER_STATUS[$request->query('status')] ?? null;

        $laporan = Laporan::with([
            'user',
            'kategori',
            'instansi',
            'diverifikasiOleh'
        ])
        ->when($filter, fn ($q) => $q->where('status', $filter[0]))
        ->latest()
        ->paginate(10)
        ->withQueryString();

        $judulHalaman = $filter[1] ?? 'Semua Laporan';

        return view('SuperAdmin.laporan.index', compact('laporan', 'judulHalaman'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = KategoriKerusakan::all();
        $instansis = Instansi::all();

        return view(
            'SuperAdmin.laporan.create',
            compact('kategoris', 'instansis')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_kerusakan,id',
            'instansi_id' => 'required|exists:instansi,id',
            'jenis_laporan' => 'nullable|string|max:50',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_kejadian' => 'nullable|date',
            'kecamatan' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'alamat' => 'nullable|string',
            'tingkat_prioritas' => 'nullable|in:Kritis,Sedang,Rendah',
            'status' => 'nullable|in:Menunggu,Diverifikasi,Diproses,Selesai,Ditolak',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')
                ->store('laporan', 'public');
        }

        Laporan::create([
            'user_id' => Auth::id(),
            'kode_lacak' => Laporan::buatKode(),
            'kategori_id' => $request->kategori_id,
            'instansi_id' => $request->instansi_id,
            'jenis_laporan' => $request->jenis_laporan,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'kecamatan' => $request->kecamatan,
            'foto' => $foto,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'alamat' => $request->alamat,
            'tingkat_prioritas' => $request->tingkat_prioritas ?? 'Sedang',
            'status' => $request->status ?? 'Menunggu',
            'diverifikasi_oleh' => null,
        ]);

        return redirect()
            ->route('super_admin.laporan.index')
            ->with('success', 'Laporan berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporan = Laporan::with([
            'user',
            'kategori',
            'instansi',
            'diverifikasiOleh'
        ])
        ->findOrFail($id);

        return view('SuperAdmin.laporan.show', compact('laporan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $laporan = Laporan::findOrFail($id);

        $kategoris = KategoriKerusakan::all();
        $instansis = Instansi::all();

        return view(
            'SuperAdmin.laporan.edit',
            compact('laporan', 'kategoris', 'instansis')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'kategori_id' => 'required|exists:kategori_kerusakan,id',
            'instansi_id' => 'required|exists:instansi,id',
            'jenis_laporan' => 'nullable|string|max:50',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_kejadian' => 'nullable|date',
            'kecamatan' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'alamat' => 'nullable|string',
            'tingkat_prioritas' => 'nullable|in:Kritis,Sedang,Rendah',
            'status' => 'nullable|in:Menunggu,Diverifikasi,Diproses,Selesai,Ditolak',
        ]);

        $foto = $laporan->foto;

        if ($request->hasFile('foto')) {
            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }
            $foto = $request->file('foto')->store('laporan', 'public');
        }

        // Catat pemverifikasi HANYA saat status benar-benar berubah,
        // supaya edit biasa tidak menimpa admin yang memverifikasi sebelumnya.
        $diverifikasiOleh = $laporan->diverifikasi_oleh;
        $statusBaru = $request->status ?? 'Menunggu';

        if ($statusBaru !== $laporan->status) {
            if (in_array($statusBaru, ['Diverifikasi', 'Diproses', 'Selesai', 'Ditolak'])) {
                $diverifikasiOleh = Auth::id();
            } elseif ($statusBaru === 'Menunggu') {
                $diverifikasiOleh = null;
            }
        }

        // kode_lacak sengaja tidak diubah saat edit (kode unik milik pelapor)
        $laporan->update([
            'kategori_id' => $request->kategori_id,
            'instansi_id' => $request->instansi_id,
            'jenis_laporan' => $request->jenis_laporan,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'kecamatan' => $request->kecamatan,
            'foto' => $foto,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'alamat' => $request->alamat,
            'tingkat_prioritas' => $request->tingkat_prioritas ?? 'Sedang',
            'status' => $statusBaru,
            'diverifikasi_oleh' => $diverifikasiOleh,
        ]);

        return redirect()
            ->route('super_admin.laporan.index')
            ->with('success', 'Laporan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }

        $laporan->delete();

        return redirect()
            ->route('super_admin.laporan.index')
            ->with('success', 'Laporan berhasil dihapus');
    }

    /**
     * Verify laporan.
     */
    public function verify(Request $request, string $id)
    {
        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Diverifikasi,Diproses,Selesai,Ditolak',
        ]);

        $laporan->update([
            'status' => $request->status,
            'diverifikasi_oleh' => Auth::id(),
        ]);

        // back() supaya tetap di tab/filter yang sedang dibuka di sidebar
        return back()->with('success', 'Status laporan berhasil diperbarui');
    }
}