<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan seluruh notifikasi lintas semua user untuk keperluan
     * audit/monitoring Super Admin. Tidak di-scope ke user_id tertentu
     * (beda dari role lain), karena Super Admin berhak memantau semuanya.
     *
     * Super Admin HANYA memonitor — notifikasi harus tercipta otomatis
     * dari event sistem (verifikasi laporan, disposisi tugas, closing
     * report, dll), bukan diinput manual lewat form.
     */
    public function index(Request $request)
    {
        $query = Notifikasi::with(['laporan', 'user']);

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->get('filter') === 'belum_dibaca') {
            $query->where('dibaca', false);
        }

        $notifikasis = $query->latest()
            ->paginate(25)
            ->withQueryString();

        return view('SuperAdmin.notifikasi.index', compact('notifikasis'));
    }

    /**
     * Menampilkan detail 1 notifikasi (termasuk ke user siapa itu terkirim).
     */
    public function show(string $id)
    {
        $notifikasi = Notifikasi::with(['laporan', 'user'])->findOrFail($id);

        return view('SuperAdmin.notifikasi.show', compact('notifikasi'));
    }
}