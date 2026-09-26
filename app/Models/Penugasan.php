<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Penugasan extends Model
{
    use HasFactory;

    protected $table = 'penugasan';

    protected $fillable = [
        'laporan_id',
        'tim_satgas_id',
        'petugas_id',
        'status',
        'tanggal_penugasan',
        'tanggal_selesai',
        'catatan',
    ];

    protected $casts = [
        'tanggal_penugasan' => 'datetime',
        'tanggal_selesai'   => 'datetime',
    ];

    /**
     * Aturan SLA sesuai proposal bab 5.2 poin 7:
     * Kritis: 12 jam, Sedang: 3 hari, Rendah: 7 hari.
     *
     * TODO: kalau tabel `sla_konfigurasi` sudah dipakai untuk menyimpan
     * durasi ini secara dinamis (bisa diubah admin tanpa deploy ulang),
     * ganti array ini dengan query ke SlaKonfigurasi::where('tingkat_prioritas', ...)->first().
     */
    private const SLA_JAM = [
        'Kritis' => 12,
        'Sedang' => 24 * 3,
        'Rendah' => 24 * 7,
    ];

    // ===================== RELASI =====================

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class);
    }

    public function timSatgas(): BelongsTo
    {
        return $this->belongsTo(TimSatgas::class, 'tim_satgas_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // ===================== SCOPE =====================

    /**
     * Penugasan yang masih berjalan: sudah didisposisikan tapi
     * belum selesai divalidasi dan belum dibatalkan.
     * Dipakai di PenugasanController::index() sebagai filter default,
     * dan bisa dipakai juga untuk kartu "Tugas Aktif" di dashboard petugas.
     */
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['ditugaskan', 'dalam_proses']);
    }

    // ===================== SLA =====================

    /**
     * Batas waktu SLA dihitung dari tanggal_penugasan + durasi
     * sesuai tingkat_prioritas laporan terkait.
     * Return null kalau laporan/prioritas tidak diketahui.
     */
    public function batasWaktuSla(): ?Carbon
    {
        if (! $this->tanggal_penugasan || ! $this->laporan) {
            return null;
        }

        $prioritas = $this->laporan->tingkat_prioritas;
        $jam = self::SLA_JAM[$prioritas] ?? null;

        if (! $jam) {
            return null;
        }

        return $this->tanggal_penugasan->copy()->addHours($jam);
    }

    /**
     * Sisa waktu SLA dalam menit. Negatif berarti sudah lewat batas.
     * Return null kalau batas SLA tidak bisa dihitung.
     */
    public function sisaWaktuSla(): ?int
    {
        $batas = $this->batasWaktuSla();

        if (! $batas) {
            return null;
        }

        $acuan = $this->tanggal_selesai ?? now();

        return (int) $acuan->diffInMinutes($batas, false);
    }

    /**
     * Overdue = sudah lewat batas SLA dan belum selesai.
     * Penugasan yang sudah selesai/dibatalkan tidak dihitung overdue lagi,
     * supaya riwayat lama tidak terus menyala merah di dashboard.
     */
    public function isOverdue(): bool
    {
        if (in_array($this->status, ['selesai', 'dibatalkan'], true)) {
            return false;
        }

        $sisaMenit = $this->sisaWaktuSla();

        return $sisaMenit !== null && $sisaMenit < 0;
    }
}