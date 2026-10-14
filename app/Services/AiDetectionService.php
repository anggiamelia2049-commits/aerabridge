<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\DeteksiAi;
use App\Models\Laporan;

class AiDetectionService
{
    protected string $aiServiceUrl;

    public function __construct()
    {
        $this->aiServiceUrl = config('services.ai.url');
    }

    public function detectAndSave($laporanId, $fotoPath, $deskripsi = '')
    {
        $response = Http::timeout(40)
            ->attach('file', file_get_contents($fotoPath), basename($fotoPath))
            ->post("{$this->aiServiceUrl}/api/ai/detect", [
                'deskripsi' => (string) $deskripsi,
            ]);

        if (!$response->successful()) {
            throw new \Exception('AI service error, status ' . $response->status());
        }

        $result = $response->json();

        // Kalau AI tidak mendeteksi apa pun, tidak ada yang disimpan
        if (empty($result['jenis_objek'])) {
            return null;
        }

        $deteksi = DeteksiAi::create([
            'laporan_id' => $laporanId,
            'jenis_objek' => $result['jenis_objek'],
            'confidence' => $result['confidence'],
            'tingkat_kerusakan' => $result['tingkat_kerusakan'],
            'estimasi_prioritas' => $result['estimasi_prioritas'],
            'hasil_validasi' => $result['hasil_validasi'],
            'response_llm' => $result['response_llm'],
        ]);

        // Update laporan berdasarkan hasil AI — HANYA prioritas yang diubah otomatis.
        // Status laporan TETAP "Menunggu" sampai instansi melakukan verifikasi manual
        // (sesuai flowchart proposal: AI kasih rekomendasi, keputusan akhir di instansi).
        $laporan = Laporan::find($laporanId);

        if ($laporan) {
            $prioritas = ucfirst(strtolower($result['estimasi_prioritas'] ?? ''));

            if (in_array($prioritas, ['Kritis', 'Sedang', 'Rendah'])) {
                $laporan->update(['tingkat_prioritas' => $prioritas]);
            }
        }

        return $deteksi;
    }
}