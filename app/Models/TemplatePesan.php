<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatePesan extends Model
{
    protected $table = 'template_pesan';

    protected $fillable = [
        'kode_template',
        'judul',
        'isi_pesan',
        'kategori',
        'status',
    ];
}