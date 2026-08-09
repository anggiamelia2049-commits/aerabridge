<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEdukasiProgress extends Model
{
    protected $table = 'user_edukasi_progress';

    protected $fillable = [
        'user_id',
        'konten_id',
        'status',
        'progress',
        'selesai_pada',
    ];

    protected function casts(): array
    {
        return [
            'progress' => 'integer',
            'selesai_pada' => 'datetime',
        ];
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Konten Edukasi
    public function konten()
    {
        return $this->belongsTo(KontenEdukasi::class, 'konten_id');
    }
}