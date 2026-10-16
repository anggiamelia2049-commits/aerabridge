@extends('template.layout')

@section('title', 'Notifikasi')

@section('content')
<div class="p-6">
    @if (session('success'))
    <div class="mb-4 rounded-lg border border-hijau/30 bg-hijau/10 px-4 py-3 text-sm font-medium text-hijau" role="status">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 rounded-lg border border-merah/30 bg-merah/10 px-4 py-3 text-sm font-medium text-merah" role="alert">{{ session('error') }}</div>
@endif

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-600">
                Kamu punya <span class="font-semibold text-cyan-6">{{ $jumlahBelumDibaca }}</span> notifikasi yang belum dibaca.
            </p>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('petugas.notifikasi.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $filter === 'semua' ? 'bg-cyan-4 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50' }}">Semua</a>
                <a href="{{ route('petugas.notifikasi.index', ['filter' => 'belum_dibaca']) }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition {{ $filter === 'belum_dibaca' ? 'bg-cyan-4 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50' }}">Belum dibaca</a>

                @if ($jumlahBelumDibaca > 0)
                    <form action="{{ route('petugas.notifikasi.update', 'semua') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-cyan-4 hover:underline">Tandai semua dibaca</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold">Judul</th>
                        <th class="px-4 py-3.5 font-semibold">Tipe</th>
                        <th class="px-4 py-3.5 font-semibold">Tanggal</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($notifikasis as $v)
                        <tr class="hover:bg-gray-50 {{ $v->dibaca ? '' : 'bg-cyan-3/10' }}">
                            <td class="px-4 py-3.5">
                                <span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $v->dibaca ? 'bg-abu-muda text-abu-tua' : 'bg-cyan-3/20 text-cyan-6' }}">
                                    {{ $v->dibaca ? 'Sudah dibaca' : 'Baru' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 min-w-[220px] {{ $v->dibaca ? '' : 'font-semibold' }}">{{ $v->judul }}</td>
                            <td class="px-4 py-3.5">{{ ucfirst($v->tipe) }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">{{ $v->created_at->format('d-m-Y H:i') }}</td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center text-sm font-semibold">
                                    <a href="{{ route('petugas.notifikasi.show', $v->id) }}" class="text-cyan-4 hover:underline">Buka</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                                {{ $filter === 'belum_dibaca' ? 'Semua notifikasi sudah dibaca.' : 'Belum ada notifikasi.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection