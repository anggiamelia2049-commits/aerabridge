@extends('template.layout')

@section('title', 'Dashboard Instansi')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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

    $totalKategori  = max(1, collect($laporanPerKategori)->sum('jumlah'));
    // Urutan warna mengikuti Figma: ungu, salmon, biru muda, lalu cadangan
    $warnaKategori  = ['#8B7CF6', '#FF8A8A', '#38BDF8', '#FDBA4D', '#5CC8B5', '#F472B6'];
@endphp

{{-- ==================== SAMBUTAN ==================== --}}
<div class="mb-5">
    <h1 class="text-h1">Selamat Datang, {{ $namaInstansi }} <span class="inline-block">👋</span></h1>
    <p class="mt-1 text-subtitle">Kelola laporan dan pantau penanganan kerusakan infrastruktur</p>
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
        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
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

{{-- ==================== BARIS 2: PETA + LAPORAN PRIORITAS TINGGI ==================== --}}
<div class="mb-5 grid grid-cols-1 gap-5 lg:grid-cols-[1.75fr_1fr]">

    {{-- Peta Sebaran Kerusakan --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <h2 class="mb-3 text-sm font-bold text-cyan-6">Peta sebaran kerusakan</h2>

        <div class="relative isolate">
            <div id="peta-instansi" class="h-72 w-full rounded-xl"></div>

            {{-- Legenda Prioritas --}}
            <div class="absolute bottom-3 left-3 z-[1000] rounded-lg bg-white p-3 text-xs text-cyan-6 shadow">
                <p class="mb-2 font-semibold">Prioritas</p>
                <p class="mb-1 flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-red-500"></span>Kritis</p>
                <p class="mb-1 flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-orange-400"></span>Sedang</p>
                <p class="flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-yellow-400"></span>Rendah</p>
            </div>
        </div>
    </div>

    {{-- Laporan Prioritas Tinggi --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold text-cyan-6">Laporan Prioritas Tinggi</h2>
            <a href="{{ route('instansi.laporan.index') }}" class="text-xs font-medium text-blue-500 hover:text-blue-700">Lihat Semua</a>
        </div>

        <div class="space-y-3">
            @forelse ($laporanPrioritas->take(3) as $l)
                @php
                    // Sesuaikan nama kolom foto bila berbeda (mis. $l->foto)
                    $foto = $l->foto_url ?? null;
                    $badgePrioritas = match (strtolower($l->tingkat_prioritas ?? 'rendah')) {
                        'kritis', 'tinggi' => ['Kritis', 'bg-red-100 text-red-500'],
                        'sedang'           => ['Sedang', 'bg-orange-100 text-orange-500'],
                        default            => ['Rendah', 'bg-yellow-100 text-yellow-600'],
                    };
                @endphp
                <div class="flex items-center gap-3 rounded-xl bg-gray-50 p-2">
                    <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gray-200 text-xl">
                        @if ($foto)
                            <img src="{{ $foto }}" alt="" class="h-full w-full object-cover">
                        @else
                            🚧
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-semibold text-cyan-6">{{ $l->judul ?? '-' }}</p>
                        <p class="truncate text-[11px] text-abu-tua">{{ $l->alamat ?? '-' }}</p>
                        <p class="text-[10px] text-abu-tua">Dilaporkan {{ optional($l->created_at)->translatedFormat('d F Y, H:i') }}</p>
                    </div>

                    <span class="flex-shrink-0 rounded px-2 py-0.5 text-[10px] font-medium {{ $badgePrioritas[1] }}">{{ $badgePrioritas[0] }}</span>
                </div>
            @empty
                <p class="py-6 text-center text-xs text-abu-tua">Belum ada laporan prioritas tinggi.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ==================== BARIS 3: GRAFIK + PENUGASAN TERBARU ==================== --}}
<div class="grid grid-cols-1 gap-5 lg:grid-cols-[2.2fr_1.3fr_1.5fr]">

    {{-- Laporan per Bulan --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold text-cyan-6">Laporan per Bulan</h2>
            <form method="GET">
                <select name="rentang" onchange="this.form.submit()"
                        class="rounded-md border border-gray-300 py-1 pl-2 pr-6 text-[11px] text-abu-tua">
                    <option value="6"  @selected(request('rentang', 6) == 6)>6 Bulan Terakhir</option>
                    <option value="12" @selected(request('rentang') == 12)>12 Bulan Terakhir</option>
                </select>
            </form>
        </div>
        <div class="h-48">
            <canvas id="chartBulanan"></canvas>
        </div>
    </div>

    {{-- Laporan per Kategori --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <h2 class="mb-3 text-sm font-bold text-cyan-6">Laporan per Kategori</h2>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <div class="h-28 w-28 flex-shrink-0">
                <canvas id="chartKategori"></canvas>
            </div>
            <ul class="space-y-2 text-[11px]">
                @foreach ($laporanPerKategori as $i => $k)
                    @php
                        $nama   = is_array($k) ? $k['nama'] : $k->nama;
                        $jumlah = is_array($k) ? $k['jumlah'] : $k->jumlah;
                        $persen = number_format($jumlah / $totalKategori * 100, 1, ',', '.');
                    @endphp
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-2.5 w-2.5 flex-shrink-0 rounded-full" style="background-color: {{ $warnaKategori[$i % count($warnaKategori)] }}"></span>
                        <span>
                            <span class="block font-semibold text-cyan-6">{{ $nama }}</span>
                            <span class="text-abu-tua">{{ $jumlah }} ({{ $persen }}%)</span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Penugasan Terbaru --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold text-cyan-6">Penugasan Terbaru</h2>
            <a href="{{ route('instansi.penugasan.index') }}" class="text-xs font-medium text-blue-500 hover:text-blue-700">Lihat Semua</a>
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
                        <p class="truncate text-[10px] text-abu-tua">📍 {{ $p->laporan->alamat ?? '-' }}</p>
                        <p class="truncate text-[10px] text-abu-tua">Ditugaskan ke {{ $p->petugas->nama ?? $p->timSatgas->nama_tim ?? '-' }}</p>
                    </div>
                </div>
            @empty
                <p class="py-6 text-center text-xs text-abu-tua">Belum ada penugasan.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ==================== SCRIPT ==================== --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<script>
    // Plugin kecil: tampilkan angka di atas tiap titik grafik garis (seperti di Figma)
    const labelNilai = {
        id: 'labelNilai',
        afterDatasetsDraw(chart) {
            const ctx = chart.ctx;
            ctx.save();
            ctx.font = '10px sans-serif';
            ctx.fillStyle = '#4A4A4A';
            ctx.textAlign = 'center';
            chart.getDatasetMeta(0).data.forEach((titik, i) => {
                ctx.fillText(chart.data.datasets[0].data[i], titik.x, titik.y - 8);
            });
            ctx.restore();
        }
    };

    new Chart(document.getElementById('chartBulanan'), {
        type: 'line',
        data: {
            labels: @json($laporanPerBulan['labels']),
            datasets: [{
                data: @json($laporanPerBulan['data']),
                borderColor: '#5CC8B5',
                backgroundColor: 'rgba(92, 200, 181, 0.15)',
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
            layout: { padding: { top: 16 } },
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 50, font: { size: 10 } }, grid: { color: '#eee' } },
                x: { ticks: { font: { size: 10 } }, grid: { display: false } }
            }
        },
        plugins: [labelNilai]
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Peta dengan titik awal Kota Depok; tombol zoom dipindah ke kanan bawah (sesuai Figma)
        const peta = L.map('peta-instansi', { zoomControl: false }).setView([-6.4025, 106.7942], 13);
        L.control.zoom({ position: 'bottomright' }).addTo(peta);

        // 2. OpenStreetMap sebagai tampilan peta
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(peta);

        // 3. Data titik laporan dari Laravel
        const titikPeta = @json(collect($titikPeta ?? [])->values());
        const semuaTitik = [];

        // 4. Marker untuk setiap laporan
        titikPeta.forEach(function (titik) {
            const latitude  = Number(titik.latitude ?? titik.lat);
            const longitude = Number(titik.longitude ?? titik.lng ?? titik.long);

            // Lewati laporan tanpa koordinat valid
            if (!Number.isFinite(latitude) || !Number.isFinite(longitude) ||
                Math.abs(latitude) > 90 || Math.abs(longitude) > 180) {
                return;
            }

            const prioritas = String(titik.tingkat_prioritas ?? 'rendah').toLowerCase();
            let warna = '#EAB308';
            if (prioritas === 'kritis' || prioritas === 'tinggi') warna = '#EF4444';
            else if (prioritas === 'sedang') warna = '#F97316';

            const marker = L.circleMarker([latitude, longitude], {
                radius: 8, color: warna, fillColor: warna, fillOpacity: 0.85, weight: 2
            }).addTo(peta);

            // 5. Popup (textContent agar aman dari XSS)
            const isiPopup = document.createElement('div');
            const judul = document.createElement('strong');
            judul.textContent = titik.judul ?? 'Laporan kerusakan';
            const alamat = document.createElement('p');
            alamat.textContent = 'Alamat: ' + (titik.alamat ?? '-');
            const labelPrioritas = document.createElement('p');
            labelPrioritas.textContent = 'Prioritas: ' + prioritas;
            isiPopup.append(judul, alamat, labelPrioritas);
            marker.bindPopup(isiPopup);

            semuaTitik.push([latitude, longitude]);
        });

        // 6. Sesuaikan tampilan ke seluruh titik
        if (semuaTitik.length > 0) {
            peta.fitBounds(semuaTitik, { padding: [25, 25], maxZoom: 15 });
        }

        // Perbaiki ukuran peta saat ditampilkan
        setTimeout(function () { peta.invalidateSize(); }, 200);
    });
</script>

@endsection
