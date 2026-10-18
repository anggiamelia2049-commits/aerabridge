@extends('template.layout')

@section('title', 'Detail Laporan')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow overflow-hidden">

        <div class="px-6 py-5">
            <h2 class="text-lg font-bold text-cyan-6">{{ $laporan->judul }}</h2>
            <p class="text-sm text-gray-600">Informasi lengkap laporan masyarakat.</p>
        </div>

        <div class="px-6 pb-6 flex flex-col sm:flex-row gap-6 text-sm text-gray-700">

            <div class="shrink-0">
                @if ($laporan->gambar_utama)
                    <a href="{{ asset('storage/' . $laporan->gambar_utama) }}" target="_blank" rel="noopener">
                        <img src="{{ asset('storage/' . $laporan->gambar_utama) }}" alt="Foto laporan"
                            class="w-full max-h-72 rounded-xl border border-abu-muda bg-gray-50 object-contain">
                    </a>
                @elseif ($laporan->lampiran)
                    <a href="{{ asset('storage/' . $laporan->lampiran) }}" target="_blank" rel="noopener"
                    class="text-cyan-4 font-semibold hover:underline">Buka lampiran (PDF)</a>
                @else
                    <p class="text-gray-400">Tidak ada foto laporan.</p>
                @endif
            </div>

            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div><p class="text-xs text-gray-500">Pelapor</p><p class="font-semibold">{{ $laporan->nama_pelapor }}</p></div>                <div><p class="text-xs text-gray-500">Instansi</p><p class="font-semibold">{{ $laporan->instansi->nama_instansi ?? '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Diverifikasi oleh</p><p class="font-semibold">{{ $laporan->diverifikasiOleh->nama ?? '-' }}</p></div>

                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Tingkat prioritas</p>
                    <span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ match ((string) ($laporan->tingkat_prioritas)) { 'Kritis' => 'bg-merah/10 text-merah', 'Sedang' => 'bg-oranye/10 text-oranye', 'Rendah' => 'bg-hijau/10 text-hijau', default => 'bg-abu-muda text-abu-tua' } }}">{{ ($laporan->tingkat_prioritas) ?: '-' }}</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Status</p>
                    <span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ match (strtolower((string) ($laporan->status ?? 'Menunggu'))) { 'selesai' => 'bg-hijau/10 text-hijau', 'dalam_proses', 'diproses' => 'bg-oranye/10 text-oranye', 'ditugaskan', 'diverifikasi' => 'bg-cyan-3/20 text-cyan-6', 'dibatalkan', 'ditolak' => 'bg-merah/10 text-merah', default => 'bg-abu-muda text-abu-tua' } }}">{{ ucfirst(str_replace('_', ' ', ($laporan->status ?? 'Menunggu'))) }}</span>
                </div>

                <div><p class="text-xs text-gray-500">Latitude</p><p class="font-semibold">{{ $laporan->latitude ?? '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Longitude</p><p class="font-semibold">{{ $laporan->longitude ?? '-' }}</p></div>

                <div class="sm:col-span-2">
                    <p class="text-xs text-gray-500">Lokasi</p>
                    <p class="font-semibold">{{ $laporan->lokasi_teks }}</p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-xs text-gray-500 mb-1">Deskripsi</p>
                    <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line">{{ $laporan->deskripsi ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="px-6 pb-6">
            <div class="px-6 pb-6">
                <a href="{{ route('petugas.laporan.index') }}" class="text-sm text-gray-500">Kembali ke daftar laporan</a>
            </div>        
        </div>
    </div>
</div>
@endsection