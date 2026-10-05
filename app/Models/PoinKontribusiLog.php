<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoinKontribusiLog extends Model
{
    protected $table = 'poin_kontribusi_log';

    protected $fillable = [
        'user_id',
        'laporan_id',
        'jenis_aktivitas',
        'poin',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    public static function tambahPoin(
        int $userId,
        int $poin,
        string $jenisAktivitas,
        ?int $laporanId = null,
        ?string $keterangan = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'laporan_id' => $laporanId,
            'jenis_aktivitas' => $jenisAktivitas,
            'poin' => $poin,
            'keterangan' => $keterangan,
        ]);
    }

        public static function totalPoin(int $userId): int
    {
        return (int) self::where('user_id', $userId)->sum('poin');
    }
}