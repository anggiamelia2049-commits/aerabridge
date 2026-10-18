@extends('template.layout')

@section('title', 'Dashboard')

@section('content')
@php
    $cards = [
        [
            'label' => 'Total Aduan',
            'value' => $stats['total'],
            'note'  => 'Bulan ini: +' . number_format($stats['bulan_ini'], 0, ',', '.'),
            'warna' => 'bg-[#E6F1FB] text-[#185FA5]',
            'icon'  => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
        ],
        [
            'label' => 'Belum Direspon',
            'value' => $stats['belum_direspon'],
            'note'  => 'Direspon: ' . number_format($stats['direspon'], 0, ',', '.'),
            'warna' => 'bg-[#FCEBEB] text-[#A32D2D]',
            'icon'  => '<path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2z"/><path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"/>',
        ],
        [
            'label' => 'Dalam Progress',
            'value' => $stats['dalam_progress'],
            'note'  => 'Perbaikan Infrastruktur',
            'warna' => 'bg-[#FAEEDA] text-[#854F0B]',
            'icon'  => '<circle cx="12" cy="12" r="9" stroke-dasharray="2 3"/><path d="M12 7v5l3 2"/>',
        ],
        [
            'label' => 'Selesai',
            'value' => $stats['selesai'],
            'note'  => 'Data Penyelesaian',
            'warna' => 'bg-[#EAF3DE] text-[#3B6D11]',
            'icon'  => '<path d="M20 6L9 17l-5-5"/>',
        ],
    ];

    // [teks, kelas] sesuai warna pada desain Figma
    $badge = [
        'Kritis' => ['Kritis', 'bg-[#ffa8a8] text-gray-900'],
        'Sedang' => ['Sedang', 'bg-[#ff8c1a] text-white'],
        'Rendah' => ['Rendah', 'bg-[#ffcc00] text-gray-900'],
    ];
@endphp

{{-- ===== Kartu Statistik ===== --}}
<section class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach ($cards as $c)
        <div class="bg-white rounded-2xl shadow-md px-4 py-4 flex items-center gap-3">
            {{-- Ikon di kiri (berwarna sesuai status) --}}
            <span class="w-14 h-14 shrink-0 rounded-full {{ $c['warna'] }} flex items-center justify-center">
                <svg fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="width:26px;height:26px;">
                    {!! $c['icon'] !!}
                </svg>
            </span>

            {{-- Teks di kanan --}}
            <div class="flex flex-col leading-tight min-w-0">
                <div class="text-sm font-extrabold text-black uppercase truncate">{{ $c['label'] }}</div>
                <div class="text-2xl font-extrabold text-black leading-tight">
                    {{ number_format($c['value'], 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-500 truncate">{{ $c['note'] }}</div>
            </div>
        </div>
    @endforeach
</section>

{{-- ===== Grafik ===== --}}
<section class="bg-white shadow-sm p-5 mb-6">
    <h2 class="text-sm font-bold text-black mb-6">Grafik Tren Pengaduan Bulanan</h2>
    <div class="h-[300px]">
        <canvas id="chartPengaduan" aria-label="Grafik pengaduan per prioritas"></canvas>
    </div>
</section>

{{-- ===== Daftar Laporan Terbaru ===== --}}
<section class="bg-white rounded-2xl shadow-md p-5">
    <h2 class="text-sm font-bold text-black mb-3 px-2">
        Daftar Laporan Terbaru <span class="text-gray-400 font-bold">(Berdasarkan Prioritas)</span>
    </h2>

    <div class="overflow-x-auto max-h-[320px] overflow-y-auto">
        <table class="w-full text-sm text-left border-separate border-spacing-0">
            <thead class="sticky top-0 z-10 text-gray-700 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 bg-gray-200 rounded-l-lg text-center w-14">No.</th>
                    <th class="px-4 py-3 bg-gray-200">ID Laporan</th>
                    <th class="px-4 py-3 bg-gray-200">Kategori</th>
                    <th class="px-4 py-3 bg-gray-200">Unit Kerja</th>
                    <th class="px-4 py-3 bg-gray-200">Tanggal</th>
                    <th class="px-4 py-3 bg-gray-200 rounded-r-lg text-center">Tingkat Prioritas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporanTerbaru as $laporan)
                    @php
                        [$teks, $kelas] = $badge[$laporan->tingkat_prioritas] ?? ['-', 'bg-gray-200 text-gray-700'];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors border-b border-gray-200">
                        <td class="px-4 py-3 text-center text-gray-600">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800 whitespace-nowrap">
                            {{ $laporan->kode_lacak ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $laporan->kategori->nama_kategori ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $laporan->instansi->nama_instansi ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $laporan->created_at->translatedFormat('d F Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block min-w-[88px] rounded-lg px-4 py-1.5 text-xs font-semibold {{ $kelas }}">
                                {{ $teks }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                            Belum ada laporan yang perlu ditangani.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    const dataChart = @json($chart);
    const warna = ['#ff5f64', '#fba04f', '#fdd52f'];

    // Skala Y dinamis (kelipatan 10, 5 langkah) agar batang tetap terlihat
    // saat data masih kecil. Kalau ingin tetap 0-100 seperti Figma, ganti jadi: const yMax = 100;
    const nilaiMax = Math.max(...dataChart.data, 0);
    const yMax = Math.max(10, Math.ceil(nilaiMax / 10) * 10);

    // Latar abu-abu (track) di belakang tiap batang, seperti pada desain
    const trackPlugin = {
        id: 'track',
        beforeDatasetsDraw(chart) {
            const { ctx, scales: { y } } = chart;
            const meta = chart.getDatasetMeta(0);
            const top = y.getPixelForValue(y.max);
            const bottom = y.getPixelForValue(0);
            ctx.save();
            ctx.fillStyle = '#f1f2f7';
            meta.data.forEach(bar => {
                ctx.fillRect(bar.x - bar.width / 2, top, bar.width, bottom - top);
            });
            ctx.restore();
        }
    };

    new Chart(document.getElementById('chartPengaduan'), {
        type: 'bar',
        data: {
            labels: dataChart.labels,
            datasets: [{
                data: dataChart.data,
                backgroundColor: warna,
                barPercentage: 1,
                categoryPercentage: 0.6,
            }]
        },
        plugins: [trackPlugin],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        generateLabels: () => dataChart.labels.map((l, i) => ({
                            text: l,
                            fillStyle: warna[i],
                            strokeStyle: warna[i],
                            pointStyle: 'rect',
                        })),
                        usePointStyle: true,
                        boxWidth: 8,
                        boxHeight: 8,
                        padding: 20,
                        font: { size: 11 },
                    }
                },
                tooltip: {
                    callbacks: {
                        label: c => c.parsed.y + ' aduan'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: yMax,
                    ticks: { stepSize: yMax / 5, color: '#6b7280', font: { size: 11 } },
                    grid: { color: '#e5e7eb', borderDash: [3, 3] },
                    border: { display: false }
                },
                x: {
                    // Label kategori disembunyikan, nama ada di legenda (sesuai Figma)
                    ticks: { display: false },
                    grid: { color: '#e5e7eb', borderDash: [3, 3] },
                    border: { color: '#9ca3af' }
                }
            }
        }
    });
</script>
@endpush
