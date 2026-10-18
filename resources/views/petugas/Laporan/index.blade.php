@extends('template.layout')

@section('title', 'Laporan')

@section('content')
@php
    $tabs = ['masuk' => 'Masuk', 'selesai' => 'Selesai'];
    $prioritas = [
        'Kritis' => ['bar' => 'bg-merah', 'pill' => 'bg-merah/10 text-merah'],
        'Sedang' => ['bar' => 'bg-oranye', 'pill' => 'bg-oranye/10 text-oranye'],
        'Rendah' => ['bar' => 'bg-hijau', 'pill' => 'bg-hijau/10 text-hijau'],
    ];
    $statusStyle = fn ($s) => match (strtolower((string) ($s ?: 'menunggu'))) {
        'selesai' => ['bg-hijau/10 text-hijau', 'bg-hijau'],
        'dalam_proses', 'diproses' => ['bg-oranye/10 text-oranye', 'bg-oranye'],
        'ditugaskan', 'diverifikasi' => ['bg-cyan-3/20 text-cyan-6', 'bg-cyan-4'],
        'dibatalkan', 'ditolak' => ['bg-merah/10 text-merah', 'bg-merah'],
        default => ['bg-abu-muda text-abu-tua', 'bg-abu-tua'],
    };
@endphp

<div class="p-6">
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-hijau/30 bg-hijau/10 px-4 py-3 text-sm font-medium text-hijau" role="status">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg border border-merah/30 bg-merah/10 px-4 py-3 text-sm font-medium text-merah" role="alert">{{ session('error') }}</div>
    @endif

    {{-- Header halaman (di luar card) --}}
    <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-h1 leading-tight">Laporan ditugaskan</h1>
                <p class="text-subtitle mt-1">Laporan masyarakat yang ditugaskan kepada anda</p>
            </div>

            <nav class="inline-flex rounded-xl bg-gray-100 p-1" aria-label="Filter laporan">
                @foreach ($tabs as $key => $label)
                    <a href="{{ route('petugas.laporan.index', ['tab' => $key]) }}"
                       @if ($tab === $key) aria-current="page" @endif
                       class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold no-underline hover:no-underline transition {{ $tab === $key ? 'bg-white text-cyan-6 shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                        {{ $label }}
                        @if ($tab === $key)
                            <span class="rounded-full bg-cyan-4 px-2 py-0.5 text-xs text-white">{{ $laporan->count() }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>
    </div>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-xs font-bold text-gray-700">
                    <tr>
                        <th class="w-1"></th>
                        <th class="px-4 py-3">Laporan</th>
                        <th class="px-4 py-3">Pelapor</th>
                        <th class="px-4 py-3">Kategori &amp; instansi</th>
                        <th class="px-4 py-3">Prioritas</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Diverifikasi oleh</th>
                        <th class="w-40 px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($laporan as $v)
                        @php
                            $p = $prioritas[$v->tingkat_prioritas] ?? ['bar' => 'bg-gray-300', 'pill' => 'bg-abu-muda text-abu-tua'];
                            [$stPill, $stDot] = $statusStyle($v->status);
                        @endphp
                        <tr class="transition hover:bg-cyan-3/5">
                            <td class="w-1 p-0"><span class="block h-full min-h-[72px] w-1 {{ $p['bar'] }}"></span></td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3 min-w-[260px] max-w-[340px]">
                                    @if ($v->gambar_utama)
                                        <img src="{{ asset('storage/' . $v->gambar_utama) }}" alt="Foto laporan"
                                             class="h-14 w-14 shrink-0 rounded-xl border border-gray-200 object-cover">
                                    @else
                                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-[10px] text-gray-400">Tanpa foto</div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-gray-900" title="{{ $v->judul }}">{{ $v->judul }}</p>
                                        <p class="truncate text-xs text-gray-500" title="{{ $v->deskripsi }}">{{ $v->deskripsi }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 font-medium text-gray-900">{{ $v->nama_pelapor }}</td>

                            <td class="px-4 py-3">
                                <p>{{ $v->kategori->nama_kategori ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $v->instansi->nama_instansi ?? '-' }}</p>
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-block whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $p['pill'] }}">{{ $v->tingkat_prioritas ?: '-' }}</span>
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap rounded-full px-3 py-1 text-xs font-semibold {{ $stPill }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $stDot }}"></span>
                                    {{ ucfirst(str_replace('_', ' ', $v->status ?? 'Menunggu')) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-gray-600">{{ $v->diverifikasiOleh->nama ?? '-' }}</td>

                            <td class="px-4 py-3">
                                <a href="{{ route('petugas.laporan.show', $v->id) }}"
                                   class="inline-block rounded-lg border border-cyan-4 px-3.5 py-1.5 text-xs font-semibold text-cyan-4 transition hover:bg-cyan-4 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-cyan-4">
                                    Lihat detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-16 text-center">
                                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-3/15 text-cyan-4">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0h-5l-1 2h-4l-1-2H4"/></svg>
                                </div>
                                <p class="font-semibold text-gray-800">{{ $tab === 'selesai' ? 'Belum ada laporan yang selesai' : 'Belum ada laporan masuk' }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $tab === 'selesai' ? 'Laporan yang sudah kamu tuntaskan akan muncul di sini.' : 'Laporan baru dari admin akan muncul di sini setelah ditugaskan.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
