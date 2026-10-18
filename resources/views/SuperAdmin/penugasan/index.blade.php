@extends('template.layout')

@section('title', 'Penugasan')

@section('content')
@php
    $filterStatus = request('status');
    $opsiStatus = ['ditugaskan' => 'Ditugaskan', 'dalam_proses' => 'Dalam proses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'];
    $daftar = $filterStatus && isset($opsiStatus[$filterStatus])
        ? $penugasan->where('status', $filterStatus)
        : $penugasan;
@endphp

<div class="p-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">Pantau seluruh penugasan laporan ke petugas lapangan.</p>

            <form method="GET" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()"
                        class="h-10 rounded-lg border border-abu-muda bg-white pl-3 pr-9 text-sm">
                    <option value="">Semua status</option>
                    @foreach ($opsiStatus as $nilai => $teks)
                        <option value="{{ $nilai }}" @selected($filterStatus === $nilai)>{{ $teks }}</option>
                    @endforeach
                </select>
                @if ($filterStatus)
                    <a href="{{ route('super_admin.penugasan.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">No</th>
                        <th class="px-4 py-3.5 font-semibold">Laporan</th>
                        <th class="px-4 py-3.5 font-semibold">Tim Satgas</th>
                        <th class="px-4 py-3.5 font-semibold">Petugas</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold">Tgl Penugasan</th>
                        <th class="px-4 py-3.5 font-semibold">Tgl Selesai</th>
                        <th class="px-4 py-3.5 font-semibold">SLA</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($daftar as $v)
                        @php $terlambat = $v->isOverdue(); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3.5 min-w-[200px] font-semibold">{{ $v->laporan->judul ?? '-' }}</td>
                            <td class="px-4 py-3.5">{{ $v->timSatgas->nama_tim ?? '-' }}</td>
                            <td class="px-4 py-3.5">{{ $v->petugas->nama ?? '-' }}</td>
                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold',
                                    'bg-hijau/10 text-hijau' => $v->status === 'selesai',
                                    'bg-oranye/10 text-oranye' => $v->status === 'dalam_proses',
                                    'bg-cyan-3/20 text-cyan-6' => $v->status === 'ditugaskan',
                                    'bg-merah/10 text-merah' => $v->status === 'dibatalkan',
                                ])>{{ $v->labelStatus() }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">{{ $v->tanggal_penugasan?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">{{ $v->tanggal_selesai?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3.5">
                                <span class="{{ $terlambat ? 'font-semibold text-merah' : 'text-gray-600' }}">{{ $v->teksSla() }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <a href="{{ route('super_admin.penugasan.show', $v->id) }}"
                                   class="text-sm font-semibold text-cyan-4 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-400">Belum ada data penugasan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection