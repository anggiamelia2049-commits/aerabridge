@extends('template.layout')

@section('title', 'Konten Edukasi')

@section('content')
@php
    $badge = [
        'publish'  => 'bg-hijau/10 text-hijau',
        'draft'    => 'bg-yellow-100 text-yellow-700',
        'nonaktif' => 'bg-abu-muda text-abu-tua',
    ];
@endphp

<div class="p-6">
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm font-medium text-hijau">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">
                Kelola materi edukasi perawatan fasilitas publik.
            </p>

            <a href="{{ route('super_admin.konten-edukasi.create') }}"
               class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                + Tambah Konten
            </a>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">No</th>
                        <th class="px-4 py-3.5 font-semibold">Thumbnail</th>
                        <th class="px-4 py-3.5 font-semibold">Judul</th>
                        <th class="px-4 py-3.5 font-semibold">Kategori</th>
                        <th class="px-4 py-3.5 font-semibold">Penulis</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($konten as $v)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-3.5">
                                @if ($v->thumbnail)
                                    <img
                                        src="{{ asset('storage/' . $v->thumbnail) }}"
                                        alt="Thumbnail {{ $v->judul }}"
                                        class="w-12 h-12 object-cover rounded-lg border border-abu-muda"
                                    >
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 border border-abu-muda flex items-center justify-center text-[10px] text-gray-400">
                                        Tidak ada
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-3.5 max-w-xs">
                                <div class="font-semibold">
                                    {{ $v->judul }}
                                </div>
                                <div class="mt-1 text-xs text-gray-500">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($v->isi), 80) }}
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-cyan-3/20 text-cyan-6">
                                    {{ $v->kategori }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5 whitespace-nowrap">
                                {{ $v->penulisUser->nama ?? '-' }}
                            </td>

                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                    $badge[$v->status] ?? 'bg-abu-muda text-abu-tua',
                                ])>
                                    {{ ucfirst($v->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-4 text-sm font-semibold">
                                    <a href="{{ route('super_admin.konten-edukasi.show', $v->id) }}"
                                       class="text-cyan-4 hover:underline">
                                        Detail
                                    </a>

                                    <a href="{{ route('super_admin.konten-edukasi.edit', $v->id) }}"
                                       class="text-oranye hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('super_admin.konten-edukasi.destroy', $v->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus konten ini?')">
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
                                Belum ada konten edukasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection