@extends('template.layout')

@section('title', 'Kategori Laporan')

@section('content')
<div class="px-6 pb-6 pt-0">

    {{-- ===== Tombol tambah ===== --}}
    <div class="mb-3 flex justify-end">
        <a href="{{ route('super_admin.kategori.create') }}"
           class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold no-underline hover:no-underline transition">
            + Tambah Kategori
        </a>
    </div>

    {{-- ===== Kartu kategori ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($kategoris as $v)
            @php
                $warna = (string) $v->warna_marker;
                $isHex = preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $warna);
                $aktif = in_array(strtolower((string) $v->status), ['aktif', 'active', '1', 'true'], true);
            @endphp

            <div class="bg-white rounded-2xl shadow overflow-hidden flex flex-col">

                {{-- Banner warna marker + label icon --}}
                <div class="h-16 flex items-end px-5 {{ $isHex ? '' : 'bg-abu-muda' }}"
                     @if ($isHex) style="background-color: {{ $warna }}" @endif>
                    <span class="-mb-3 inline-block px-2.5 py-0.5 rounded-md bg-white text-xs font-medium text-gray-600 shadow-sm">
                        {{ $v->icon ?: '-' }}
                    </span>
                </div>

                <div class="px-5 pt-7 pb-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between gap-3">
                        <h2 class="text-base font-semibold text-gray-900">{{ $v->nama_kategori }}</h2>
                        <span @class([
                            'shrink-0 inline-block px-3 py-1 rounded-full text-xs font-semibold',
                            'bg-hijau/10 text-hijau' => $aktif,
                            'bg-abu-muda text-abu-tua' => ! $aktif,
                        ])>{{ $v->status ?: '-' }}</span>
                    </div>

                    <p class="mt-2 mb-4 text-[13px] text-gray-500 min-h-[40px]">
                        {{ $v->deskripsi ?: '-' }}
                    </p>

                    <div class="mt-auto flex items-center justify-between gap-4 pt-4 border-t border-gray-200">
                        <span class="text-xs text-gray-500">{{ $warna ?: '-' }}</span>

                        <div class="flex items-center gap-4 text-sm font-semibold whitespace-nowrap">
                            <a href="{{ route('super_admin.kategori.show', $v->id) }}"
                               class="text-cyan-4 no-underline hover:no-underline hover:opacity-80 transition">Detail</a>

                            <a href="{{ route('super_admin.kategori.edit', $v->id) }}"
                               class="text-oranye no-underline hover:no-underline hover:opacity-80 transition">Edit</a>

                            <form method="POST"
                                  action="{{ route('super_admin.kategori.destroy', $v->id) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="text-merah hover:opacity-80 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl shadow px-4 py-10 text-center text-gray-400">
                Belum ada data kategori.
            </div>
        @endforelse
    </div>
</div>
@endsection
