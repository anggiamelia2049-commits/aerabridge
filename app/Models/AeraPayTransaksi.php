<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AeraPayTransaksi extends Model
{
    protected $table = 'aera_pay_transaksi';

    protected $fillable = [
        'user_id',
        'laporan_id',
        'jenis_transaksi',
        'nominal',
        'saldo_sebelum',
        'saldo_sesudah',
        'status'
    ];

    // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // relasi ke laporan
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }
}
