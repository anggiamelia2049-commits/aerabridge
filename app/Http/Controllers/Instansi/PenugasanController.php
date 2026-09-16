<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Notifikasi;
use App\Models\Penugasan;
use App\Models\TimSatgas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenugasanController extends Controller
{
    /**
     * Monitor progres lapangan & indikator overdue SLA.
     */
    public function index(Request $request)
    {
        $instansiId = $this->instansiId();

        $query = Penugasan::with(['laporan.kategori', 'timSatgas', 'petugas'])
            ->whereHas('laporan', function ($q) use ($instansiId) {
                $q->where('instansi_id', $instansiId);
            });

        $filter = $request->get('filter', 'aktif');

        match ($filter) {
            'validasi' => $query->where('status', 'selesai')
                ->whereHas('laporan', function ($q) {
                    $q->where('status', 'Diproses');
                }),

            'selesai' => $query->where('status', 'selesai')
                ->whereHas('laporan', function ($q) {
                    $q->where('status', 'Selesai');
                }),

            'dibatalkan' => $query->where('status', 'dibatalkan'),

            default => $query->aktif(),
        };

        $penugasan = $query->latest('tanggal_penugasan')->get();

        // Indikator overdue dihitung dari sla_konfigurasi per tingkat prioritas
        if ($request->boolean('overdue')) {
            $penugasan = $penugasan->filter(
                fn ($item) => $item->isOverdue()
            );
        }

        return view('instansi.Penugasan.index', compact('penugasan', 'filter'));
    }

    /**
     * Form disposisi tugas: pilih tim satgas & petugas yang tersedia.
     */
    public function create(Request $request)
    {
        $instansiId = $this->instansiId();

        // Hanya laporan yang sudah dinyatakan valid yang boleh didisposisikan
        $laporan = Laporan::with('kategori')
            ->where('instansi_id', $instansiId)
            ->where('status', 'Diverifikasi')
            ->whereDoesntHave('penugasan', function ($q) {
                $q->whereIn('status', ['ditugaskan', 'dalam_proses', 'selesai']);
            })
            ->orderByRaw("FIELD(tingkat_prioritas, 'Krisis', 'Sedang', 'Rendah')")
            ->get();

        $laporanTerpilih = $request->get('laporan_id');

        $timSatgas = TimSatgas::where('instansi_id', $instansiId)
            ->where('status', 'aktif')
            ->withCount(['penugasan' => function ($q) {
                $q->whereIn('status', ['ditugaskan', 'dalam_proses']);
            }])
            ->get();

        $petugas = User::where('role', 'petugas')
            ->where('instansi_id', $instansiId)
            ->where('status', 'Aktif')
            ->get();

        return view('instansi.Penugasan.create', compact(
            'laporan',
            'laporanTerpilih',
            'timSatgas',
            'petugas'
        ));
    }

    /**
     * Klik disposisi tugas ke petugas.
     * Sistem otomatis mengaktifkan hitung mundur SLA dari tanggal_penugasan.
     */
    public function store(Request $request)
    {
        $instansiId = $this->instansiId();

        $request->validate([
            'laporan_id' => 'required|exists:laporan,id',
            'tim_satgas_id' => 'required|exists:tim_satgas,id',
            'petugas_id' => 'required|exists:users,id',
            'catatan' => 'nullable|string|max:500',
        ]);

        $laporan = Laporan::findOrFail($request->laporan_id);

        abort_unless(
            $laporan->instansi_id === $instansiId,
            403,
            'Anda tidak memiliki akses ke laporan ini.'
        );

        if ($laporan->status !== 'Diverifikasi') {
            return back()->with(
                'error',
                'Hanya laporan yang sudah diverifikasi yang dapat didisposisikan.'
            );
        }

        $this->pastikanTimMilikInstansi($request->tim_satgas_id, $instansiId);
        $this->pastikanPetugasMilikInstansi($request->petugas_id, $instansiId);

        $penugasan = DB::transaction(function () use ($request, $laporan) {

            $penugasan = Penugasan::create([
                'laporan_id' => $laporan->id,
                'tim_satgas_id' => $request->tim_satgas_id,
                'petugas_id' => $request->petugas_id,
                'status' => 'ditugaskan',
                // Titik mulai hitung mundur SLA otomatis
                'tanggal_penugasan' => now(),
                'catatan' => $request->catatan,
            ]);

            $laporan->update(['status' => 'Diproses']);

            return $penugasan;
        });

        $batasSla = $penugasan->batasWaktuSla();

        $this->kirimNotifikasi(
            $request->petugas_id,
            $laporan->id,
            'Tugas Baru Ditugaskan',
            "Anda ditugaskan menangani laporan \"{$laporan->judul}\"."
                . ($batasSla
                    ? ' Batas waktu penyelesaian: ' . $batasSla->format('d/m/Y H:i') . '.'
                    : ''),
            'informasi'
        );

        $this->kirimNotifikasi(
            $laporan->user_id,
            $laporan->id,
            'Laporan Sedang Diproses',
            "Laporan \"{$laporan->judul}\" telah didisposisikan ke petugas lapangan.",
            'informasi'
        );

        return redirect()
            ->route('instansi.penugasan.index')
            ->with('success', 'Tugas berhasil didisposisikan ke petugas.');
    }

    /**
     * Detail progres lapangan & closing report petugas.
     */
    public function show(string $id)
    {
        $penugasan = Penugasan::with([
            'laporan.user',
            'laporan.kategori',
            'timSatgas',
            'petugas',
        ])->findOrFail($id);

        $this->authorizePenugasan($penugasan);

        $batasSla = $penugasan->batasWaktuSla();
        $sisaMenit = $penugasan->sisaWaktuSla();
        $overdue = $penugasan->isOverdue();

        return view('instansi.Penugasan.show', compact(
            'penugasan',
            'batasSla',
            'sisaMenit',
            'overdue'
        ));
    }

    /**
     * Form re-disposisi: ganti tim satgas / petugas pada tugas berjalan.
     */
    public function edit(string $id)
    {
        $instansiId = $this->instansiId();

        $penugasan = Penugasan::with('laporan')->findOrFail($id);

        $this->authorizePenugasan($penugasan);

        $timSatgas = TimSatgas::where('instansi_id', $instansiId)
            ->where('status', 'aktif')
            ->get();

        $petugas = User::where('role', 'petugas')
            ->where('instansi_id', $instansiId)
            ->where('status', 'Aktif')
            ->get();

        return view('instansi.Penugasan.edit', compact(
            'penugasan',
            'timSatgas',
            'petugas'
        ));
    }

    /**
     * Dua aksi utama instansi pada sebuah penugasan:
     *
     * aksi = validasi : closing report petugas valid  -> laporan SELESAI
     * aksi = revisi   : closing report tidak valid    -> tugas dikembalikan
     * aksi = redisposisi : ganti tim satgas / petugas
     */
    public function update(Request $request, string $id)
    {
        $penugasan = Penugasan::with('laporan')->findOrFail($id);

        $this->authorizePenugasan($penugasan);

        $request->validate([
            'aksi' => 'required|in:validasi,revisi,redisposisi',
            'catatan' => 'required_if:aksi,revisi|nullable|string|max:500',
            'tim_satgas_id' => 'required_if:aksi,redisposisi|nullable|exists:tim_satgas,id',
            'petugas_id' => 'required_if:aksi,redisposisi|nullable|exists:users,id',
        ]);

        return match ($request->aksi) {
            'validasi' => $this->validasiClosingReport($penugasan),
            'revisi' => $this->tolakClosingReport($penugasan, $request->catatan),
            'redisposisi' => $this->redisposisi($request, $penugasan),
        };
    }

    /**
     * Batalkan penugasan, laporan dikembalikan ke antrian disposisi.
     */
    public function destroy(string $id)
    {
        $penugasan = Penugasan::with('laporan')->findOrFail($id);

        $this->authorizePenugasan($penugasan);

        if ($penugasan->status === 'selesai') {
            return back()->with(
                'error',
                'Penugasan yang sudah selesai tidak dapat dibatalkan.'
            );
        }

        DB::transaction(function () use ($penugasan) {
            $penugasan->update(['status' => 'dibatalkan']);

            $penugasan->laporan->update(['status' => 'Diverifikasi']);
        });

        $this->kirimNotifikasi(
            $penugasan->petugas_id,
            $penugasan->laporan_id,
            'Penugasan Dibatalkan',
            "Penugasan untuk laporan \"{$penugasan->laporan->judul}\" telah dibatalkan oleh instansi.",
            'warning'
        );

        return redirect()
            ->route('instansi.penugasan.index')
            ->with('success', 'Penugasan berhasil dibatalkan.');
    }

    // ===================== AKSI INTERNAL =====================

    /**
     * Closing report valid -> ubah status laporan jadi SELESAI.
     */
    private function validasiClosingReport(Penugasan $penugasan)
    {
        if ($penugasan->status !== 'selesai') {
            return back()->with(
                'error',
                'Petugas belum mengirimkan closing report untuk tugas ini.'
            );
        }

        if ($penugasan->laporan->status === 'Selesai') {
            return back()->with('error', 'Laporan ini sudah divalidasi sebelumnya.');
        }

        DB::transaction(function () use ($penugasan) {
            if (! $penugasan->tanggal_selesai) {
                $penugasan->update(['tanggal_selesai' => now()]);
            }

            $penugasan->laporan->update(['status' => 'Selesai']);
        });

        $this->kirimNotifikasi(
            $penugasan->laporan->user_id,
            $penugasan->laporan_id,
            'Laporan Selesai Ditangani',
            "Laporan \"{$penugasan->laporan->judul}\" telah selesai ditangani dan diverifikasi oleh instansi.",
            'informasi'
        );

        $this->kirimNotifikasi(
            $penugasan->petugas_id,
            $penugasan->laporan_id,
            'Closing Report Disetujui',
            "Closing report Anda untuk laporan \"{$penugasan->laporan->judul}\" telah disetujui.",
            'informasi'
        );

        return redirect()
            ->route('instansi.penugasan.index')
            ->with('success', 'Closing report divalidasi. Laporan dinyatakan SELESAI.');
    }

    /**
     * Closing report tidak valid -> tugas dikembalikan ke petugas.
     */
    private function tolakClosingReport(Penugasan $penugasan, ?string $catatan)
    {
        if ($penugasan->status !== 'selesai') {
            return back()->with(
                'error',
                'Tugas ini belum berada pada tahap validasi closing report.'
            );
        }

        $penugasan->update([
            'status' => 'dalam_proses',
            'tanggal_selesai' => null,
            'catatan' => $catatan,
        ]);

        $this->kirimNotifikasi(
            $penugasan->petugas_id,
            $penugasan->laporan_id,
            'Closing Report Perlu Perbaikan',
            "Closing report untuk laporan \"{$penugasan->laporan->judul}\" belum dapat disetujui. Catatan: {$catatan}",
            'warning'
        );

        return redirect()
            ->route('instansi.penugasan.index')
            ->with('success', 'Closing report dikembalikan ke petugas untuk diperbaiki.');
    }

    /**
     * Alihkan tugas ke tim satgas / petugas lain.
     */
    private function redisposisi(Request $request, Penugasan $penugasan)
    {
        $instansiId = $this->instansiId();

        if ($penugasan->laporan->status === 'Selesai') {
            return back()->with(
                'error',
                'Laporan yang sudah selesai tidak dapat didisposisikan ulang.'
            );
        }

        $this->pastikanTimMilikInstansi($request->tim_satgas_id, $instansiId);
        $this->pastikanPetugasMilikInstansi($request->petugas_id, $instansiId);

        $petugasLama = $penugasan->petugas_id;

        $penugasan->update([
            'tim_satgas_id' => $request->tim_satgas_id,
            'petugas_id' => $request->petugas_id,
            'status' => 'ditugaskan',
            // Hitung mundur SLA direset mengikuti penugasan baru
            'tanggal_penugasan' => now(),
            'tanggal_selesai' => null,
            'catatan' => $request->catatan,
        ]);

        if ($petugasLama && $petugasLama !== (int) $request->petugas_id) {
            $this->kirimNotifikasi(
                $petugasLama,
                $penugasan->laporan_id,
                'Penugasan Dialihkan',
                "Penugasan untuk laporan \"{$penugasan->laporan->judul}\" telah dialihkan ke petugas lain.",
                'informasi'
            );
        }

        $this->kirimNotifikasi(
            $request->petugas_id,
            $penugasan->laporan_id,
            'Tugas Baru Ditugaskan',
            "Anda ditugaskan menangani laporan \"{$penugasan->laporan->judul}\".",
            'informasi'
        );

        return redirect()
            ->route('instansi.penugasan.index')
            ->with('success', 'Penugasan berhasil dialihkan.');
    }

    // ===================== HELPER =====================

    private function kirimNotifikasi(
        ?int $userId,
        ?int $laporanId,
        string $judul,
        string $isi,
        string $tipe
    ): void {
        if (! $userId) {
            return;
        }

        Notifikasi::create([
            'user_id' => $userId,
            'laporan_id' => $laporanId,
            'judul' => $judul,
            'isi' => $isi,
            'tipe' => $tipe,
            'dibaca' => false,
        ]);
    }

    private function pastikanTimMilikInstansi($timSatgasId, int $instansiId): void
    {
        $valid = TimSatgas::where('id', $timSatgasId)
            ->where('instansi_id', $instansiId)
            ->where('status', 'aktif')
            ->exists();

        abort_unless($valid, 403, 'Tim satgas tidak tersedia untuk instansi Anda.');
    }

    private function pastikanPetugasMilikInstansi($petugasId, int $instansiId): void
    {
        $valid = User::where('id', $petugasId)
            ->where('role', 'petugas')
            ->where('instansi_id', $instansiId)
            ->exists();

        abort_unless($valid, 403, 'Petugas tidak tersedia untuk instansi Anda.');
    }

    private function authorizePenugasan(Penugasan $penugasan): void
    {
        abort_unless(
            $penugasan->laporan
                && $penugasan->laporan->instansi_id === $this->instansiId(),
            403,
            'Anda tidak memiliki akses ke penugasan ini.'
        );
    }

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
