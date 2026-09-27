<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenugasanController extends Controller
{
    /**
     * Menampilkan daftar tugas milik petugas yang sedang login.
     * Filter: aktif (ditugaskan/dalam_proses), prioritas (Kritis), selesai.
     */
    public function index(Request $request)
    {
        $query = Penugasan::with(['laporan', 'timSatgas'])
            ->where('petugas_id', Auth::id());

        if ($request->filled('filter')) {
            match ($request->filter) {
                'aktif' => $query->whereIn('status', [
                    'ditugaskan',
                    'dalam_proses',
                ]),

                'prioritas' => $query->whereHas('laporan', function ($q) {
                    $q->where('tingkat_prioritas', 'Kritis');
                }),

                'selesai' => $query->where('status', 'selesai'),

                default => null,
            };
        }

        $penugasan = $query->latest('tanggal_penugasan')->get();

        return view('Petugas.penugasan.index', compact('penugasan'));
    }

    /**
     * Menampilkan detail 1 tugas, termasuk sisa waktu SLA.
     */
    public function show(string $id)
    {
        $penugasan = Penugasan::with(['laporan', 'timSatgas'])->findOrFail($id);
        $this->pastikanMilikPetugas($penugasan);

        $batasSla = $penugasan->batasWaktuSla();
        $sisaMenit = $penugasan->sisaWaktuSla();
        $overdue = $penugasan->isOverdue();

        return view('Petugas.penugasan.show', compact(
            'penugasan',
            'batasSla',
            'sisaMenit',
            'overdue'
        ));
    }

    /**
     * Form closing report (upload foto hasil + catatan penyelesaian).
     * Hanya bisa diakses kalau tugas sedang "dalam_proses".
     */
    public function edit(string $id)
    {
        $penugasan = Penugasan::findOrFail($id);
        $this->pastikanMilikPetugas($penugasan);

        abort_unless(
            $penugasan->status === 'dalam_proses',
            403,
            'Closing report hanya bisa diisi saat tugas sedang dikerjakan.'
        );

        return view('Petugas.penugasan.closing-report', compact('penugasan'));
    }

    /**
     * Menangani 2 aksi dari sisi petugas:
     * 1. status = 'dalam_proses' -> petugas menekan "Mulai Tugas"
     * 2. status = 'selesai'      -> petugas mengirim closing report
     */
    public function update(Request $request, string $id)
    {
        $penugasan = Penugasan::findOrFail($id);
        $this->pastikanMilikPetugas($penugasan);

        return match ($request->status) {
            'dalam_proses' => $this->mulaiTugas($penugasan),
            'selesai' => $this->kirimClosingReport($request, $penugasan),
            default => back()->with('error', 'Status tidak valid.'),
        };
    }

    /**
     * Aksi: petugas menekan "Mulai Tugas".
     */
    private function mulaiTugas(Penugasan $penugasan)
    {
        if ($penugasan->status !== 'ditugaskan') {
            return back()->with('error', 'Tugas ini tidak dapat dimulai.');
        }

        $penugasan->update(['status' => 'dalam_proses']);

        return back()->with('success', 'Tugas berhasil dimulai.');
    }

    /**
     * Aksi: petugas mengirim closing report (foto hasil + catatan).
     * Status diubah jadi "selesai"; validasi akhir tetap dilakukan Instansi
     * lewat validasiClosingReport()/tolakClosingReport() sesuai flowchart
     * proposal (jika ditolak, status dikembalikan ke "dalam_proses").
     */
    private function kirimClosingReport(Request $request, Penugasan $penugasan)
    {
        if ($penugasan->status !== 'dalam_proses') {
            return back()->with('error', 'Tugas ini belum bisa dilaporkan selesai.');
        }

        $request->validate([
            'foto_hasil' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'catatan_penyelesaian' => 'required|string|max:1000',
        ]);

        $fotoPath = $request->file('foto_hasil')->store('closing-report', 'public');

        $penugasan->update([
            'foto_hasil' => $fotoPath,
            'catatan_penyelesaian' => $request->catatan_penyelesaian,
            'status' => 'selesai',
            'tanggal_selesai' => now(),
        ]);

        return redirect()
            ->route('petugas.penugasan.index')
            ->with('success', 'Closing report berhasil dikirim, menunggu validasi instansi.');
    }

    /**
     * Guard: pastikan tugas yang diakses memang milik petugas yang login.
     */
    private function pastikanMilikPetugas(Penugasan $penugasan): void
    {
        abort_unless(
            $penugasan->petugas_id === Auth::id(),
            403,
            'Anda tidak memiliki akses ke tugas ini.'
        );
    }
}