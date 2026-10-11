@extends('template.layout')

@section('content')
    <div class="p-6">

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-hijau font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            {{-- Bagian atas kartu --}}
            <div class="flex items-center justify-between gap-4 px-6 py-5">
                <p class="text-sm text-gray-600">
                    Pantau status laporan kerusakan infrastruktur yang sudah kamu kirim.
                </p>
                <a href="{{ route('warga.laporan.create') }}"
                   class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                    + Buat Laporan
                </a>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-cyan-6 text-white">
                        <tr>
                            <th class="px-4 py-3.5 font-semibold">Judul</th>
                            <th class="px-4 py-3.5 font-semibold">Kategori</th>
                            <th class="px-4 py-3.5 font-semibold">Instansi</th>
                            <th class="px-4 py-3.5 font-semibold">Prioritas</th>
                            <th class="px-4 py-3.5 font-semibold">Status</th>
                            <th class="px-4 py-3.5 font-semibold">Tanggal</th>
                            <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @forelse ($laporan as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3.5 font-semibold">{{ $item->judul }}</td>
                        <td class="px-4 py-3.5">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                        <td class="px-4 py-3.5">{{ $item->instansi->nama_instansi ?? '-' }}</td>
                        <td class="px-4 py-3.5">
                            <span @class([
                                'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                'bg-merah/10 text-merah' => $item->tingkat_prioritas === 'Kritis',
                                'bg-oranye/10 text-oranye' => $item->tingkat_prioritas === 'Sedang',
                                'bg-kuning/20 text-abu-tua' => $item->tingkat_prioritas === 'Rendah',
                            ])>
                                {{ $item->tingkat_prioritas }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span @class([
                                'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                'bg-abu-muda text-abu-tua' => $item->status === 'Menunggu',
                                'bg-cyan-3/20 text-cyan-6' => $item->status === 'Diverifikasi',
                                'bg-oranye/10 text-oranye' => $item->status === 'Diproses',
                                'bg-hijau/10 text-hijau' => $item->status === 'Selesai',
                                'bg-merah/10 text-merah' => $item->status === 'Ditolak',
                            ])>
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap">{{ $item->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-3.5 text-center">
                            <a href="{{ route('warga.laporan.show', $item->id) }}"
                            class="text-sm font-semibold text-cyan-4 hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                            Belum ada laporan yang kamu buat.
                        </td>
                    </tr>
                @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection