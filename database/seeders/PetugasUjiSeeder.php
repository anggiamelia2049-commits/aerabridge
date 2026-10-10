<?php

namespace Database\Seeders;

use App\Models\Laporan;
use App\Models\Notifikasi;
use App\Models\Penugasan;
use App\Models\TimSatgas;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data uji untuk alur petugas: satu penugasan + satu notifikasi
 * dari laporan pertama yang sudah ada. Aman dijalankan berulang.
 */
class PetugasUjiSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = User::where('role', 'petugas')->first();
        $laporan = Laporan::first();

        if (! $petugas || ! $laporan) {
            $this->command->warn('Butuh minimal 1 user petugas dan 1 laporan.');
            return;
        }

        // Pakai prioritas Kritis sekalian membuktikan perbaikan enum berhasil
        $laporan->update([
            'tingkat_prioritas' => 'Kritis',
            'status'            => 'Diverifikasi',
        ]);

        $tim = TimSatgas::firstOrCreate(
            ['instansi_id' => $laporan->instansi_id, 'nama_tim' => 'Tim Uji Lapangan'],
            ['ketua' => 'Budi', 'jumlah_anggota' => 3, 'kontak' => '081234567890', 'status' => 'aktif']
        );

        Penugasan::firstOrCreate(
            ['laporan_id' => $laporan->id, 'petugas_id' => $petugas->id],
            [
                'tim_satgas_id'     => $tim->id,
                'status'            => 'ditugaskan',
                'tanggal_penugasan' => now(),
                'catatan'           => 'Data uji: cek lokasi dan perbaiki kerusakan.',
            ]
        );

        Notifikasi::firstOrCreate(
            ['user_id' => $petugas->id, 'laporan_id' => $laporan->id, 'judul' => 'Penugasan baru'],
            [
                'isi'    => "Anda mendapat tugas untuk laporan \"{$laporan->judul}\".",
                'tipe'   => 'informasi',
                'dibaca' => false,
            ]
        );

        $this->command->info("Data uji dibuat untuk petugas: {$petugas->email}");
    }
}