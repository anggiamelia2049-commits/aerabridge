<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PenugasanController extends Controller
{
    /**
     * Daftar tugas milik petugas yang login.
     * Filter tab: semua, aktif, prioritas (aktif + Kritis), selesai.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'semua');

        if (! in_array($filter, ['semua', 'aktif', 'prioritas', 'selesai'])) {
            $filter = 'semua';
        }

        $query = Penugasan::with('laporan')
            ->where('petugas_id', Auth::id());

        if ($filter === 'aktif') {
            $query->aktif();
        } elseif ($filter === 'prioritas') {
            $query->aktif()->whereHas('laporan', function ($q) {
                $q->where('tingkat_prioritas', 'Kritis');
            });
        } elseif ($filter === 'selesai') {
            $query->where('status', 'selesai');
        }

        $penugasan = $query->latest('tanggal_penugasan')->get();

        return view('petugas.penugasan.index', compact('penugasan', 'filter'));
    }

    /**
     * Detail satu tugas, termasuk batas dan sisa waktu SLA.
     */
    public function show(string $id)
    {
        $penugasan = $this->cariTugas($id, ['laporan.kategori', 'laporan.instansi', 'timSatgas']);

        $batasSla = $penugasan->batasWaktuSla();
        $sisaMenit = $penugasan->sisaWaktuSla();
        $overdue = $penugasan->isOverdue();

        return view('petugas.penugasan.show', compact(
            'penugasan',
            'batasSla',
            'sisaMenit',
            'overdue'
        ));
    }

    /**
     * Form closing report. Hanya bisa dibuka saat tugas "dalam_proses".
     */
    public function edit(string $id)
    {
        $penugasan = $this->cariTugas($id);

        if ($penugasan->status !== 'dalam_proses') {
            return redirect()
                ->route('petugas.penugasan.show', $penugasan->id)
                ->with('error', 'Closing report hanya bisa diisi saat tugas sedang dikerjakan.');
        }

        return view('petugas.penugasan.closing-report', compact('penugasan'));
    }

    /**
     * Dua aksi lewat satu route:
     * - status = dalam_proses : petugas menekan "Mulai Tugas"
     * - status = selesai      : petugas mengirim closing report
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:dalam_proses,selesai',
        ]);

        $penugasan = $this->cariTugas($id);

        if ($request->status === 'dalam_proses') {
            return $this->mulaiTugas($penugasan);
        }

        return $this->kirimClosingReport($request, $penugasan);
    }

    /**
     * Mulai tugas: penugasan jadi dalam_proses, laporan jadi Diproses.
     */
    private function mulaiTugas(Penugasan $penugasan)
    {
        if ($penugasan->status !== 'ditugaskan') {
            return back()->with('error', 'Tugas ini tidak bisa dimulai.');
        }

        DB::transaction(function () use ($penugasan) {
            $penugasan->update(['status' => 'dalam_proses']);
            $penugasan->laporan->update(['status' => 'Diproses']);
        });

        return redirect()
            ->route('petugas.penugasan.show', $penugasan->id)
            ->with('success', 'Tugas berhasil dimulai.');
    }

    /**
     * Kirim closing report: simpan foto + catatan, penugasan dan laporan
     * jadi selesai, lalu beri tahu user instansi terkait.
     */
    private function kirimClosingReport(Request $request, Penugasan $penugasan)
    {
        if ($penugasan->status !== 'dalam_proses') {
            return back()->with('error', 'Tugas ini belum bisa dilaporkan selesai.');
        }

        $request->validate([
            'foto_hasil' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'catatan_penyelesaian' => 'required|string|min:10|max:1000',
        ], [
            'foto_hasil.required' => 'Foto hasil perbaikan wajib diunggah.',
            'foto_hasil.image' => 'File harus berupa gambar.',
            'foto_hasil.mimes' => 'Format foto harus JPG atau PNG.',
            'foto_hasil.max' => 'Ukuran foto maksimal 5 MB.',
            'catatan_penyelesaian.required' => 'Catatan penyelesaian wajib diisi.',
            'catatan_penyelesaian.min' => 'Catatan minimal 10 karakter.',
            'catatan_penyelesaian.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        $fotoPath = $request->file('foto_hasil')->store('closing-report', 'public');

        try {
            DB::transaction(function () use ($request, $penugasan, $fotoPath) {
                $laporan = $penugasan->laporan;

                $penugasan->update([
                    'foto_hasil' => $fotoPath,
                    'catatan_penyelesaian' => $request->catatan_penyelesaian,
                    'status' => 'selesai',
                    'tanggal_selesai' => now(),
                ]);

                $laporan->update(['status' => 'Selesai']);

                // Beri tahu semua user instansi yang menangani laporan ini
                $idUserInstansi = User::where('role', 'instansi')
                    ->where('instansi_id', $laporan->instansi_id)
                    ->pluck('id');

                foreach ($idUserInstansi as $idUser) {
                    Notifikasi::create([
                        'user_id' => $idUser,
                        'laporan_id' => $laporan->id,
                        'judul' => 'Closing report menunggu validasi',
                        'isi' => 'Petugas telah mengirim closing report untuk laporan "' . $laporan->judul . '". Mohon segera divalidasi.',
                        'tipe' => 'informasi',
                        'dibaca' => false,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            // Kalau database gagal, hapus foto yang sudah terlanjur tersimpan
            Storage::disk('public')->delete($fotoPath);
            report($e);

            return back()->with('error', 'Closing report gagal dikirim. Silakan coba lagi.');
        }

        return redirect()
            ->route('petugas.penugasan.index')
            ->with('success', 'Closing report berhasil dikirim, menunggu validasi instansi.');
    }

    /**
     * Cari tugas milik petugas yang login.
     * Tugas milik petugas lain tidak ditemukan (404).
     */
    private function cariTugas(string $id, array $relasi = ['laporan']): Penugasan
    {
        return Penugasan::with($relasi)
            ->where('petugas_id', Auth::id())
            ->findOrFail($id);
    }
}