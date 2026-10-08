@extends('template.layout')

@section('title', 'Dashboard Instansi')

@section('content')

@php
    // ---- Fallback aman bila controller belum mengirim variabel baru ----
    $namaInstansi        = $namaInstansi ?? (auth()->user()->nama ?? 'Instansi');
    $laporanPrioritas    = $laporanPrioritas ?? collect();
    $penugasanTerbaru    = $penugasanTerbaru ?? ($penugasanAktif ?? collect());
    $laporanPerBulan     = $laporanPerBulan ?? ['labels' => [], 'data' => []];
    $laporanPerKategori  = $laporanPerKategori ?? collect();   // [['nama' => '', 'jumlah' => 0], ...]
    $totalLaporan        = $totalLaporan ?? array_sum($statistik ?? []);
    $selesaiBulanIni     = $selesaiBulanIni ?? ($statistik['selesai'] ?? 0);
    $menungguVerifikasi  = $statistik['menunggu'] ?? 0;
    $sedangDitangani     = ($statistik['diverifikasi'] ?? 0) + ($statistik['diproses'] ?? 0);

    $totalKategori   = max(1, collect($laporanPerKategori)->sum('jumlah'));
    $warnaKategori   = ['#8B7CF6', '#FF8A8A', '#FDBA4D', '#38BDF8', '#5CC8B5', '#F472B6'];
@endphp

{{-- ==================== SAMBUTAN ==================== --}}
<div class="mb-5">
    <h1 class="text-3xl font-bold text-cyan-6">Selamat Datang, {{ $namaInstansi }} <span class="inline-block">👋</span></h1>
    <p class="mt-1 text-base text-cyan-6">Kelola laporan dan pantau penanganan kerusakan infrastruktur</p>
</div>

{{-- ==================== KARTU STATISTIK ==================== --}}
<div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    {{-- Total Laporan --}}
    <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow">
        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6M9 8h2M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>
        </div>
        <div>
            <p class="text-xs text-abu-tua">Total Laporan</p>
            <p class="text-xl font-bold leading-tight text-cyan-6">{{ $totalLaporan }}</p>
            <p class="text-xs font-medium text-cyan-6">Semua laporan masuk</p>
        </div>
    </div>

    {{-- Menunggu Verifikasi --}}
    <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow">
        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 text-blue-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 2"/></svg>
        </div>
        <div>
            <p class="text-xs text-abu-tua">Menunggu Verifikasi</p>
            <p class="text-xl font-bold leading-tight text-cyan-6">{{ $menungguVerifikasi }}</p>
            <p class="text-xs font-medium text-cyan-6">Perlu verifikasi cepat</p>
        </div>
    </div>

    {{-- Sedang Ditangani --}}
    <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow">
        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 3 3 5-6"/></svg>
        </div>
        <div>
            <p class="text-xs text-abu-tua">Sedang Ditangani</p>
            <p class="text-xl font-bold leading-tight text-cyan-6">{{ $sedangDitangani }}</p>
            <p class="text-xs font-medium text-cyan-6">Dalam proses</p>
        </div>
    </div>

    {{-- Selesai Bulan Ini --}}
    <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow">
        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-purple-100 text-purple-500">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/></svg>
        </div>
        <div>
            <p class="text-xs text-abu-tua">Selesai Bulan Ini</p>
            <p class="text-xl font-bold leading-tight text-cyan-6">{{ $selesaiBulanIni }}</p>
            <p class="text-xs font-medium text-cyan-6">Telah diselesaikan</p>
        </div>
    </div>
</div>

{{-- ==================== BARIS 2: PETA + PRIORITAS TINGGI ==================== --}}
<div class="mb-5 grid grid-cols-1 gap-5 lg:grid-cols-[1.75fr_1fr]">

    {{-- Peta sebaran kerusakan (placeholder: ganti div peta dengan Leaflet, render $titikPeta sebagai marker) --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <h2 class="mb-3 text-base font-bold text-cyan-6">Peta sebaran kerusakan</h2>

        <div class="relative flex h-64 items-center justify-center rounded-xl border border-dashed border-abu-muda bg-abu-muda/20 text-sm text-abu-tua">
            Peta belum terhubung &middot; {{ ($titikPeta ?? collect())->count() }} titik laporan siap ditampilkan

            {{-- Legenda prioritas --}}
            <div class="absolute bottom-3 left-3 rounded-lg bg-white p-2 text-[10px] text-cyan-6 shadow">
                <p class="mb-1 font-semibold">Prioritas</p>
                <p class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>Kritis</p>
                <p class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-orange-400"></span>Sedang</p>
                <p class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-yellow-400"></span>Rendah</p>
            </div>
        </div>
    </div>

    {{-- Laporan Prioritas Tinggi --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-base font-bold text-cyan-6">Laporan Prioritas Tinggi</h2>
            <a href="{{ route('instansi.laporan.index') }}" class="text-xs font-medium text-cyan-4 hover:text-cyan-6">Lihat Semua</a>
        </div>

        <div class="divide-y divide-gray-200 rounded-xl bg-gray-100 px-3">
            @forelse ($laporanPrioritas as $v)
                @php
                    $badge = match (strtolower($v->tingkat_prioritas)) {
                        'krisis' => ['kritis', 'bg-red-300 text-red-600'],
                        'sedang' => ['sedang', 'bg-orange-200 text-orange-500'],
                        default  => ['rendah', 'bg-yellow-100 text-yellow-600'],
                    };
                @endphp
                <a href="{{ route('instansi.laporan.show', $v->id) }}" class="flex items-center gap-3 py-3">
                    <div class="h-12 w-14 flex-shrink-0 overflow-hidden rounded-md bg-gray-300">
                        @if (!empty($v->foto_url))
                            <img src="{{ $v->foto_url }}" alt="" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-semibold text-cyan-6">{{ $v->judul }}</p>
                        <p class="truncate text-[11px] text-abu-tua">{{ $v->alamat ?? '-' }}</p>
                        <p class="text-[11px] text-abu-tua">Dilaporkan {{ $v->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                    <span class="flex-shrink-0 rounded-md px-2 py-0.5 text-[11px] font-medium {{ $badge[1] }}">{{ $badge[0] }}</span>
                </a>
            @empty
                <p class="py-6 text-center text-xs text-abu-tua">Belum ada laporan prioritas tinggi.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ==================== BARIS 3: GRAFIK + KATEGORI + PENUGASAN TERBARU ==================== --}}
<div class="grid grid-cols-1 gap-5 lg:grid-cols-[1.9fr_1fr_1.1fr]">

    {{-- Laporan per Bulan --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <div class="mb-2 flex items-center justify-between">
            <h2 class="text-sm font-bold text-cyan-6">Laporan per Bulan</h2>
            <span class="rounded-md border border-gray-300 px-2 py-1 text-[11px] text-abu-tua">6 Bulan Terakhir</span>
        </div>
        <div class="h-48">
            <canvas id="chartBulanan"></canvas>
        </div>
    </div>

    {{-- Laporan per Kategori --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <h2 class="mb-2 text-sm font-bold text-cyan-6">Laporan per Kategori</h2>
        <div class="mx-auto h-28 w-28">
            <canvas id="chartKategori"></canvas>
        </div>
        <ul class="mt-3 space-y-2">
            @foreach ($laporanPerKategori as $i => $k)
                <li class="flex items-start gap-2 text-xs">
                    <span class="mt-1 h-2.5 w-2.5 flex-shrink-0 rounded-full" style="background: {{ $warnaKategori[$i % count($warnaKategori)] }}"></span>
                    <div>
                        <p class="font-semibold text-cyan-6">{{ $k['nama'] }}</p>
                        <p class="text-[11px] text-abu-tua">{{ $k['jumlah'] }} ({{ number_format($k['jumlah'] / $totalKategori * 100, 1, ',', '.') }}%)</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Penugasan Terbaru --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold text-cyan-6">Penugasan Terbaru</h2>
            <a href="{{ route('instansi.penugasan.index') }}" class="text-[11px] font-medium text-cyan-4 hover:text-cyan-6">Lihat Semua</a>
        </div>

        <div class="space-y-3">
            @forelse ($penugasanTerbaru->take(3) as $p)
                @php
                    $statusBadge = match ($p->status) {
                        'selesai'    => ['Selesai', 'bg-green-100 text-green-600'],
                        'ditugaskan' => ['Ditugaskan', 'bg-orange-100 text-orange-500'],
                        default      => ['Dikerjakan', 'bg-blue-100 text-blue-500'],
                    };
                @endphp
                <div class="flex items-start gap-2">
                    <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center overflow-hidden rounded-md bg-gray-200 text-lg">
                        @if (!empty($p->petugas->foto_url))
                            <img src="{{ $p->petugas->foto_url }}" alt="" class="h-full w-full object-cover">
                        @else
                            👷
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-1">
                            <p class="truncate text-[11px] font-semibold text-cyan-6">{{ $p->laporan->judul ?? '-' }}</p>
                            <span class="flex-shrink-0 rounded px-1.5 py-0.5 text-[9px] font-medium {{ $statusBadge[1] }}">{{ $statusBadge[0] }}</span>
                        </div>
                        <p class="truncate text-[10px] text-abu-tua">{{ $p->laporan->alamat ?? '-' }}</p>
                        <p class="truncate text-[10px] text-abu-tua">Ditugaskan ke {{ $p->petugas->nama ?? $p->timSatgas->nama_tim ?? '-' }}</p>
                        <p class="text-right text-[9px] text-abu-tua">{{ $p->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                </div>
            @empty
                <p class="py-6 text-center text-xs text-abu-tua">Belum ada penugasan.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ==================== SCRIPT GRAFIK ==================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('chartBulanan'), {
        type: 'line',
        data: {
            labels: @json($laporanPerBulan['labels']),
            datasets: [{
                data: @json($laporanPerBulan['data']),
                borderColor: '#5CC8B5',
                backgroundColor: 'rgba(92, 200, 181, 0.12)',
                fill: true,
                tension: 0.35,
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#5CC8B5',
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { font: { size: 10 } }, grid: { color: '#eee' } },
                x: { ticks: { font: { size: 10 } }, grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('chartKategori'), {
        type: 'doughnut',
        data: {
            labels: @json(collect($laporanPerKategori)->pluck('nama')),
            datasets: [{
                data: @json(collect($laporanPerKategori)->pluck('jumlah')),
                backgroundColor: @json($warnaKategori),
                borderWidth: 0,
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '35%',
            plugins: { legend: { display: false } }
        }
    });
</script>

@endsection
