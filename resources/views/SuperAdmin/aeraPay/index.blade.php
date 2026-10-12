@extends('template.layout')

@section('content')
@php
    $field = 'h-10 px-3 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
@endphp

<div class="p-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        {{-- Bagian atas kartu + filter --}}
        <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">Pantau seluruh transaksi saldo AERA Pay pengguna.</p>

            <form method="GET" action="{{ route('super_admin.aeraPay.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="jenis_transaksi" class="{{ $field }}">
                    <option value="">Semua Jenis</option>
                    @foreach (['reward' => 'Reward', 'redeem' => 'Redeem', 'admin_adjust' => 'Penyesuaian Admin'] as $nilai => $teks)
                        <option value="{{ $nilai }}" @selected(request('jenis_transaksi') === $nilai)>{{ $teks }}</option>
                    @endforeach
                </select>

                <select name="status" class="{{ $field }}">
                    <option value="">Semua Status</option>
                    @foreach (['berhasil' => 'Berhasil', 'pending' => 'Pending', 'gagal' => 'Gagal'] as $nilai => $teks)
                        <option value="{{ $nilai }}" @selected(request('status') === $nilai)>{{ $teks }}</option>
                    @endforeach
                </select>

                <button type="submit" class="h-10 px-4 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Filter</button>

                @if (request()->hasAny(['jenis_transaksi', 'status', 'user_id']))
                    <a href="{{ route('super_admin.aeraPay.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">No</th>
                        <th class="px-4 py-3.5 font-semibold">Pengguna</th>
                        <th class="px-4 py-3.5 font-semibold">Laporan</th>
                        <th class="px-4 py-3.5 font-semibold">Jenis</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Nominal</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Saldo Sebelum</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Saldo Sesudah</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($transaksis as $v)
                        @php $selisih = $v->saldo_sesudah - $v->saldo_sebelum; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">{{ $transaksis->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3.5 font-semibold">{{ $v->user->name ?? $v->user->nama ?? '-' }}</td>
                            <td class="px-4 py-3.5">{{ $v->laporan->judul ?? '-' }}</td>
                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold capitalize',
                                    'bg-hijau/10 text-hijau' => $v->jenis_transaksi === 'reward',
                                    'bg-oranye/10 text-oranye' => $v->jenis_transaksi === 'redeem',
                                    'bg-cyan-3/20 text-cyan-6' => $v->jenis_transaksi === 'admin_adjust',
                                ])>
                                    {{ str_replace('_', ' ', $v->jenis_transaksi) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right font-semibold whitespace-nowrap {{ $selisih >= 0 ? 'text-hijau' : 'text-merah' }}">
                                {{ $selisih >= 0 ? '+' : '-' }}Rp{{ number_format(abs($v->nominal), 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">Rp{{ number_format($v->saldo_sebelum, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">Rp{{ number_format($v->saldo_sesudah, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold capitalize',
                                    'bg-hijau/10 text-hijau' => $v->status === 'berhasil',
                                    'bg-kuning/20 text-abu-tua' => $v->status === 'pending',
                                    'bg-merah/10 text-merah' => $v->status === 'gagal',
                                ])>
                                    {{ $v->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <a href="{{ route('super_admin.aeraPay.show', $v->id) }}"
                                   class="text-sm font-semibold text-cyan-4 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-400">Belum ada transaksi AERA Pay.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transaksis->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection