@extends('template.layout')

@section('title', 'Penugasan')

@section('content')
@php
    $tabs = ['semua' => 'Semua', 'aktif' => 'Aktif', 'prioritas' => 'Prioritas', 'selesai' => 'Selesai'];
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
            <p class="text-sm text-gray-600">Pantau tugas penanganan laporan yang diberikan kepada kamu.</p>

            <nav class="flex flex-wrap gap-2" aria-label="Filter penugasan">
                @foreach ($tabs as $key => $label)
                    <a href="{{ route('petugas.penugasan.index', $key === 'semua' ? [] : ['filter' => $key]) }}"
                       @if ($filter === $key) aria-current="page" @endif
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition
                              {{ $filter === $key ? 'bg-cyan-4 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
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
                        <th class="px-4 py-3.5 font-semibold">Laporan</th>
                        <th class="px-4 py-3.5 font-semibold">Prioritas</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold">Tanggal Penugasan</th>
                        <th class="px-4 py-3.5 font-semibold">SLA</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($penugasan as $item)
                        @php $terlambat = $item->isOverdue(); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">{{ $loop->iteration }}</td>

                            <td class="px-4 py-3.5 min-w-[200px] font-semibold">
                                {{ $item->laporan->judul ?? '-' }}
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ match ((string) ($item->laporan->tingkat_prioritas ?? null)) { 'Kritis' => 'bg-merah/10 text-merah', 'Sedang' => 'bg-oranye/10 text-oranye', 'Rendah' => 'bg-hijau/10 text-hijau', default => 'bg-abu-muda text-abu-tua' } }}">{{ ($item->laporan->tingkat_prioritas ?? null) ?: '-' }}</span>
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ match (strtolower((string) ($item->status))) { 'selesai' => 'bg-hijau/10 text-hijau', 'dalam_proses', 'diproses' => 'bg-oranye/10 text-oranye', 'ditugaskan', 'diverifikasi' => 'bg-cyan-3/20 text-cyan-6', 'dibatalkan', 'ditolak' => 'bg-merah/10 text-merah', default => 'bg-abu-muda text-abu-tua' } }}">{{ $item->labelStatus() }}</span>
                            </td>

                            <td class="px-4 py-3.5 whitespace-nowrap">
                                {{ $item->tanggal_penugasan ? $item->tanggal_penugasan->format('d-m-Y H:i') : '-' }}
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="{{ $terlambat ? 'font-semibold text-merah' : 'text-gray-600' }}">{{ $item->teksSla() }}</span>
                                @if ($terlambat)
                                    <span class="block text-xs text-merah">Melewati batas waktu</span>
                                @endif
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center text-sm font-semibold">
                                    <a href="{{ route('petugas.penugasan.show', $item->id) }}" class="text-cyan-4 hover:underline">Detail</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                                Belum ada penugasan{{ $filter !== 'semua' ? ' di tab ini' : '' }}. Tugas baru akan muncul di sini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection