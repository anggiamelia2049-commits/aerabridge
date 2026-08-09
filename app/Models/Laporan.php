<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'diverifikasi_oleh'
    ];

    // relasi ke user yang membuat laporan
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // relasi ke kategori kerusakan
    public function kategori()
    {
        return $this->belongsTo(KategoriKerusakan::class, 'kategori_id');
    }

    // relasi ke instansi
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    // relasi ke user yang melakukakn verifikasi
    public function verifier()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
