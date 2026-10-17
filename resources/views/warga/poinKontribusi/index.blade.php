@extends('template.layout')

@section('title', 'Poin Kontribusi')

@section('content')
@php
    $labelAktivitas = [
        'laporan_terverifikasi'  => 'Laporan terverifikasi',
        'laporan_kritis_selesai' => 'Bonus laporan kritis selesai',
        'baca_edukasi'           => 'Membaca edukasi',
        'redeem_aera_pay'        => 'Konversi ke AERA Pay',
    ];
@endphp

<div class="p-6 space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-xs text-gray-500">Total Poin Saya</p>
            <p class="mt-1 text-3xl font-bold text-cyan-6">
                {{ number_format($totalPoin, 0, ',', '.') }}
                <span class="text-sm font-semibold text-gray-500">poin</span>
            </p>
            <p class="mt-2 text-xs text-gray-500">1.000 poin = Rp500 saldo simulasi</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 flex flex-col justify-between">
            <div>
                <p class="text-xs text-gray-500">Cara mendapat poin</p>
                <ul class="mt-1 text-sm text-gray-700 space-y-0.5">
                    <li>+50 laporan terverifikasi</li>
                    <li>+30 laporan kritis selesai</li>
                    <li>+10 menyelesaikan konten edukasi</li>
                </ul>
            </div>
            <a href="{{ route('warga.aeraPay.create') }}"
               class="mt-3 self-start px-5 py-2 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                Konversi Poin
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-5">
            <p class="text-sm text-gray-600">Riwayat poin kontribusi kamu.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">Tanggal</th>
                        <th class="px-4 py-3.5 font-semibold">Aktivitas</th>
                        <th class="px-4 py-3.5 font-semibold">Laporan Terkait</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Poin</th>
                        <th class="px-4 py-3.5 font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($riwayatPoin as $v)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5 whitespace-nowrap">{{ $v->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-4 py-3.5">{{ $labelAktivitas[$v->jenis_aktivitas] ?? ucfirst(str_replace('_', ' ', $v->jenis_aktivitas)) }}</td>
                            <td class="px-4 py-3.5">{{ $v->laporan->judul ?? '-' }}</td>
                            <td class="px-4 py-3.5 text-right font-semibold whitespace-nowrap {{ $v->poin >= 0 ? 'text-hijau' : 'text-merah' }}">
                                {{ $v->poin > 0 ? '+' . $v->poin : $v->poin }}
                            </td>
                            <td class="px-4 py-3.5">{{ $v->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-400">Belum ada riwayat poin kontribusi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection