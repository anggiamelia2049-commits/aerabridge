<?php

namespace App\Services;

use App\Models\DeteksiAi;
use App\Models\Laporan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AiDetectionService
{
    protected string $aiServiceUrl;

    public function __construct()
    {
        $this->aiServiceUrl = rtrim(
            (string) config(
                'services.ai.url',
                env('AI_SERVICE_URL', 'http://127.0.0.1:8081')
            ),
            '/'
        );
    }

    public function detectAndSave(
        $laporanId,
        $fotoPath,
        $deskripsi = ''
    ) {
        Log::info('AERA AI: Memulai deteksi laporan.', [
            'laporan_id' => $laporanId,
            'ai_url' => $this->aiServiceUrl,
        ]);

        // Pastikan foto tersedia sebelum dikirim ke FastAPI.
        if (!is_file($fotoPath) || !is_readable($fotoPath)) {
            Log::error('AERA AI: File foto tidak ditemukan atau tidak dapat dibaca.', [
                'laporan_id' => $laporanId,
            ]);

            throw new \RuntimeException(
                'Foto laporan tidak ditemukan atau tidak dapat dibaca.'
            );
        }

        try {
            $response = Http::timeout(60)
                ->connectTimeout(10)
                ->attach(
                    'file',
                    file_get_contents($fotoPath),
                    basename($fotoPath)
                )
                ->post(
                    $this->aiServiceUrl . '/api/ai/detect',
                    [
                        'deskripsi' => (string) $deskripsi,
                    ]
                );
        } catch (Throwable $e) {
            Log::error('AERA AI: Tidak dapat menghubungi FastAPI.', [
                'laporan_id' => $laporanId,
                'ai_url' => $this->aiServiceUrl,
                'error' => $e->getMessage(),
            ]);

            throw new \RuntimeException(
                'Layanan AI tidak dapat dihubungi. Pastikan FastAPI berjalan pada port 8081.',
                0,
                $e
            );
        }

        if (!$response->successful()) {
            Log::error('AERA AI: FastAPI mengembalikan error.', [
                'laporan_id' => $laporanId,
                'http_status' => $response->status(),
                'response' => mb_substr($response->body(), 0, 1000),
            ]);

            throw new \RuntimeException(
                'Layanan AI mengembalikan HTTP ' . $response->status() . '.'
            );
        }

        $result = $response->json();

        if (!is_array($result)) {
            Log::error('AERA AI: Respons FastAPI bukan JSON yang valid.', [
                'laporan_id' => $laporanId,
            ]);

            throw new \RuntimeException(
                'Respons layanan AI tidak valid.'
            );
        }

        Log::info('AERA AI: Respons FastAPI diterima.', [
            'laporan_id' => $laporanId,
            'metode_analisis' => $result['metode_analisis'] ?? 'tidak diketahui',
            'jenis_objek' => $result['jenis_objek'] ?? null,
            'confidence' => $result['confidence'] ?? null,
        ]);

        // Foto tidak menghasilkan objek yang dikenali.
        if (empty($result['jenis_objek'])) {
            Log::warning('AERA AI: Tidak ada objek yang terdeteksi.', [
                'laporan_id' => $laporanId,
                'response_llm' => $result['response_llm'] ?? null,
            ]);

            return null;
        }

        $tingkatValid = ['ringan', 'sedang', 'berat'];
        $prioritasValid = ['kritis', 'sedang', 'rendah'];

        $tingkat = strtolower(
            (string) ($result['tingkat_kerusakan'] ?? '')
        );

        $prioritasAi = strtolower(
            (string) ($result['estimasi_prioritas'] ?? '')
        );

        if (
            !in_array($tingkat, $tingkatValid, true) ||
            !in_array($prioritasAi, $prioritasValid, true)
        ) {
            Log::error('AERA AI: Hasil analisis tidak valid.', [
                'laporan_id' => $laporanId,
                'tingkat_kerusakan' => $tingkat,
                'estimasi_prioritas' => $prioritasAi,
            ]);

            throw new \RuntimeException(
                'Hasil analisis AI tidak memiliki tingkat kerusakan atau prioritas yang valid.'
            );
        }

        // Simpan hasil deteksi AI.
        $deteksi = DeteksiAi::create([
            'laporan_id' => $laporanId,
            'jenis_objek' => $result['jenis_objek'],
            'confidence' => $result['confidence'] ?? 0,
            'tingkat_kerusakan' => $tingkat,
            'estimasi_prioritas' => $prioritasAi,
            'hasil_validasi' => $result['hasil_validasi'] ?? 'valid',
            'response_llm' => $result['response_llm'] ?? '',
        ]);

        // Prioritas AI hanya menjadi rekomendasi.
        // Status laporan tidak diubah otomatis.
        $laporan = Laporan::find($laporanId);

        if (!$laporan) {
            Log::warning('AERA AI: Laporan tidak ditemukan untuk pembaruan prioritas.', [
                'laporan_id' => $laporanId,
                'deteksi_ai_id' => $deteksi->id,
            ]);

            return $deteksi;
        }

        /*
         * Sesuaikan mapping ini dengan enum aktual
         * kolom laporan.tingkat_prioritas di database.
         *
         * Jika enum database menggunakan "Krisis",
         * nilai AI "kritis" harus dipetakan ke "Krisis".
         */
        $prioritasDatabase = [
            'kritis' => 'Krisis',
            'sedang' => 'Sedang',
            'rendah' => 'Rendah',
        ];

        $prioritas = $prioritasDatabase[$prioritasAi];

        // Periksa nilai yang benar-benar diizinkan oleh database.
        $kolom = $laporan->getConnection()
            ->getSchemaBuilder()
            ->getColumnType(
                $laporan->getTable(),
                'tingkat_prioritas'
            );

        $nilaiDiizinkan = [
            'Krisis',
            'Sedang',
            'Rendah',
        ];

        /*
         * Mapping di atas sesuai jika enum database memang
         * menggunakan Krisis, Sedang, dan Rendah.
         * Jika skema berbeda, jangan paksa update.
         */
        if (!in_array($prioritas, $nilaiDiizinkan, true)) {
            Log::warning('AERA AI: Prioritas tidak dapat dipetakan.', [
                'laporan_id' => $laporanId,
                'prioritas_ai' => $prioritasAi,
            ]);

            return $deteksi;
        }

        $laporan->update([
            'tingkat_prioritas' => $prioritas,
        ]);

        Log::info('AERA AI: Hasil deteksi berhasil disimpan.', [
            'laporan_id' => $laporanId,
            'deteksi_ai_id' => $deteksi->id,
            'prioritas' => $prioritas,
            'status_laporan' => $laporan->status,
        ]);

        return $deteksi;
    }
}