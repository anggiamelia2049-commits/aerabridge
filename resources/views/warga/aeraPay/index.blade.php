@extends('template.layout')

@section('content')
<div class="p-6 space-y-6">

    {{-- Ringkasan poin & saldo --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-xs text-gray-500">Total Poin Kontribusi</p>
            <p class="mt-1 text-3xl font-bold text-cyan-6">{{ number_format($totalPoin, 0, ',', '.') }}
                <span class="text-sm font-semibold text-gray-500">poin</span>
            </p>
            <p class="mt-2 text-xs text-gray-500">1.000 poin = Rp500 saldo simulasi</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-xs text-gray-500">Saldo AERA Pay</p>
            <p class="mt-1 text-3xl font-bold text-cyan-4">Rp{{ number_format($saldoSaatIni, 0, ',', '.') }}</p>
            <p class="mt-2 text-xs text-gray-500">Saldo simulasi, bukan uang sungguhan.</p>
        </div>
    </div>

    {{-- Riwayat transaksi --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">Riwayat transaksi saldo AERA Pay kamu.</p>
            <a href="{{ route('warga.aeraPay.create') }}"
               class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                Konversi Poin
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">Tanggal</th>
                        <th class="px-4 py-3.5 font-semibold">Jenis</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Nominal</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Saldo Sebelum</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Saldo Sesudah</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($transaksis as $item)
                        @php $selisih = $item->saldo_sesudah - $item->saldo_sebelum; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5 whitespace-nowrap">{{ $item->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                    'bg-hijau/10 text-hijau' => $item->jenis_transaksi === 'reward',
                                    'bg-oranye/10 text-oranye' => $item->jenis_transaksi === 'redeem',
                                    'bg-cyan-3/20 text-cyan-6' => $item->jenis_transaksi === 'admin_adjust',
                                ])>
                                    @switch($item->jenis_transaksi)
                                        @case('reward') Reward @break
                                        @case('redeem') Konversi Poin @break
                                        @default Penyesuaian Admin
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right font-semibold whitespace-nowrap {{ $selisih >= 0 ? 'text-hijau' : 'text-merah' }}">
                                {{ $selisih >= 0 ? '+' : '-' }}Rp{{ number_format(abs($item->nominal), 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">Rp{{ number_format($item->saldo_sebelum, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">Rp{{ number_format($item->saldo_sesudah, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold capitalize',
                                    'bg-hijau/10 text-hijau' => $item->status === 'berhasil',
                                    'bg-kuning/20 text-abu-tua' => $item->status === 'pending',
                                    'bg-merah/10 text-merah' => $item->status === 'gagal',
                                ])>{{ $item->status }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <a href="{{ route('warga.aeraPay.show', $item->id) }}"
                                   class="text-sm font-semibold text-cyan-4 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-400">Belum ada transaksi AERA Pay.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection