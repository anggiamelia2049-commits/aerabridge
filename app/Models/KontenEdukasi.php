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

    public function penulisUser()
    {
        return $this->belongsTo(User::class, 'penulis');
    }

    public function progress()
    {
        return $this->hasMany(UserEdukasiProgress::class, 'konten_id');
    }
}