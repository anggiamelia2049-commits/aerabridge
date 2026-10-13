@extends('template.layout')

@section('title', 'Data Kategori')

@section('content')

@php
    // Durasi SLA sesuai proposal (bisa diganti dengan data dari tabel sla_konfigurasi bila dikirim controller)
    $sla = $sla ?? [
        ['label' => 'Kritis', 'durasi' => '12 jam',  'badge' => 'bg-red-100 text-red-600',       'dot' => 'bg-red-500'],
        ['label' => 'Sedang', 'durasi' => '3 hari',  'badge' => 'bg-orange-100 text-orange-500', 'dot' => 'bg-orange-400'],
        ['label' => 'Rendah', 'durasi' => '7 hari',  'badge' => 'bg-yellow-100 text-yellow-600', 'dot' => 'bg-yellow-400'],
    ];
@endphp

<div class="mb-5">
    <h1 class="text-3xl font-bold text-gray-900">Data Kategori</h1>
    <p class="mt-1 text-base text-gray-900">Kategori kerusakan infrastruktur yang dapat dilaporkan dan dideteksi oleh sistem AI</p>
</div>

{{-- ==================== DAFTAR KATEGORI ==================== --}}
<div class="mb-5 rounded-2xl bg-white p-5 shadow">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-sm font-bold text-cyan-6">Kategori Kerusakan</h2>
        <p class="text-xs text-abu-tua">Total: {{ $kategori->total() }} kategori</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-xs text-abu-tua">
                    <th class="w-16 py-3 pr-3 font-medium">No</th>
                    <th class="py-3 pr-3 font-medium">Nama Kategori</th>
                    <th class="py-3 pr-3 font-medium">Deskripsi</th>
                    <th class="py-3 text-right font-medium">Jumlah Laporan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategori as $item)
                    @php
                        $nama = strtolower($item->nama_kategori ?? '');
                        // Bila kolom deskripsi kosong, pakai keterangan dari proposal
                        $deskripsi = $item->deskripsi ?? match (true) {
                            str_contains($nama, 'jalan') || str_contains($nama, 'lubang')
                                => 'Lubang atau kerusakan pada permukaan jalan (pothole).',
                            str_contains($nama, 'air') || str_contains($nama, 'drainase') || str_contains($nama, 'saluran')
                                => 'Saluran air atau drainase yang tersumbat.',
                            default
                                => 'Kerusakan fasilitas pendukung jalan, seperti lampu dan pembatas jalan.',
                        };
                    @endphp
                    <tr class="border-b border-gray-100 last:border-0">
                        <td class="py-3 pr-3 text-abu-tua">{{ $kategori->firstItem() + $loop->index }}</td>
                        <td class="py-3 pr-3 font-semibold text-cyan-6">{{ $item->nama_kategori }}</td>
                        <td class="py-3 pr-3 text-abu-tua">{{ $deskripsi }}</td>
                        <td class="py-3 text-right font-semibold text-cyan-6">{{ $item->laporan_count ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-xs text-abu-tua">Belum ada data kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($kategori->hasPages())
        <div class="mt-4">{{ $kategori->links() }}</div>
    @endif
</div>

{{-- ==================== SLA + CAKUPAN AI ==================== --}}
<div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

    {{-- Target waktu penyelesaian (SLA) per prioritas AI --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <h2 class="mb-1 text-sm font-bold text-cyan-6">Target Waktu Penyelesaian (SLA)</h2>
        <p class="mb-3 text-xs text-abu-tua">Durasi penyelesaian ditentukan otomatis berdasarkan tingkat prioritas dari AI.</p>
        <div class="space-y-2">
            @foreach ($sla as $s)
                <div class="flex items-center justify-between rounded-xl bg-gray-50 px-3 py-2">
                    <span class="flex items-center gap-2 text-sm text-cyan-6">
                        <span class="h-2.5 w-2.5 rounded-full {{ $s['dot'] }}"></span>Prioritas {{ $s['label'] }}
                    </span>
                    <span class="rounded px-2 py-0.5 text-xs font-medium {{ $s['badge'] }}">{{ $s['durasi'] }}</span>
                </div>
            @endforeach
        </div>
        <p class="mt-3 text-[11px] text-abu-tua">Indikator merah (Overdue) tampil bila batas waktu terlampaui.</p>
    </div>

    {{-- Cakupan deteksi AI --}}
    <div class="rounded-2xl bg-white p-5 shadow">
        <h2 class="mb-1 text-sm font-bold text-cyan-6">Cakupan Deteksi AI</h2>
        <p class="mb-3 text-xs text-abu-tua">Model YOLO dibatasi untuk mendeteksi tiga kategori kerusakan. Hasilnya dianalisis LLM untuk rekomendasi prioritas.</p>
        <ul class="space-y-2 text-sm text-cyan-6">
            <li class="rounded-xl bg-gray-50 px-3 py-2">Jalan berlubang (<em>pothole</em>)</li>
            <li class="rounded-xl bg-gray-50 px-3 py-2">Saluran air / drainase tersumbat</li>
            <li class="rounded-xl bg-gray-50 px-3 py-2">Kerusakan fasilitas pendukung jalan (lampu dan pembatas jalan)</li>
        </ul>
        <p class="mt-3 text-[11px] text-abu-tua">Penambahan atau perubahan kategori dikelola oleh Super Admin.</p>
    </div>
</div>

@endsection
