<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
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

    // Relasi ke Laporan
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    // Relasi ke Tim Satgas
    public function timSatgas()
    {
        return $this->belongsTo(TimSatgas::class, 'tim_satgas_id');
    }

    // Relasi ke User sebagai petugas
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}