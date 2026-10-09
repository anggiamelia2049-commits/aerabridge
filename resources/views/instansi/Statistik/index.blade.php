@extends('template.layout')

@section('title', 'Statistik')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-800">Statistik</h1>
        <p class="text-sm text-slate-500">Ringkasan laporan dan penugasan di instansi Anda.</p>
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
            <div class="rounded-xl border border-slate-200 bg-white p-4">
                <p class="text-sm text-slate-500">{{ $label }}</p>
                <p class="mt-1 text-2xl font-semibold text-slate-800">{{ $nilai }}</p>
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
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <h2 class="mb-4 text-sm font-semibold text-slate-800">{{ $judul }}</h2>

                @forelse ($data as $baris)
                    <div class="mb-3">
                        <div class="mb-1 flex justify-between text-sm">
                            <span class="text-slate-600">{{ $baris['label'] }}</span>
                            <span class="font-medium text-slate-800">{{ $baris['jumlah'] }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-100">
                            <div class="h-2 rounded-full"
                                 style="width: {{ round($baris['jumlah'] / $maks * 100) }}%; background-color: {{ $baris['warna'] }}"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada data.</p>
                @endforelse
            </div>
        @endforeach
    </div>
</div>
@endsection
