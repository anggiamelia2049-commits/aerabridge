<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimSatgas extends Model
{
    protected $table = 'tim_satgas';

    protected $fillable = [
        'instansi_id',
        'nama_tim',
        'ketua',
        'jumlah_anggota',
        'kontak',
        'status'
    ];

    protected function casts(): array
    {
        return[
            'jumlah_anggota'=> 'integer'
        ];
    }

    // relasi ke instansi
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    // relasi ke penugasan
    public function penugasan()
    {
        return $this->hasMany(Penugasan::class, 'tim_satgas_id');
    }
}
