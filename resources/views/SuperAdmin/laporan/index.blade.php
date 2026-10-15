@extends('template.layout')

@php
    $prioritasClasses = [
        'kritis' => 'bg-merah/10 text-merah',
        'sedang' => 'bg-oranye/10 text-oranye',
        'rendah' => 'bg-kuning/20 text-abu-tua',
    ];

    $statusClasses = [
        'menunggu'         => 'bg-abu-muda text-abu-tua',
        'terverifikasi ai' => 'bg-cyan-3/10 text-cyan-4',
        'diverifikasi'     => 'bg-cyan-3/20 text-cyan-6',
        'diproses'         => 'bg-oranye/10 text-oranye',
        'selesai'          => 'bg-hijau/10 text-hijau',
        'ditolak'          => 'bg-merah/10 text-merah',
    ];
@endphp

@section('title', $judulHalaman)

@section('content')
<div class="p-6">
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-hijau font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">
                Kelola dan pantau seluruh laporan kerusakan infrastruktur dari warga.
                <span class="text-gray-400">({{ $laporan->total() }} laporan)</span>
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">No</th>
                        <th class="px-4 py-3.5 font-semibold whitespace-nowrap">Kode Lacak</th>
                        <th class="px-4 py-3.5 font-semibold">Pelapor</th>
                        <th class="px-4 py-3.5 font-semibold">Kategori</th>
                        <th class="px-4 py-3.5 font-semibold">Judul</th>
                        <th class="px-4 py-3.5 font-semibold">Foto</th>
                        <th class="px-4 py-3.5 font-semibold">Instansi</th>
                        <th class="px-4 py-3.5 font-semibold">Prioritas</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($laporan as $v)
                        @php
                            $prioritasKey = strtolower((string) $v->tingkat_prioritas);
                            $statusKey    = strtolower((string) $v->status);

                            $gambar = $v->foto;
                            if (! $gambar && $v->lampiran
                                && in_array(strtolower(pathinfo($v->lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])) {
                                $gambar = $v->lampiran;
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">{{ $laporan->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3.5 font-mono text-xs whitespace-nowrap">{{ $v->kode_lacak ?? '-' }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">{{ $v->nama_pelapor ?? optional($v->user)->name ?? '-' }}</td>
                            <td class="px-4 py-3.5">
                                {{ optional($v->kategori)->nama_kategori ?? ($v->kategori_lainnya ?: '-') }}
                            </td>
                            <td class="px-4 py-3.5 font-semibold min-w-[180px]">{{ $v->judul }}</td>
                            <td class="px-4 py-3.5">
                                @if ($gambar)
                                    <img src="{{ asset('storage/' . $gambar) }}" alt="Foto laporan"
                                         class="w-14 h-14 object-cover rounded-lg border border-abu-muda">
                                @else
                                    <div class="w-14 h-14 rounded-lg bg-gray-100 border border-abu-muda flex items-center justify-center text-[10px] text-gray-400">Tidak ada</div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">{{ optional($v->instansi)->nama_instansi ?? '-' }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold capitalize {{ $prioritasClasses[$prioritasKey] ?? 'bg-gray-200 text-gray-600' }}">
                                    {{ $v->tingkat_prioritas ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $statusClasses[$statusKey] ?? 'bg-gray-200 text-gray-600' }}">
                                    {{ $v->status ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-4 text-sm font-semibold whitespace-nowrap">
                                    <a href="{{ route('super_admin.laporan.show', $v->id) }}" class="text-cyan-4 hover:underline">Detail</a>
                                    <a href="{{ route('super_admin.laporan.edit', $v->id) }}" class="text-oranye hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('super_admin.laporan.destroy', $v->id) }}"
                                          onsubmit="return confirm('Hapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-merah hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-10 text-center text-gray-400">
                                {{ request()->filled('status') ? 'Tidak ada laporan pada kategori ini.' : 'Belum ada laporan.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($laporan->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $laporan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection