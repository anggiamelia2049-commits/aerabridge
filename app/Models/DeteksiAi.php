<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class DeteksiAi extends Model
{
    protected $table = 'deteksi_ai';

    protected $fillable = [
        'laporan_id',
        'jenis_objek',
        'confidence',
        'tingkat_kerusakan',
        'estimasi_prioritas',
        'hasil_validasi',
        'respon_llm'
    ];

    protected function casts(): array
    {
        {
            return [
            'confidence' => 'decimal:2',
            ];
        };
    }

    // relasi ke laporan
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }
}
