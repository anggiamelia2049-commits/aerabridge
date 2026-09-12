<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenugasanController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar tugas milik petugas yang sedang login,
     * dengan filter Aktif / Prioritas Tinggi / Selesai (sesuai mock-up dashboard petugas).
     */
    public function index(Request $request)
    {
        $query = Penugasan::with(['laporan', 'timSatgas'])
            ->where('petugas_id', Auth::id());

        if ($request->filled('filter')) {
            match ($request->filter) {
                'aktif'     => $query->whereIn('status', ['ditugaskan', 'dalam_proses']),
                'prioritas' => $query->whereHas('laporan', fn ($q) => $q->where('prioritas', 'kritis')),
                'selesai'   => $query->where('status', 'selesai'),
                default     => null,
            };
        }

        $penugasan = $query->latest()->get();

        $summary = [
            'tugas_aktif'      => Penugasan::where('petugas_id', Auth::id())
                ->whereIn('status', ['ditugaskan', 'dalam_proses'])->count(),
            'prioritas_tinggi' => Penugasan::where('petugas_id', Auth::id())
                ->whereIn('status', ['ditugaskan', 'dalam_proses'])
                ->whereHas('laporan', fn ($q) => $q->where('prioritas', 'kritis'))->count(),
            'selesai'          => Penugasan::where('petugas_id', Auth::id())
                ->where('status', 'selesai')->count(),
            'tugas_harian'     => Penugasan::where('petugas_id', Auth::id())
                ->whereDate('tanggal_penugasan', today())->count(),
        ];

        return view('Petugas.penugasan.index', compact('penugasan', 'summary'));
    }

    /**
     * Show the form for creating a new resource.
     * Tidak digunakan: penugasan dibuat oleh Instansi/Super Admin, bukan oleh Petugas.
     */
    public function create()
    {
        abort(403, 'Petugas tidak memiliki akses untuk membuat penugasan baru.');
    }

    /**
     * Store a newly created resource in storage.
     * Tidak digunakan: lihat catatan pada method create().
     */
    public function store(Request $request)
    {
        abort(403, 'Petugas tidak memiliki akses untuk membuat penugasan baru.');
    }

    /**
     * Display the specified resource.
     * Detail tugas: jenis kerusakan, prioritas, foto laporan,
     * catatan teknis, dan sisa waktu SLA.
     */
    public function show(string $id)
    {
        $penugasan = Penugasan::with(['laporan', 'timSatgas'])->findOrFail($id);

        $this->authorizeTugas($penugasan);

        $slaDeadline = $this->hitungBatasSla($penugasan);

        return view('Petugas.penugasan.show', compact('penugasan', 'slaDeadline'));
    }

    /**
     * Show the form for editing the specified resource.
     * Diarahkan ke form closing report (upload foto hasil + catatan penyelesaian),
     * bukan form edit data penugasan.
     */
    public function edit(string $id)
    {
        $penugasan = Penugasan::with(['laporan', 'timSatgas'])->findOrFail($id);

        $this->authorizeTugas($penugasan);

        if ($penugasan->status === 'selesai') {
            return redirect()
                ->route('petugas.penugasan.show', $penugasan->id)
                ->with('info', 'Tugas ini sudah selesai.');
        }

        return view('Petugas.penugasan.closing-report', compact('penugasan'));
    }

    /**
     * Update the specified resource in storage.
     * Menangani dua aksi milik petugas:
     * 1) mulai_proses  -> status: ditugaskan -> dalam_proses
     * 2) closing_report -> upload foto hasil & catatan, status -> selesai
     */
    public function update(Request $request, string $id)
    {
        $penugasan = Penugasan::with('laporan')->findOrFail($id);

        $this->authorizeTugas($penugasan);

        $request->validate([
            'aksi' => 'required|in:mulai_proses,closing_report',
        ]);

        if ($request->aksi === 'mulai_proses') {
            if ($penugasan->status !== 'ditugaskan') {
                return back()->with('error', 'Tugas ini tidak dapat dimulai dari status saat ini.');
            }

            $penugasan->update(['status' => 'dalam_proses']);

            return back()->with('success', 'Tugas dimulai. Semoga perbaikan berjalan lancar.');
        }

        // aksi === 'closing_report'
        if ($penugasan->status === 'selesai') {
            return back()->with('error', 'Tugas ini sudah ditandai selesai sebelumnya.');
        }

        $request->validate([
            'foto_hasil'           => 'required|image|max:5120',
            'catatan_penyelesaian' => 'nullable|string|max:1000',
        ]);

        $path = $request->file('foto_hasil')->store('closing-report', 'public');

        $penugasan->update([
            'status'               => 'selesai',
            'foto_hasil'           => $path,
            'catatan_penyelesaian' => $request->catatan_penyelesaian,
            'tanggal_selesai'      => now(),
        ]);

        $penugasan->laporan()->update(['status' => 'selesai']);

        return redirect()
            ->route('petugas.penugasan.index')
            ->with('success', 'Closing report berhasil dikirim. Tugas ditandai selesai.');
    }

    /**
     * Remove the specified resource from storage.
     * Tidak digunakan: petugas tidak berwenang menghapus data penugasan.
     */
    public function destroy(string $id)
    {
        abort(403, 'Petugas tidak memiliki akses untuk menghapus penugasan.');
    }

    /**
     * Memastikan tugas yang diakses benar-benar milik petugas yang login.
     */
    private function authorizeTugas(Penugasan $penugasan): void
    {
        abort_unless($penugasan->petugas_id === Auth::id(), 403, 'Anda tidak memiliki akses ke tugas ini.');
    }

    /**
     * Hitung batas waktu SLA berdasarkan prioritas laporan
     * (Kritis: 12 jam, Sedang: 3 hari, Rendah: 7 hari) sejak tanggal penugasan.
     */
    private function hitungBatasSla(Penugasan $penugasan)
    {
        $jamSla = match ($penugasan->laporan->prioritas ?? 'rendah') {
            'kritis' => 12,
            'sedang' => 24 * 3,
            default  => 24 * 7,
        };

        return $penugasan->tanggal_penugasan
            ? \Carbon\Carbon::parse($penugasan->tanggal_penugasan)->addHours($jamSla)
            : null;
    }
}