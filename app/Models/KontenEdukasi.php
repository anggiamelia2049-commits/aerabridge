<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontenEdukasi extends Model
{
    protected $table = 'konten_edukasi';

    protected $fillable = [
        'super_admin',
        'judul',
        'thumbnail',
        'isi',
        'kategori',
        'status',
    ];

    // relasi ke user sebagai penulis
    public function super_admin()
    {
        return $this->belongsTo(User::class, 'super_admin');
    }
}