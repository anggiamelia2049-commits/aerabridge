<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\KategoriKerusakan;
use App\Models\Instansi;
use App\Models\Penugasan;
use App\Models\DeteksiAi;
use Illuminate\Support\Str;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'user_id',
        'kode_lacak',
        'is_anonim',
        'kategori_id',
        'instansi_id',
        'judul',
        'deskripsi',
        'tanggal_kejadian',
        'kecamatan',
        'jenis_laporan',
        'kategori_lainnya',
        'foto',
        'lampiran',
        'latitude',
        'longitude',
        'alamat',
        'tingkat_prioritas',
        'status',
        'diverifikasi_oleh',
        
    ];

    protected $casts = ['is_anonim' => 'boolean'];

    public static function buatKode(): string
    {
        do {
            $kode = 'AEB-' . strtoupper(Str::random(8));
        } while (self::where('kode_lacak', $kode)->exists());

        return $kode;
    }

    public function getNamaPelaporAttribute(): string
    {
        return $this->is_anonim || ! $this->user ? 'Anonim' : $this->user->nama;
    }

    // Gambar utama: foto kamera, atau lampiran kalau berupa gambar
    public function getGambarUtamaAttribute(): ?string
    {
        if ($this->foto) {
            return $this->foto;
        }

        if ($this->lampiran
            && in_array(strtolower(pathinfo($this->lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'], true)) {
            return $this->lampiran;
        }

        return null;
    }

    // Teks lokasi: kecamatan (laporan baru) atau alamat (laporan lama)
    public function getLokasiTeksAttribute(): string
    {
        if ($this->kecamatan) {
            return 'Kecamatan ' . $this->kecamatan;
        }

        return $this->alamat ?: '-';
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

    // Hasil deteksi AI untuk laporan ini
    public function deteksiAi()
    {
        return $this->hasOne(DeteksiAi::class, 'laporan_id')->latestOfMany();
    }
}