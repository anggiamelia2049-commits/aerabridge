@extends('template.layout')

@section('title', 'Notifikasi')

@section('content')
@php
    // Ikon & label per tipe notifikasi
    $ikonTipe = [
        'penugasan_baru'      => 'M12 21s-7-6.2-7-11a7 7 0 1114 0c0 4.8-7 11-7 11zm0-8.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z',
        'prioritas_diubah'    => 'M5 21V4m0 0h11l-2 4 2 4H5',
        'laporan_diverifikasi'=> 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'pengingat_tugas'     => 'M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z',
    ];
    $ikonDefault = 'M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0';

    // Kelompokkan per tanggal
    $kelompok = $notifikasis->groupBy(fn ($n) => $n->created_at->toDateString());

    $labelHari = function (string $tanggal) {
        $c = \Illuminate\Support\Carbon::parse($tanggal);
        if ($c->isToday())     return 'Hari ini';
        if ($c->isYesterday()) return 'Kemarin';
        return $c->format('d-m-Y');
    };
@endphp

<div class="p-6">
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-hijau/30 bg-hijau/10 px-4 py-3 text-sm font-medium text-hijau" role="status">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg border border-merah/30 bg-merah/10 px-4 py-3 text-sm font-medium text-merah" role="alert">{{ session('error') }}</div>
    @endif

    {{-- Judul halaman (di luar kartu) --}}
    <div class="mb-6">
        <h1 class="text-h1">Notifikasi</h1>
        <p class="text-subtitle mt-1">Pantau kabar terbaru seputar penugasan dan laporan anda</p>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        {{-- Tab filter + tandai semua dibaca --}}
        <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-2 border-b border-gray-200 px-6">
            <nav class="flex gap-6" aria-label="Filter notifikasi">
                <a href="{{ route('petugas.notifikasi.index') }}"
                   @if ($filter === 'semua') aria-current="page" @endif
                   class="-mb-px border-b-2 py-4 text-sm font-semibold transition
                          {{ $filter === 'semua' ? 'border-cyan-4 text-cyan-4' : 'border-transparent text-gray-500 hover:text-cyan-6' }}">
                    Semua
                </a>
                <a href="{{ route('petugas.notifikasi.index', ['filter' => 'belum_dibaca']) }}"
                   @if ($filter === 'belum_dibaca') aria-current="page" @endif
                   class="-mb-px flex items-center gap-2 border-b-2 py-4 text-sm font-semibold transition
                          {{ $filter === 'belum_dibaca' ? 'border-cyan-4 text-cyan-4' : 'border-transparent text-gray-500 hover:text-cyan-6' }}">
                    Belum dibaca
                    @if ($jumlahBelumDibaca > 0)
                        <span class="rounded-full bg-cyan-3/20 px-2 py-0.5 text-xs font-semibold text-cyan-6">{{ $jumlahBelumDibaca }}</span>
                    @endif
                </a>
            </nav>

            @if ($jumlahBelumDibaca > 0)
                <form action="{{ route('petugas.notifikasi.update', 'semua') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="text-sm font-semibold text-cyan-4 hover:underline">Tandai semua dibaca</button>
                </form>
            @endif
        </div>

        {{-- Daftar notifikasi per hari --}}
        @forelse ($kelompok as $tanggal => $items)
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-2 text-xs font-semibold text-gray-500 {{ $loop->first ? '' : 'border-t' }}">
                {{ $labelHari($tanggal) }}
            </div>

            <ul class="divide-y divide-gray-200">
                @foreach ($items as $v)
                    @php
                        $baru  = ! $v->dibaca;
                        $path  = $ikonTipe[$v->tipe] ?? $ikonDefault;
                        $label = ucfirst(str_replace('_', ' ', (string) $v->tipe));
                    @endphp
                    <li>
                        <a href="{{ route('petugas.notifikasi.show', $v->id) }}"
                           class="flex items-center gap-4 px-6 py-4 transition hover:bg-gray-50 {{ $baru ? 'bg-cyan-3/10' : '' }}">

                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $baru ? 'bg-cyan-3/20 text-cyan-6' : 'bg-abu-muda text-abu-tua' }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                                </svg>
                            </span>

                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm text-gray-800 {{ $baru ? 'font-semibold' : '' }}">{{ $v->judul }}</span>
                                <span class="block text-xs text-gray-500">{{ $label }}</span>
                            </span>

                            <span class="shrink-0 text-xs text-gray-500">{{ $v->created_at->format('H:i') }}</span>

                            @if ($baru)
                                <span class="sr-only">Belum dibaca</span>
                                <span class="h-2 w-2 shrink-0 rounded-full bg-cyan-4" aria-hidden="true"></span>
                            @else
                                <span class="h-2 w-2 shrink-0" aria-hidden="true"></span>
                            @endif

                            <span class="shrink-0 text-sm font-semibold text-cyan-4">Buka</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @empty
            <div class="px-6 py-14 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $ikonDefault }}"/>
                </svg>
                <p class="mt-3 text-sm font-semibold text-gray-700">
                    {{ $filter === 'belum_dibaca' ? 'Semua notifikasi sudah dibaca' : 'Belum ada notifikasi' }}
                </p>
                <p class="mt-1 text-sm text-gray-500">Notifikasi baru akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
