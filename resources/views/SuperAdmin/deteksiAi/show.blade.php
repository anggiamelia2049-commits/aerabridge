@extends('template.layout')

@section('title', 'Detail Deteksi AI')

@section('content')
@php
    $laporan = $deteksiAi->laporan;
    $persen = $deteksiAi->confidence <= 1 ? $deteksiAi->confidence * 100 : $deteksiAi->confidence;
    $prioritas = ucfirst(strtolower((string) $deteksiAi->estimasi_prioritas));
    $prioritasClass = match ($prioritas) {
        'Kritis' => 'bg-merah/10 text-merah',
        'Sedang' => 'bg-oranye/10 text-oranye',
        'Rendah' => 'bg-kuning/20 text-abu-tua',
        default  => 'bg-abu-muda text-abu-tua',
    };
    $valid = strtolower((string) $deteksiAi->hasil_validasi) === 'valid';
@endphp

<div class="p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex flex-wrap items-start justify-between gap-3 px-6 py-5">
            <div class="min-w-0">
                <h2 class="text-lg font-bold text-cyan-6">Detail Deteksi AI</h2>
                <p class="text-sm text-gray-600">Hasil analisis YOLO dan LLM untuk laporan masuk.</p>
            </div>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $prioritasClass }}">
                Prioritas: {{ $prioritas ?: '-' }}
            </span>
        </div>

        <div class="px-6 pb-6 space-y-6 text-sm text-gray-700">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <p class="text-xs text-gray-500">ID Deteksi</p>
                    <p class="font-mono">#{{ $deteksiAi->id }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Laporan</p>
                    @if ($laporan)
                        <a href="{{ route('super_admin.laporan.show', $laporan->id) }}"
                           class="font-semibold text-cyan-4 hover:underline">{{ $laporan->judul }}</a>
                    @else
                        <p class="font-semibold">{{ $deteksiAi->laporan_id }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-500">Jenis Objek</p>
                    <p class="font-semibold">{{ $deteksiAi->jenis_objek ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tingkat Kerusakan</p>
                    <p class="font-semibold">{{ $deteksiAi->tingkat_kerusakan ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Confidence</p>
                    <div class="flex items-center gap-3">
                        <div class="h-2 w-40 rounded-full bg-abu-muda overflow-hidden">
                            <div class="h-full bg-cyan-4" style="width: {{ min(100, max(0, $persen)) }}%"></div>
                        </div>
                        <span class="font-semibold">{{ number_format($persen, 0) }}%</span>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Hasil Validasi</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $valid ? 'bg-hijau/10 text-hijau' : 'bg-merah/10 text-merah' }}">
                        {{ $deteksiAi->hasil_validasi ?: '-' }}
                    </span>
                </div>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Ringkasan LLM</p>
                <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line leading-relaxed">{{ $deteksiAi->response_llm ?: '-' }}</div>
            </div>

            <p class="text-xs text-gray-500">Dibuat: {{ $deteksiAi->created_at?->translatedFormat('d F Y, H:i') }}</p>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('super_admin.deteksi-ai.index') }}" class="text-sm text-gray-500 hover:underline">← Kembali</a>
                @if ($laporan)
                    <a href="{{ route('super_admin.laporan.show', $laporan->id) }}"
                       class="text-sm font-semibold text-cyan-4 hover:underline">Lihat laporan →</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection