<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\TimSatgas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Smart Dashboard Pemda.
     *
     * Menampilkan peta heatmap laporan, ringkasan statistik,
     * dan indikator overdue SLA untuk penugasan yang sedang berjalan.
     */
    public function index(Request $request)
    {
        $instansiId = $this->instansiId();

        // ==== Filter peta heatmap ====
        $prioritas = $request->get('prioritas');   // Krisis | Sedang | Rendah
        $status = $request->get('status');         // Menunggu | Diverifikasi | ...

        $peta = Laporan::with(['kategori', 'user'])
            ->where('instansi_id', $instansiId)
            ->whereNotIn('status', ['Ditolak']);

        if (in_array($prioritas, ['Krisis', 'Sedang', 'Rendah'], true)) {
            $peta->where('tingkat_prioritas', $prioritas);
        }

        if (in_array($status, ['Menunggu', 'Diverifikasi', 'Diproses', 'Selesai'], true)) {
            $peta->where('status', $status);
        }

        $titikPeta = $peta->latest()->get();

        // ==== Ringkasan statistik laporan ====
        $statistik = [
            'menunggu' => $this->hitungLaporan($instansiId, 'Menunggu'),
            'diverifikasi' => $this->hitungLaporan($instansiId, 'Diverifikasi'),
            'diproses' => $this->hitungLaporan($instansiId, 'Diproses'),
            'selesai' => $this->hitungLaporan($instansiId, 'Selesai'),
            'ditolak' => $this->hitungLaporan($instansiId, 'Ditolak'),
            'krisis' => Laporan::where('instansi_id', $instansiId)
                ->where('tingkat_prioritas', 'Krisis')
                ->whereIn('status', ['Menunggu', 'Diverifikasi', 'Diproses'])
                ->count(),
        ];

        // ==== Antrian kerja instansi ====

        // Laporan baru yang menunggu verifikasi, prioritas kritis didahulukan
        $perluVerifikasi = Laporan::with(['kategori', 'user'])
            ->where('instansi_id', $instansiId)
            ->where('status', 'Menunggu')
            ->orderByRaw("FIELD(tingkat_prioritas, 'Krisis', 'Sedang', 'Rendah')")
            ->latest()
            ->limit(10)
            ->get();

        // Laporan sudah diverifikasi tapi belum didisposisikan ke tim satgas
        $belumDidisposisi = Laporan::with('kategori')
            ->where('instansi_id', $instansiId)
            ->where('status', 'Diverifikasi')
            ->whereDoesntHave('penugasan', function ($q) {
                $q->whereIn('status', ['ditugaskan', 'dalam_proses', 'selesai']);
            })
            ->latest()
            ->get();

        // Closing report petugas yang menunggu validasi instansi
        $menungguValidasi = Penugasan::with(['laporan', 'petugas', 'timSatgas'])
            ->where('status', 'selesai')
            ->whereHas('laporan', function ($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId)
                    ->where('status', 'Diproses');
            })
            ->latest('tanggal_selesai')
            ->get();

        // ==== Monitoring progres lapangan & indikator overdue ====
        $penugasanAktif = Penugasan::with(['laporan', 'petugas', 'timSatgas'])
            ->aktif()
            ->whereHas('laporan', function ($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            })
            ->latest('tanggal_penugasan')
            ->get();

        $jumlahOverdue = $penugasanAktif
            ->filter(fn ($item) => $item->isOverdue())
            ->count();

        $timSatgas = TimSatgas::where('instansi_id', $instansiId)
            ->where('status', 'aktif')
            ->withCount(['penugasan' => function ($q) {
                $q->whereIn('status', ['ditugaskan', 'dalam_proses']);
            }])
            ->get();

        return view('instansi.dashboard.index', compact(
            'titikPeta',
            'statistik',
            'perluVerifikasi',
            'belumDidisposisi',
            'menungguValidasi',
            'penugasanAktif',
            'jumlahOverdue',
            'timSatgas',
            'prioritas',
            'status'
        ));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        abort(404);
    }

    public function update(Request $request, string $id)
    {
        abort(404);
    }

    public function destroy(string $id)
    {
        abort(404);
    }

    private function hitungLaporan(int $instansiId, string $status): int
    {
        return Laporan::where('instansi_id', $instansiId)
            ->where('status', $status)
            ->count();
    }

    /**
     * Instansi milik user yang sedang login.
     */
    private function instansiId(): int
    {
        $instansiId = Auth::user()->instansi_id;

        abort_unless(
            $instansiId,
            403,
            'Akun Anda belum terhubung dengan instansi mana pun.'
        );

        return $instansiId;
    }
}
