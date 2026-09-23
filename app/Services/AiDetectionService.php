<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\DeteksiAi;

class AiDetectionService
{
    protected string $aiServiceUrl;

    public function __construct()
    {
        $this->aiServiceUrl = config('services.ai.url');
    }

    public function detectAndSave($laporanId, $fotoPath)
    {
        $response = Http::attach(
            'file', file_get_contents($fotoPath), basename($fotoPath)
        )->post("{$this->aiServiceUrl}/api/ai/detect");

        $result = $response->json();

        return DeteksiAi::create([
            'laporan_id' => $laporanId,
            'jenis_objek' => $result['jenis_objek'],
            'confidence' => $result['confidence'],
            'tingkat_kerusakan' => $result['tingkat_kerusakan'],
            'estimasi_prioritas' => $result['estimasi_prioritas'],
            'hasil_validasi' => $result['hasil_validasi'],
            'response_llm' => $result['response_llm'],
        ]);
    }
}