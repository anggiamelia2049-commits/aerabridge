<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\KategoriKerusakan;
use App\Models\Instansi;
use App\Models\Penugasan;
use Illuminate\Support\Str;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'instansi_id',
        'judul',
        'deskripsi',
        'foto',
        'lampiran',
        'latitude',
        'longitude',
        'alamat',
        'tingkat_prioritas',
        'status',
        'diverifikasi_oleh',
        'kode_lacak',
        'is_anonim',
    ];

    protected $casts = ['is_anonim' => 'boolean'];

    public static function buatKode(): string
    {
        do {
            $kode = 'AEB-' . strtoupper(Str::random(8));
        } while (self::where('kode_lacak', $kode)->exists());

        return $kode;
    }

    // Pakai ini di semua dashboard: {{ $laporan->nama_pelapor }}
    public function getNamaPelaporAttribute(): string
    {
        return $this->is_anonim || ! $this->user ? 'Anonim' : $this->user->name;
    }

    // User yang membuat laporan
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Kategori kerusakan laporan
    public function kategori()
    {
        return $this->belongsTo(
            KategoriKerusakan::class,
            'kategori_id'
        );
    }

    // Instansi tujuan laporan
    public function instansi()
    {
        return $this->belongsTo(
            Instansi::class,
            'instansi_id'
        );
    }

    public function penugasan()
    {
        return $this->hasMany(Penugasan::class, 'laporan_id');
    }

    // User yang melakukan verifikasi
    public function diverifikasiOleh()
    {
        return $this->belongsTo(
            User::class,
            'diverifikasi_oleh'
        );
    }
}