<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaKonfigurasi extends Model
{
    protected $table = 'sla_konfigurasi';

    protected $fillable = [
        'prioritas',
        'waktu_respon',
        'waktu_penyelesaian',
        'deskripsi',
        'status',
    ];
}