@extends('template.layout')

@section('title', 'Dashboard Warga')

@section('content')
@php
    $cards = [
        [
            'label' => 'Total Laporan',
            'value' => $totalLaporan,
            'note'  => 'Semua laporan yang kamu buat',
            'icon'  => '<path d="M9 12h6m-6 4h6m2 5H7a2.25 2.25 0 01-2.25-2.25V6.75A2.25 2.25 0 017 4.5h2.25M15 5.25h1.5a2.25 2.25 0 012.25 2.25v12A2.25 2.25 0 0116.5 21.75H7.5m0-18h3.75a1.5 1.5 0 011.5 1.5v.75a1.5 1.5 0 01-1.5 1.5H7.5a1.5 1.5 0 01-1.5-1.5v-.75a1.5 1.5 0 011.5-1.5z"/>',
        ],
        [
            'label' => 'Sedang Diproses',
            'value' => $laporanDiproses,
            'note'  => 'Laporan yang sedang ditangani',
            'icon'  => '<path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>',
        ],
        [
            'label' => 'Laporan Selesai',
            'value' => $laporanSelesai,
            'note'  => 'Laporan yang telah diselesaikan',
            'icon'  => '<path d="M4.5 12.75l6 6 9-13.5"/>',
        ],
    ];

    $statusBadge = [
        'selesai'   => ['Selesai', 'bg-hijau/10 text-hijau'],
        'ditangani' => ['Selesai', 'bg-hijau/10 text-hijau'],
        'diproses'  => ['Diproses', 'bg-oranye/10 text-oranye'],
        'proses'    => ['Diproses', 'bg-oranye/10 text-oranye'],
    ];
@endphp

{{-- ===== Sapaan ===== --}}
<p class="text-sm text-gray-500 mb-4">
    Selamat datang, <span class="font-medium text-gray-700">{{ $user->nama }}</span>
</p>

{{-- ===== Kartu Statistik ===== --}}
<section class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    @foreach ($cards as $c)
        <div class="bg-white rounded-xl shadow-sm px-6 py-6 flex items-center gap-4">
            <span class="w-14 h-14 shrink-0 rounded-full bg-gray-100 flex items-center justify-center text-gray-700">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="width:26px;height:26px;">
                    {!! $c['icon'] !!}
                </svg>
            </span>

            <div class="flex flex-col leading-tight min-w-0">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wide truncate">{{ $c['label'] }}</div>
                <div class="text-3xl font-extrabold text-gray-800 leading-none mt-1.5">
                    {{ number_format($c['value'], 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-400 mt-1.5 truncate">{{ $c['note'] }}</div>
            </div>
        </div>
    @endforeach
</section>

{{-- ===== Laporan Terbaru ===== --}}
<section class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-bold text-gray-800">Laporan Terbaru</h2>
        <a href="{{ url('/warga/laporan') }}" class="text-sm text-cyan-4 hover:text-cyan-6 hover:underline">
            Lihat Semua
        </a>
    </div>

    <div class="overflow-x-auto max-h-[320px] overflow-y-auto">
        <table class="w-full text-sm text-left">
            <thead class="sticky top-0 bg-cyan-4/25 text-cyan-6 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 rounded-l-md">Judul Laporan</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3 rounded-r-md text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($laporanTerbaru as $laporan)
                    @php
                        $statusKey = strtolower($laporan->status ?? '');
                        [$teks, $kelas] = $statusBadge[$statusKey] ?? ['Menunggu', 'bg-cyan-4/10 text-cyan-6'];
                    @endphp
                    <tr class="hover:bg-gray-100 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $laporan->judul }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $laporan->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block min-w-[64px] rounded-md px-3 py-1 text-xs font-semibold {{ $kelas }}">
                                {{ $teks }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                            Belum ada laporan yang kamu buat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- ===== Kontribusi Saya ===== --}}
<section class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-base font-bold text-gray-800 mb-4">Kontribusi Saya</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gray-100 rounded-lg p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Total Poin Kontribusi</p>
            <p class="text-2xl font-extrabold text-gray-800 mt-1">
                {{ number_format($totalPoin) }}
                <span class="text-sm font-normal text-gray-500">Poin</span>
            </p>
            <a href="{{ route('warga.poin.index') }}" class="inline-block mt-2 text-sm text-cyan-4 hover:text-cyan-6 hover:underline">
                Lihat Kontribusi
            </a>
        </div>

        <div class="bg-gray-100 rounded-lg p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide font-bold">Saldo AERA Pay</p>
            <p class="text-2xl font-extrabold text-gray-800 mt-1">
                Rp{{ number_format($saldoSaatIni) }}
            </p>
            <div class="flex gap-4 mt-2 text-sm">
                <a href="{{ route('warga.aeraPay.index') }}" class="text-cyan-4 hover:text-cyan-6 hover:underline">Riwayat</a>
                <a href="{{ route('warga.aeraPay.create') }}" class="text-cyan-4 hover:text-cyan-6 hover:underline">Tukar Poin</a>
            </div>
        </div>
    </div>
</section>
@push('styles')
<style>
    body > div.flex.min-h-screen {
        height: 100vh;
        overflow: hidden;
    }

    body > div.flex.min-h-screen > aside,
    body > div.flex.min-h-screen > aside > div {
        height: 100vh;
    }

    body > div.flex.min-h-screen main {
        height: 100vh;
        overflow-y: auto;
    }
</style>
@endpush
@endsection