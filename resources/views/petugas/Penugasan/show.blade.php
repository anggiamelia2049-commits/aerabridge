@extends('template.layout')

@section('title', 'Detail Penugasan')

@section('content')
@php
    $laporan = $penugasan->laporan;
    $adaKoordinat = filled($laporan->latitude) && filled($laporan->longitude);
@endphp

<div class="p-6">
    <div class="max-w-4xl mx-auto">
        @if (session('success'))
    <div class="mb-4 rounded-lg border border-hijau/30 bg-hijau/10 px-4 py-3 text-sm font-medium text-hijau" role="status">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 rounded-lg border border-merah/30 bg-merah/10 px-4 py-3 text-sm font-medium text-merah" role="alert">{{ session('error') }}</div>
@endif

        <div class="bg-white rounded-2xl shadow overflow-hidden">

            <div class="flex flex-col gap-3 px-6 py-5 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <h2 class="text-lg font-bold text-cyan-6">{{ $laporan->judul ?? 'Tanpa judul' }}</h2>
                    <p class="text-sm text-gray-600">{{ $laporan->kategori->nama_kategori ?? '-' }}</p>
                </div>
                <div class="flex shrink-0 flex-wrap gap-2">
                    <span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ match ((string) ($laporan->tingkat_prioritas)) { 'Kritis' => 'bg-merah/10 text-merah', 'Sedang' => 'bg-oranye/10 text-oranye', 'Rendah' => 'bg-hijau/10 text-hijau', default => 'bg-abu-muda text-abu-tua' } }}">{{ ($laporan->tingkat_prioritas) ?: '-' }}</span>
                    <span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ match (strtolower((string) ($penugasan->status))) { 'selesai' => 'bg-hijau/10 text-hijau', 'dalam_proses', 'diproses' => 'bg-oranye/10 text-oranye', 'ditugaskan', 'diverifikasi' => 'bg-cyan-3/20 text-cyan-6', 'dibatalkan', 'ditolak' => 'bg-merah/10 text-merah', default => 'bg-abu-muda text-abu-tua' } }}">{{ $penugasan->labelStatus() }}</span>
                </div>
            </div>

            <div class="px-6 pb-6 text-sm text-gray-700">

                <h3 class="mb-3 text-sm font-semibold text-cyan-6">Informasi laporan</h3>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div><p class="text-xs text-gray-500">Kategori</p><p class="font-semibold">{{ $laporan->kategori->nama_kategori ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Instansi</p><p class="font-semibold">{{ $laporan->instansi->nama_instansi ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Latitude</p><p class="font-semibold">{{ $laporan->latitude ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500">Longitude</p><p class="font-semibold">{{ $laporan->longitude ?? '-' }}</p></div>

                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500">Alamat</p>
                        <p class="font-semibold">{{ $laporan->alamat ?: '-' }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="mb-1 text-xs text-gray-500">Deskripsi</p>
                        <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line">{{ $laporan->deskripsi ?: '-' }}</div>
                    </div>
                </div>

                <h3 class="mb-3 mt-8 border-t border-gray-200 pt-6 text-sm font-semibold text-cyan-6">Informasi penugasan</h3>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div><p class="text-xs text-gray-500">Status tugas</p><p class="font-semibold">{{ $penugasan->labelStatus() }}</p></div>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal penugasan</p>
                        <p class="font-semibold">{{ $penugasan->tanggal_penugasan ? $penugasan->tanggal_penugasan->format('d-m-Y H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Batas SLA</p>
                        <p class="font-semibold">{{ $batasSla ? $batasSla->format('d-m-Y H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Sisa waktu</p>
                        <p class="font-semibold {{ $overdue ? 'text-merah' : '' }}">{{ $penugasan->teksSla() }}</p>
                        @if ($overdue)
                            <p class="text-xs text-merah">Melewati batas waktu penanganan.</p>
                        @endif
                    </div>

                    <div class="sm:col-span-2">
                        <p class="mb-1 text-xs text-gray-500">Catatan dari instansi</p>
                        <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line">{{ $penugasan->catatan ?: '-' }}</div>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-6 border-t border-gray-200 pt-6 {{ $penugasan->status === 'selesai' ? 'md:grid-cols-2' : '' }}">
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-cyan-6">Foto laporan</h3>
                        @if ($laporan->foto)
                            <a href="{{ asset('storage/' . $laporan->foto) }}" target="_blank" rel="noopener">
                                <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto laporan"
                                     class="w-full max-h-72 rounded-xl border border-abu-muda bg-gray-50 object-contain">
                            </a>
                        @else
                            <p class="text-gray-400">Tidak ada foto laporan.</p>
                        @endif
                    </div>

                    @if ($penugasan->status === 'selesai')
                        <div>
                            <h3 class="mb-3 text-sm font-semibold text-cyan-6">Hasil perbaikan</h3>
                            @if ($penugasan->foto_hasil)
                                <a href="{{ asset('storage/' . $penugasan->foto_hasil) }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('storage/' . $penugasan->foto_hasil) }}" alt="Foto hasil perbaikan"
                                         class="mb-3 w-full max-h-72 rounded-xl border border-abu-muda bg-gray-50 object-contain">
                                </a>
                            @else
                                <p class="mb-3 text-gray-400">Tidak ada foto hasil perbaikan.</p>
                            @endif
                            <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line">{{ $penugasan->catatan_penyelesaian ?: '-' }}</div>
                            @if ($penugasan->tanggal_selesai)
                                <p class="mt-2 text-xs text-gray-500">Dikirim pada {{ $penugasan->tanggal_selesai->format('d-m-Y H:i') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 px-6 py-5">
                <a href="{{ route('petugas.penugasan.index') }}"
                   class="mr-auto text-sm text-gray-500 hover:underline">← Kembali ke daftar</a>

                @if ($adaKoordinat)
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $laporan->latitude }},{{ $laporan->longitude }}"
                       target="_blank" rel="noopener"
                       class="px-5 py-2.5 rounded-lg border border-cyan-4 text-sm font-semibold text-cyan-4 hover:bg-cyan-4 hover:text-white transition">
                        Buka rute di Google Maps
                    </a>
                @endif

                @if ($penugasan->status === 'ditugaskan')
                    <form action="{{ route('petugas.penugasan.update', $penugasan->id) }}" method="POST"
                          onsubmit="return confirm('Mulai tugas ini sekarang?')">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="dalam_proses">
                        <button type="submit"
                                class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Mulai tugas</button>
                    </form>
                @endif

                @if ($penugasan->status === 'dalam_proses')
                    <a href="{{ route('petugas.penugasan.edit', $penugasan->id) }}"
                       class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Isi closing report</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection