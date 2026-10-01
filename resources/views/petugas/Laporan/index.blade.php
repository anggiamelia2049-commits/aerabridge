@extends('template.layout')

@section('title', 'Laporan')

@section('content')
<div>

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-[36px] font-bold text-gray-800">Daftar Laporan</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left whitespace-nowrap">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Instansi</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Deskripsi</th>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">Latitude</th>
                    <th class="px-4 py-3">Longitude</th>
                    <th class="px-4 py-3">Alamat</th>
                    <th class="px-4 py-3">Prioritas</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Verifikasi Oleh</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $v)
                <tr class="border-t border-gray-100">
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">{{ $v->user->nama ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $v->kategori->nama_kategori ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $v->instansi->nama_instansi ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $v->judul }}</td>
                    <td class="px-4 py-3 max-w-xs truncate">{{ $v->deskripsi }}</td>

                    <td class="px-4 py-3">
                        @if ($v->foto)
                            <img src="{{ asset('storage/' . $v->foto) }}"
                                 class="w-12 h-12 rounded object-cover">
                        @else
                            <span class="text-gray-400 text-xs">Tidak ada foto</span>
                        @endif
                    </td>

                    <td class="px-4 py-3">{{ $v->latitude }}</td>
                    <td class="px-4 py-3">{{ $v->longitude }}</td>
                    <td class="px-4 py-3">{{ $v->alamat }}</td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $v->tingkat_prioritas == 'Kritis' ? 'bg-red-100 text-red-700' :
                               ($v->tingkat_prioritas == 'Sedang' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700') }}">
                            {{ $v->tingkat_prioritas }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $v->status == 'Selesai' ? 'bg-green-100 text-green-700' :
                               ($v->status == 'Diproses' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ $v->status }}
                        </span>
                    </td>

                    <td class="px-4 py-3">{{ $v->diverifikasiOleh->nama ?? '-' }}</td>

                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('petugas.laporan.show', $v->id) }}"
                           class="text-cyan-700 hover:underline">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="px-4 py-6 text-center text-gray-400">
                        Belum ada laporan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
