@extends('template.layout')

@section('content')
@php $selisih = $aeraPayTransaksi->saldo_sesudah - $aeraPayTransaksi->saldo_sebelum; @endphp

<div class="p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-5">
            <p class="text-sm text-gray-600">Detail transaksi AERA Pay.</p>
        </div>

        <div class="px-6 pb-6 space-y-5 text-sm text-gray-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs text-gray-500">ID Transaksi</p>
                    <p class="font-mono">#{{ $aeraPayTransaksi->id }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Pengguna</p>
                    <p class="font-semibold text-cyan-6">
                        {{ $aeraPayTransaksi->user->name ?? $aeraPayTransaksi->user->nama ?? $aeraPayTransaksi->user_id }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Laporan Terkait</p>
                    @if ($aeraPayTransaksi->laporan)
                        <a href="{{ route('super_admin.laporan.show', $aeraPayTransaksi->laporan->id) }}"
                           class="font-semibold text-cyan-4 hover:underline">{{ $aeraPayTransaksi->laporan->judul }}</a>
                    @else
                        <p>-</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Jenis Transaksi</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold capitalize bg-cyan-3/20 text-cyan-6">
                        {{ str_replace('_', ' ', $aeraPayTransaksi->jenis_transaksi) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 rounded-lg border border-abu-muda bg-gray-50 p-4">
                <div>
                    <p class="text-xs text-gray-500">Saldo Sebelum</p>
                    <p class="font-semibold">Rp{{ number_format($aeraPayTransaksi->saldo_sebelum, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Nominal</p>
                    <p class="font-semibold {{ $selisih >= 0 ? 'text-hijau' : 'text-merah' }}">
                        {{ $selisih >= 0 ? '+' : '-' }}Rp{{ number_format(abs($aeraPayTransaksi->nominal), 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Saldo Sesudah</p>
                    <p class="font-semibold">Rp{{ number_format($aeraPayTransaksi->saldo_sesudah, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-10">
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Status</p>
                    <span @class([
                        'inline-block px-3 py-1 rounded-full text-xs font-semibold capitalize',
                        'bg-hijau/10 text-hijau' => $aeraPayTransaksi->status === 'berhasil',
                        'bg-kuning/20 text-abu-tua' => $aeraPayTransaksi->status === 'pending',
                        'bg-merah/10 text-merah' => $aeraPayTransaksi->status === 'gagal',
                    ])>{{ $aeraPayTransaksi->status }}</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Dibuat Pada</p>
                    <p>{{ $aeraPayTransaksi->created_at?->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>

            <a href="{{ route('super_admin.aeraPay.index') }}" class="inline-block text-sm text-gray-500 hover:underline">← Kembali</a>
        </div>
    </div>
</div>
@endsection