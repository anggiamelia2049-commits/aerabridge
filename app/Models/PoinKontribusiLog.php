<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class PoinKontribusiLog extends Model
{
    protected $table = 'poin_kontribusi_log';

    protected $fillable = [
        'user_id', 'laporan_id', 'konten_id',
        'jenis_aktivitas', 'poin', 'poin_sesudah', 'keterangan',
    ];

    public function user()    { return $this->belongsTo(User::class, 'user_id'); }
    public function laporan() { return $this->belongsTo(Laporan::class, 'laporan_id'); }
    public function konten()  { return $this->belongsTo(KontenEdukasi::class, 'konten_id'); }

    /**
     * Tambah (atau kurangi, kalau $poin negatif) poin dengan aman.
     * Mengembalikan null kalau poin untuk laporan/konten itu sudah pernah diberikan.
     * Melempar RuntimeException kalau poin tidak cukup.
     */
    public static function tambahPoin(
        int $userId,
        int $poin,
        string $jenisAktivitas,
        ?int $laporanId = null,
        ?string $keterangan = null,
        ?int $kontenId = null
    ): ?self {
        try {
            return DB::transaction(function () use ($userId, $poin, $jenisAktivitas, $laporanId, $keterangan, $kontenId) {
                $user = User::whereKey($userId)->lockForUpdate()->firstOrFail();

                // Idempotent: satu laporan/konten tidak boleh memberi poin jenis yang sama dua kali
                if ($laporanId || $kontenId) {
                    $sudah = self::where('user_id', $userId)
                        ->where('jenis_aktivitas', $jenisAktivitas)
                        ->when($laporanId, fn ($q) => $q->where('laporan_id', $laporanId))
                        ->when($kontenId, fn ($q) => $q->where('konten_id', $kontenId))
                        ->exists();

                    if ($sudah) {
                        return null;
                    }
                }

                $baru = (int) $user->total_poin + $poin;

                if ($baru < 0) {
                    throw new \RuntimeException('Poin tidak cukup.');
                }

                $log = self::create([
                    'user_id'         => $userId,
                    'laporan_id'      => $laporanId,
                    'konten_id'       => $kontenId,
                    'jenis_aktivitas' => $jenisAktivitas,
                    'poin'            => $poin,
                    'poin_sesudah'    => $baru,
                    'keterangan'      => $keterangan,
                ]);

                $user->total_poin = $baru;
                $user->save();

                return $log;
            });
        } catch (UniqueConstraintViolationException) {
            return null; // pengaman terakhir dari unique index
        }
    }

    public static function totalPoin(int $userId): int
    {
        return (int) User::whereKey($userId)->value('total_poin');
    }
}