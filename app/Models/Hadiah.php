<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class Hadiah extends Model
{
    protected $table = 'hadiah';

    protected $fillable = [
        'nama_hadiah',
        'deskripsi',
        'poin_dibutuhkan',
        'stok',
        'gambar',
        'status'
    ];

    #[Override]
    protected function casts(): array
    {
        return [
            'poin_dibutuhkan' => 'integer',
            'stok' => 'integer'
        ];
    }
}
