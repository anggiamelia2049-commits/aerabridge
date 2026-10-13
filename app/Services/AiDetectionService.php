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

    public function detectAndSave($laporanId, $fotoPath)
    {
        $response = Http::timeout(30)
            ->attach('file', file_get_contents($fotoPath), basename($fotoPath))
            ->post("{$this->aiServiceUrl}/api/ai/detect");

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

        // Update laporan berdasarkan hasil AI
        $laporan = Laporan::find($laporanId);

        if ($laporan) {
            $update = [];

            $prioritas = ucfirst(strtolower($result['estimasi_prioritas'] ?? ''));
            if (in_array($prioritas, ['Kritis', 'Sedang', 'Rendah'])) {
                $update['tingkat_prioritas'] = $prioritas;
            }

            if (($result['hasil_validasi'] ?? '') === 'valid') {
                $update['status'] = 'Terverifikasi AI';
            }

            if ($update) {
                $laporan->update($update);
            }
        }

        return $deteksi;
    }
}