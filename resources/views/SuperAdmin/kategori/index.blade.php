
@extends('template.layout')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">
                Kelola kategori kerusakan yang digunakan warga saat membuat laporan.
            </p>

            <a href="{{ route('super_admin.kategori.create') }}"
               class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                + Tambah Kategori
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">No</th>
                        <th class="px-4 py-3.5 font-semibold">Nama Kategori</th>
                        <th class="px-4 py-3.5 font-semibold">Icon</th>
                        <th class="px-4 py-3.5 font-semibold">Deskripsi</th>
                        <th class="px-4 py-3.5 font-semibold whitespace-nowrap">Warna Marker</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($kategoris as $v)
                        @php
                            $warna = (string) $v->warna_marker;
                            $isHex = preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $warna);
                            $aktif = in_array(strtolower((string) $v->status), ['aktif', 'active', '1', 'true'], true);
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">{{ $loop->iteration }}</td>

                            <td class="px-4 py-3.5 font-semibold whitespace-nowrap">
                                {{ $v->nama_kategori }}
                            </td>

                            <td class="px-4 py-3.5 text-lg">{{ $v->icon ?: '-' }}</td>

                            <td class="px-4 py-3.5 min-w-[180px]">
                                {{ $v->deskripsi ?: '-' }}
                            </td>

                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if ($isHex)
                                    <span class="inline-flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-md border border-abu-muda"
                                              style="background-color: {{ $warna }}"></span>
                                        {{ $warna }}
                                    </span>
                                @else
                                    {{ $warna ?: '-' }}
                                @endif
                            </td>

                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                    'bg-hijau/10 text-hijau' => $aktif,
                                    'bg-abu-muda text-abu-tua' => !$aktif,
                                ])>
                                    {{ $v->status ?: '-' }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-4 text-sm font-semibold whitespace-nowrap">
                                    <a href="{{ route('super_admin.kategori.show', $v->id) }}"
                                       class="text-cyan-4 hover:underline">Detail</a>

                                    <a href="{{ route('super_admin.kategori.edit', $v->id) }}"
                                       class="text-oranye hover:underline">Edit</a>

                                    <form method="POST"
                                          action="{{ route('super_admin.kategori.destroy', $v->id) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-merah hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                                Belum ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
