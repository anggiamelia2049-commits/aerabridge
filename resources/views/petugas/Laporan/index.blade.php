@extends('template.layout')

@section('title', 'Laporan')

@section('content')
@php
    $tabs = ['masuk' => 'Masuk', 'selesai' => 'Selesai'];
@endphp

<div class="p-6">
    @if (session('success'))
    <div class="mb-4 rounded-lg border border-hijau/30 bg-hijau/10 px-4 py-3 text-sm font-medium text-hijau" role="status">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 rounded-lg border border-merah/30 bg-merah/10 px-4 py-3 text-sm font-medium text-merah" role="alert">{{ session('error') }}</div>
@endif

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-600">Laporan masyarakat yang ditugaskan kepada kamu.</p>

            <nav class="flex flex-wrap gap-2" aria-label="Filter laporan">
                @foreach ($tabs as $key => $label)
                    <a href="{{ route('petugas.laporan.index', ['tab' => $key]) }}"
                       @if ($tab === $key) aria-current="page" @endif
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $tab === $key ? 'bg-cyan-4 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">No</th>
                        <th class="px-4 py-3.5 font-semibold">Pelapor</th>
                        <th class="px-4 py-3.5 font-semibold">Kategori</th>
                        <th class="px-4 py-3.5 font-semibold">Instansi</th>
                        <th class="px-4 py-3.5 font-semibold">Judul Laporan</th>
                        <th class="px-4 py-3.5 font-semibold">Foto</th>
                        <th class="px-4 py-3.5 font-semibold">Prioritas</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold">Diverifikasi Oleh</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($laporan as $v)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3.5 font-semibold">{{ $v->user->nama ?? '-' }}</td>
                            <td class="px-4 py-3.5">{{ $v->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-4 py-3.5">{{ $v->instansi->nama_instansi ?? '-' }}</td>

                            <td class="px-4 py-3.5 min-w-[180px] max-w-[240px]">
                                <p class="truncate font-semibold" title="{{ $v->judul }}">{{ $v->judul }}</p>
                                <p class="truncate text-xs text-gray-500" title="{{ $v->deskripsi }}">{{ $v->deskripsi }}</p>
                            </td>

                            <td class="px-4 py-3.5">
                                @if ($v->foto)
                                    <img src="{{ asset('storage/' . $v->foto) }}" alt="Foto laporan"
                                         class="w-12 h-12 object-cover rounded-lg border border-abu-muda">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 border border-abu-muda flex items-center justify-center text-[10px] text-gray-400">Tidak ada</div>
                                @endif
                            </td>

                            <td class="px-4 py-3.5"><span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ match ((string) ($v->tingkat_prioritas)) { 'Kritis' => 'bg-merah/10 text-merah', 'Sedang' => 'bg-oranye/10 text-oranye', 'Rendah' => 'bg-hijau/10 text-hijau', default => 'bg-abu-muda text-abu-tua' } }}">{{ ($v->tingkat_prioritas) ?: '-' }}</span></td>
                            <td class="px-4 py-3.5"><span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ match (strtolower((string) ($v->status ?? 'Menunggu'))) { 'selesai' => 'bg-hijau/10 text-hijau', 'dalam_proses', 'diproses' => 'bg-oranye/10 text-oranye', 'ditugaskan', 'diverifikasi' => 'bg-cyan-3/20 text-cyan-6', 'dibatalkan', 'ditolak' => 'bg-merah/10 text-merah', default => 'bg-abu-muda text-abu-tua' } }}">{{ ucfirst(str_replace('_', ' ', ($v->status ?? 'Menunggu'))) }}</span></td>
                            <td class="px-4 py-3.5">{{ $v->diverifikasiOleh->nama ?? '-' }}</td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center text-sm font-semibold">
                                    <a href="{{ route('petugas.laporan.show', $v->id) }}" class="text-cyan-4 hover:underline">Detail</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-10 text-center text-gray-400">
                                {{ $tab === 'selesai' ? 'Belum ada laporan yang selesai.' : 'Belum ada laporan masuk.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection