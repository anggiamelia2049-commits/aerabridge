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
                @if ($laporan->foto)
                    <a href="{{ asset('storage/' . $laporan->foto) }}" target="_blank" rel="noopener">
                        <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto laporan"
                             class="w-48 h-48 rounded-xl object-cover border border-abu-muda">
                    </a>
                @else
                    <div class="w-48 h-48 rounded-xl bg-gray-100 border border-abu-muda flex items-center justify-center text-xs text-gray-400 text-center px-2">Tidak ada foto</div>
                @endif
            </div>

            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div><p class="text-xs text-gray-500">Pelapor</p><p class="font-semibold">{{ $laporan->user->nama ?? '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Kategori</p><p class="font-semibold">{{ $laporan->kategori->nama_kategori ?? '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Instansi</p><p class="font-semibold">{{ $laporan->instansi->nama_instansi ?? '-' }}</p></div>
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
                    <p class="text-xs text-gray-500">Alamat</p>
                    <p class="font-semibold">{{ $laporan->alamat ?: '-' }}</p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-xs text-gray-500 mb-1">Deskripsi</p>
                    <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line">{{ $laporan->deskripsi ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="px-6 pb-6">
            <a href="{{ route('petugas.laporan.index') }}" class="text-sm text-gray-500 hover:underline">← Kembali ke daftar laporan</a>
        </div>
    </div>
</div>
@endsection