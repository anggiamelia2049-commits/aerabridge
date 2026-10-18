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

    {{-- Header + filter --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-h1">Penugasan</h1>
            <p class="text-subtitle mt-1">Pantau tugas penanganan laporan yang diberikan</p>
        </div>

        <nav class="flex flex-wrap gap-2" aria-label="Filter penugasan">
            @foreach ($tabs as $key => $label)
                <a href="{{ route('petugas.penugasan.index', $key === 'semua' ? [] : ['filter' => $key]) }}"
                   @if ($filter === $key) aria-current="page" @endif
                   class="rounded-full px-4 py-1.5 text-sm font-semibold no-underline transition
                          {{ $filter === $key ? 'bg-cyan-4 text-white' : 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    {{-- Daftar kartu --}}
    @if ($penugasan->count())
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($penugasan as $item)
                @php
                    $terlambat  = $item->isOverdue();
                    $prioritas  = (string) ($item->laporan->tingkat_prioritas ?? '');

                    $garis = match ($prioritas) {
                        'Kritis' => 'border-l-merah',
                        'Sedang' => 'border-l-oranye',
                        'Rendah' => 'border-l-hijau',
                        default  => 'border-l-gray-300',
                    };
                    $badgePrioritas = match ($prioritas) {
                        'Kritis' => 'bg-merah/10 text-merah',
                        'Sedang' => 'bg-oranye/10 text-oranye',
                        'Rendah' => 'bg-hijau/10 text-hijau',
                        default  => 'bg-abu-muda text-abu-tua',
                    };
                    $badgeStatus = match (strtolower((string) $item->status)) {
                        'selesai'                  => 'bg-hijau/10 text-hijau',
                        'dalam_proses', 'diproses' => 'bg-oranye/10 text-oranye',
                        'ditugaskan', 'diverifikasi' => 'bg-cyan-3/20 text-cyan-6',
                        'dibatalkan', 'ditolak'    => 'bg-merah/10 text-merah',
                        default                    => 'bg-abu-muda text-abu-tua',
                    };
                @endphp

                <article class="flex flex-col rounded-xl border border-gray-200 border-l-4 {{ $garis }} bg-white p-4 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $badgePrioritas }}">
                            {{ $prioritas ?: '-' }}
                        </span>
                        <span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $badgeStatus }}">
                            {{ $item->labelStatus() }}
                        </span>
                    </div>

                    <h3 class="mt-3 line-clamp-2 text-base font-semibold text-gray-800">
                        {{ $item->laporan->judul ?? '-' }}
                    </h3>

                    <p class="mt-1 text-xs text-gray-500">
                        Ditugaskan {{ $item->tanggal_penugasan ? $item->tanggal_penugasan->format('d-m-Y H:i') : '-' }}
                    </p>

                    <div class="mt-auto pt-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <span class="text-xs {{ $terlambat ? 'font-semibold text-merah' : 'text-gray-600' }}">
                                    {{ $item->teksSla() }}
                                </span>
                                @if ($terlambat)
                                    <span class="block text-xs text-merah">Melewati batas waktu</span>
                                @endif
                            </div>

                            <a href="{{ route('petugas.penugasan.show', $item->id) }}"
                               class="rounded-lg bg-cyan-4 px-4 py-1.5 text-sm font-semibold text-white no-underline transition hover:bg-cyan-6">
                                Detail
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        {{-- Kondisi kosong --}}
        <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-14 text-center">
            <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="mt-3 text-sm font-semibold text-gray-700">
                Belum ada penugasan{{ $filter !== 'semua' ? ' di tab ini' : '' }}
            </p>
            <p class="mt-1 text-sm text-gray-500">Tugas baru akan muncul di sini.</p>
        </div>
    @endif
</div>
@endsection
