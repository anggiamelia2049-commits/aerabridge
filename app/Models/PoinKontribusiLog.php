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
}