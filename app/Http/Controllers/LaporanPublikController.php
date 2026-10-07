<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\KategoriKerusakan;
use App\Models\Laporan;
use App\Services\AiDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LaporanPublikController extends Controller
{
    public function create()
    {
        $kategoris = KategoriKerusakan::where('status', 'Aktif')->get();
        $instansis = Instansi::where('status', 'Aktif')->get();

        return view('welcome', compact('kategoris', 'instansis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_kerusakan,id',
            'instansi_id' => 'required|exists:instansi,id',
            'judul'       => 'required|string|max:255',
            'deskripsi'   => 'required|string|max:5000',
            'foto_base64' => 'nullable|string|max:8000000',
            'lampiran'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'alamat'      => 'nullable|string|max:500',
        ]);

        if (! $request->filled('foto_base64') && ! $request->hasFile('lampiran')) {
            return back()
                ->withInput($request->except('foto_base64'))
                ->withErrors(['foto_base64' => 'Wajib mengisi foto dari kamera atau lampiran file.']);
        }

        $foto = null;
        $lampiran = null;

        if ($request->filled('foto_base64')) {
            $mentah = preg_replace('#^data:image/\w+;base64,#', '', $request->foto_base64);
            $biner = base64_decode(str_replace(' ', '+', $mentah), true);

            // Endpoint ini publik, jadi pastikan isinya benar-benar gambar
            if ($biner === false || @getimagesizefromstring($biner) === false) {
                return back()
                    ->withInput($request->except('foto_base64'))
                    ->withErrors(['foto_base64' => 'Foto tidak valid, silakan ambil ulang.']);
            }

            $foto = 'laporan/' . uniqid() . '.jpg';
            Storage::disk('public')->put($foto, $biner);
        }

        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran')->store('laporan/lampiran', 'public');
        }

        $laporan = Laporan::create([
            'user_id'           => null,
            'is_anonim'         => true,
            'kode_lacak'        => Laporan::buatKode(),
            'kategori_id'       => $request->kategori_id,
            'instansi_id'       => $request->instansi_id,
            'judul'             => $request->judul,
            'deskripsi'         => $request->deskripsi,
            'foto'              => $foto,
            'lampiran'          => $lampiran,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'alamat'            => $request->alamat,
            'tingkat_prioritas' => 'Sedang',
            'status'            => 'Menunggu',
        ]);

        $pathUntukAi = $foto;
        if (! $pathUntukAi && $lampiran
            && in_array(strtolower(pathinfo($lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])) {
            $pathUntukAi = $lampiran;
        }

        if ($pathUntukAi) {
            try {
                (new AiDetectionService())->detectAndSave($laporan->id, storage_path('app/public/' . $pathUntukAi));
            } catch (\Exception $e) {
                Log::error('AI Detection gagal untuk laporan #' . $laporan->id . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('laporan.sukses')->with('kode_lacak', $laporan->kode_lacak);
    }

    public function sukses()
    {
        $kode = session('kode_lacak');

        if (! $kode) {
            return redirect()->route('home');
        }

        return view('laporan-publik.sukses', compact('kode'));
    }

    public function lacak(Request $request)
    {
        $laporan = null;

        if ($request->filled('kode')) {
            $laporan = Laporan::with(['kategori', 'instansi'])
                ->where('kode_lacak', strtoupper(trim($request->kode)))
                ->first();
        }

        return view('laporan-publik.lacak', compact('laporan'));
    }
}