@extends('template.layout')

@section('title', 'Dashboard')

@section('content')
@php
    $cards = [
        [
            'label' => 'Total Aduan',
            'value' => $stats['total'],
            'note'  => 'Bulan ini: +' . number_format($stats['bulan_ini'], 0, ',', '.'),
            'icon'  => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
        ],
        [
            'label' => 'Belum Direspon',
            'value' => $stats['belum_direspon'],
            'note'  => 'Direspon: ' . number_format($stats['direspon'], 0, ',', '.'),
            'icon'  => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>',
        ],
        [
            'label' => 'Dalam Progress',
            'value' => $stats['dalam_progress'],
            'note'  => 'Perbaikan Infrastruktur',
            'icon'  => '<path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>',
        ],
        [
            'label' => 'Selesai',
            'value' => $stats['selesai'],
            'note'  => 'Data Penyelesaian',
            'icon'  => '<path d="M20 6L9 17l-5-5"/>',
        ],
    ];

    $badge = [
        'Krisis' => ['Krisis', 'bg-[#f45b5b] text-white'],
        'Sedang' => ['Sedang', 'bg-[#f99d3a] text-white'],
        'Rendah' => ['Rendah', 'bg-[#f9d63a] text-gray-900'],
    ];
@endphp

{{-- ===== Kartu Statistik (Horizontal & Compact) ===== --}}
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @foreach ($cards as $c)
        <div class="bg-white rounded-lg shadow-sm px-3 py-2.5 flex items-center gap-2.5">
            {{-- Ikon di kiri (lebih kecil) --}}
            <span class="w-9 h-9 shrink-0 rounded-full bg-gray-100 flex items-center justify-center text-gray-700">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="width:18px;height:18px;">
                    {!! $c['icon'] !!}
                </svg>
            </span>

            {{-- Teks di kanan --}}
            <div class="flex flex-col leading-tight min-w-0">
                <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wide truncate">{{ $c['label'] }}</div>
                <div class="text-lg font-extrabold text-gray-800 leading-none mt-0.5">
                    {{ number_format($c['value'], 0, ',', '.') }}
                </div>
                <div class="text-[10px] text-gray-400 mt-0.5 truncate">{{ $c['note'] }}</div>
            </div>
        </div>
    @endforeach
</section>

{{-- ===== Grafik ===== --}}
<section class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h2 class="text-base font-bold text-gray-800 mb-6">Grafik Tren Pengaduan Bulanan</h2>
    <div class="h-[300px]">
        <canvas id="chartPengaduan" aria-label="Grafik pengaduan per prioritas"></canvas>
    </div>
</section>

{{-- ===== Daftar Laporan Terbaru ===== --}}
<section class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-base font-bold text-gray-800 mb-4">
        Daftar Laporan Terbaru <span class="text-gray-400 font-normal">(Berdasarkan Prioritas)</span>
    </h2>

    <div class="overflow-x-auto max-h-[320px] overflow-y-auto">
        <table class="w-full text-sm text-left">
            <thead class="sticky top-0 bg-gray-100 text-gray-600 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 rounded-l-md">Judul Laporan</th>
                    <th class="px-4 py-3">Lokasi</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3 rounded-r-md text-center">Prioritas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($laporanTerbaru as $laporan)
                    @php
                        [$teks, $kelas] = $badge[$laporan->tingkat_prioritas] ?? ['-', 'bg-gray-200 text-gray-700'];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $laporan->judul }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $laporan->lokasi }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $laporan->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block min-w-[64px] rounded-md px-3 py-1 text-xs font-semibold {{ $kelas }}">
                                {{ $teks }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">
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
    const warna = ['#f45b5b', '#f99d3a', '#f9d63a'];

    new Chart(document.getElementById('chartPengaduan'), {
        type: 'bar',
        data: {
            labels: dataChart.labels,
            datasets: [{
                data: dataChart.data,
                backgroundColor: warna,
                barPercentage: 0.8,
                categoryPercentage: 0.6,
            }]
        },
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
                        padding: 20,
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
                    grid: { borderDash: [3, 3], color: '#e5e7eb' },
                    ticks: { color: '#9ca3af', font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#9ca3af', font: { size: 11 } }
                }
            }
        }
    });
</script>
@endpush