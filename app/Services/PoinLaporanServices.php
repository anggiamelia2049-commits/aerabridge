<?php

namespace App\Services;

use App\Models\Laporan;
use App\Models\Notifikasi;
use App\Models\PoinKontribusiLog;

class PoinLaporanService
{
    /**
     * +50 saat laporan diverifikasi.
     * Hanya untuk laporan milik akun role warga (anonim tidak dapat poin).
     */
    public static function verifikasi(Laporan $laporan): void
    {
        $user = $laporan->user;

        if (! $user || $user->role !== 'warga') {
            return;
        }

        $log = PoinKontribusiLog::tambahPoin(
            $user->id,
            50,
            'laporan_terverifikasi',
            $laporan->id,
            'Laporan "' . $laporan->judul . '" terverifikasi'
        );

        // $log null berarti poin ini sudah pernah diberikan, jadi tidak ada notifikasi ganda
        if ($log) {
            self::notif(
                $user->id,
                $laporan->id,
                'Laporan Terverifikasi (+50 poin)',
                'Laporan "' . $laporan->judul . '" telah diverifikasi. Kamu mendapat 50 poin kontribusi.'
            );
        }
    }

    /**
     * Saat laporan selesai:
     * - pastikan +50 sudah diberikan (kalau sebelumnya terlewat)
     * - +30 tambahan kalau prioritas Kritis
     */
    public static function selesai(Laporan $laporan): void
    {
        $user = $laporan->user;

        if (! $user || $user->role !== 'warga') {
            return;
        }

        self::verifikasi($laporan);

        if ($laporan->tingkat_prioritas === 'Kritis') {
            $log = PoinKontribusiLog::tambahPoin(
                $user->id,
                30,
                'laporan_kritis_selesai',
                $laporan->id,
                'Laporan kritis "' . $laporan->judul . '" selesai ditangani'
            );

            if ($log) {
                self::notif(
                    $user->id,
                    $laporan->id,
                    'Bonus Laporan Kritis (+30 poin)',
                    'Laporan kritis "' . $laporan->judul . '" selesai ditangani. Kamu mendapat bonus 30 poin.'
                );
            }
        }
    }

    private static function notif(int $userId, int $laporanId, string $judul, string $isi): void
    {
        Notifikasi::create([
            'user_id'    => $userId,
            'laporan_id' => $laporanId,
            'judul'      => $judul,
            'isi'        => $isi,
            'tipe'       => 'informasi',
            'dibaca'     => false,
        ]);
    }
}