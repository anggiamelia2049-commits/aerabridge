<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKerusakan extends Model
{
    protected $table = 'kategori_kerusakan';

    protected $fillable = [
        'nama_kategori',
        'icon',
        'deskripsi',
        'warna_marker',
        'status'
    ];

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'kategori_id');
    }
}