<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\KategoriKerusakan;
use App\Models\Instansi;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'instansi_id',
        'judul',
        'deskripsi',
        'foto',
        'latitude',
        'longitude',
        'alamat',
        'tingkat_prioritas',
        'status',
        'diverifikasi_oleh',
    ];

    // User yang membuat laporan
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Kategori kerusakan laporan
    public function kategori()
    {
        return $this->belongsTo(
            KategoriKerusakan::class,
            'kategori_id'
        );
    }

    // Instansi tujuan laporan
    public function instansi()
    {
        return $this->belongsTo(
            Instansi::class,
            'instansi_id'
        );
    }

    // User yang melakukan verifikasi
    public function diverifikasiOleh()
    {
        return $this->belongsTo(
            User::class,
            'diverifikasi_oleh'
        );
    }
}