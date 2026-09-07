<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class KontenEdukasi extends Model
{
    protected $table = 'konten_edukasi';

    protected $fillable = [
        'penulis',
        'judul',
        'thumbnail',
        'isi',
        'kategori',
        'status',
    ];

    // Relasi ke user sebagai penulis
    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis');
    }
}