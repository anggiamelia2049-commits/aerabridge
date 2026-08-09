<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontenEdukasi extends Model
{
    protected $table = 'konten_edukasi';

    protected $fillable = [
        'judul',
        'thumbnail',
        'isi',
        'kategori',
        'penulis',
        'status',
    ];

    // relasi ke user sebagai penulis
    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis');
    }
}
