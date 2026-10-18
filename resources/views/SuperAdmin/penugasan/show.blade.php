@extends('template.layout')

@section('title', 'Detail Penugasan')

@section('content')
@php
    $laporan = $penugasan->laporan;
    $statusClass = match ($penugasan->status) {
        'selesai'      => 'bg-hijau/10 text-hijau',
        'dalam_proses' => 'bg-oranye/10 text-oranye',
        'ditugaskan'   => 'bg-cyan-3/20 text-cyan-6',
        'dibatalkan'   => 'bg-merah/10 text-merah',
        default        => 'bg-abu-muda text-abu-tua',
    };
    $overdue = $penugasan->isOverdue();
@endphp

<div class="p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex flex-wrap items-start justify-between gap-3 px-6 py-5">
            <div class="min-w-0">
                <h2 class="text-lg font-bold text-cyan-6">{{ $laporan->judul ?? 'Tanpa judul' }}</h2>
                <p class="text-sm text-gray-600">Detail penugasan (mode pantau).</p>
            </div>
            <span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                {{ $penugasan->labelStatus() }}
            </span>
        </div>

        <div class="px-6 pb-6 space-y-6 text-sm text-gray-700">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div><p class="text-xs text-gray-500">Kategori</p><p class="font-semibold">{{ $laporan->kategori->nama_kategori ?? '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Instansi</p><p class="font-semibold">{{ $laporan->instansi->nama_instansi ?? '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Pelapor</p><p class="font-semibold">{{ $laporan?->nama_pelapor ?? '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Lokasi</p><p class="font-semibold">{{ $laporan?->lokasi_teks ?? '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Tim Satgas</p><p class="font-semibold">{{ $penugasan->timSatgas->nama_tim ?? '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Petugas</p><p class="font-semibold">{{ $penugasan->petugas->nama ?? '-' }}</p></div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal Penugasan</p>
                    <p class="font-semibold">{{ $penugasan->tanggal_penugasan?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal Selesai</p>
                    <p class="font-semibold">{{ $penugasan->tanggal_selesai?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Batas SLA</p>
                    <p class="font-semibold">{{ $penugasan->batasWaktuSla()?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Sisa Waktu</p>
                    <p class="font-semibold {{ $overdue ? 'text-merah' : '' }}">{{ $penugasan->teksSla() }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Catatan dari instansi</p>
                <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line">{{ $penugasan->catatan ?: '-' }}</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-200 pt-6">
                <div>
                    <h3 class="mb-3 text-sm font-semibold text-cyan-6">Foto laporan</h3>
                    @if ($laporan?->gambar_utama)
                        <a href="{{ asset('storage/' . $laporan->gambar_utama) }}" target="_blank" rel="noopener">
                            <img src="{{ asset('storage/' . $laporan->gambar_utama) }}" alt="Foto laporan"
                                 class="w-full max-h-72 rounded-xl border border-abu-muda bg-gray-50 object-contain">
                        </a>
                    @else
                        <p class="text-gray-400">Tidak ada foto laporan.</p>
                    @endif
                </div>

                <div>
                    <h3 class="mb-3 text-sm font-semibold text-cyan-6">Hasil perbaikan</h3>
                    @if ($penugasan->foto_hasil)
                        <a href="{{ asset('storage/' . $penugasan->foto_hasil) }}" target="_blank" rel="noopener">
                            <img src="{{ asset('storage/' . $penugasan->foto_hasil) }}" alt="Foto hasil perbaikan"
                                 class="mb-3 w-full max-h-72 rounded-xl border border-abu-muda bg-gray-50 object-contain">
                        </a>
                        <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line">{{ $penugasan->catatan_penyelesaian ?: '-' }}</div>
                    @else
                        <p class="text-gray-400">Belum ada closing report.</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('super_admin.penugasan.index') }}" class="text-sm text-gray-500 hover:underline">← Kembali</a>
                @if ($laporan)
                    <a href="{{ route('super_admin.laporan.show', $laporan->id) }}"
                       class="text-sm font-semibold text-cyan-4 hover:underline">Lihat laporan →</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection