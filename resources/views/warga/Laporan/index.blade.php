@extends('template.layout')

@section('content')
    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
            <div class="mb-4 p-4 bg-hijau/10 border border-hijau text-hijau rounded-lg text-sm">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="flex justify-between items-center px-6 py-4 border-b border-abu-muda">
                    <p class="text-sm text-abu-tua">
                        Pantau status laporan kerusakan infrastruktur yang sudah kamu kirim.
                    </p>
                    <a href="{{ route('warga.laporan.create') }}"
                        class="inline-flex items-center gap-2 bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                        + Buat Laporan
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-cyan-6 text-white">
                            <tr>
                                <th class="px-4 py-3 font-medium">Judul</th>
                                <th class="px-4 py-3 font-medium">Kategori</th>
                                <th class="px-4 py-3 font-medium">Instansi</th>
                                <th class="px-4 py-3 font-medium">Prioritas</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium">Tanggal</th>
                                <th class="px-4 py-3 font-medium"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-abu-muda">
                            @forelse ($laporan as $item)
                            <tr class="hover:bg-cyan-muda/10 transition">
                                <td class="px-4 py-3 font-medium text-abu-tua">{{ $item->judul }}</td>
                                <td class="px-4 py-3 text-abu-tua">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td class="px-4 py-3 text-abu-tua">{{ $item->instansi->nama_instansi ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span @class([ 'px-2.5 py-1 rounded-full text-xs font-semibold' , 'bg-merah/10 text-merah'=> $item->tingkat_prioritas === 'Krisis',
                                        'bg-oranye/10 text-oranye' => $item->tingkat_prioritas === 'Sedang',
                                        'bg-kuning/20 text-abu-tua' => $item->tingkat_prioritas === 'Rendah',
                                        ])>
                                        {{ $item->tingkat_prioritas }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span @class([ 'px-2.5 py-1 rounded-full text-xs font-semibold' , 'bg-abu-muda text-abu-tua'=> $item->status === 'Menunggu',
                                        'bg-cyan-3/20 text-cyan-6' => $item->status === 'Diverifikasi',
                                        'bg-oranye/10 text-oranye' => $item->status === 'Diproses',
                                        'bg-hijau/10 text-hijau' => $item->status === 'Selesai',
                                        'bg-merah/10 text-merah' => $item->status === 'Ditolak',
                                        ])>
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-abu-tua">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('warga.laporan.show', $item->id) }}"
                                        class="text-cyan-4 hover:text-cyan-6 font-medium hover:underline">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-abu-tua">
                                    Belum ada laporan yang kamu buat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection