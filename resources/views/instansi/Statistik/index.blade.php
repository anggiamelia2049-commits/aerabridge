@extends('template.layout')

@section('title', 'Statistik')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-h1 leading-tight">Statistik</h1>
        <p class="text-subtitle mt-1">Ringkasan laporan dan penugasan di instansi Anda.</p>
    </div>

    {{-- Ringkasan --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ([
            ['Total laporan', $totalLaporan],
            ['Menunggu verifikasi', $menungguVerifikasi],
            ['Sedang diproses', $laporanDiproses],
            ['Selesai', $laporanSelesai],
            ['Penugasan aktif', $penugasanAktif],
        ] as [$label, $nilai])
            <div class="rounded-lg bg-white p-4 shadow">
                <p class="text-caption text-abu-tua">{{ $label }}</p>
                <p class="mt-1 text-[24px] font-bold text-cyan-6">{{ $nilai }}</p>
            </div>
        @endforeach
    </div>

    {{-- Grafik batang: status, prioritas, kategori --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        @foreach ([
            ['Laporan menurut status', $grafikStatus],
            ['Laporan menurut prioritas', $grafikPrioritas],
            ['Laporan menurut kategori', $grafikKategori],
        ] as [$judul, $data])
            @php $maks = max($data->max('jumlah'), 1); @endphp
            <div class="rounded-lg bg-white p-5 shadow">
                <h2 class="mb-4 text-[18px] font-semibold text-cyan-6">{{ $judul }}</h2>

                @forelse ($data as $baris)
                    <div class="mb-3">
                        <div class="mb-1 flex justify-between text-[14px]">
                            <span class="text-abu-tua">{{ $baris['label'] }}</span>
                            <span class="font-medium text-cyan-6">{{ $baris['jumlah'] }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100">
                            <div class="h-2 rounded-full"
                                 style="width: {{ round($baris['jumlah'] / $maks * 100) }}%; background-color: {{ $baris['warna'] }}"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-[14px] text-abu-tua">Belum ada data.</p>
                @endforelse
            </div>
        @endforeach
    </div>
</div>
@endsection
