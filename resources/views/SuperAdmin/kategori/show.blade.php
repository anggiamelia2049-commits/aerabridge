@extends('template.layout')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="px-6 pt-6 pb-4">
            {{-- Nama kategori --}}
            <p class="text-xs text-gray-400 mb-1">Nama Kategori</p>
            <p class="text-base font-semibold text-gray-800 mb-5">
                {{ $kategori->nama_kategori }}
            </p>

            {{-- Deskripsi --}}
            <p class="text-xs text-gray-400 mb-1">Deskripsi</p>
            <p class="text-sm text-gray-600 mb-6">
                {{ $kategori->deskripsi ?: '-' }}
            </p>

            {{-- Icon, warna marker, status dalam bentuk chip --}}
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-100 text-xs font-semibold text-gray-700">
                    <span class="text-gray-400 font-normal">Icon</span>
                    {{ $kategori->icon ?: '-' }}
                </span>

                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-100 text-xs font-semibold text-gray-700">
                    <span class="text-gray-400 font-normal">Warna</span>
                    @if ($kategori->warna_marker)
                        <span class="w-3.5 h-3.5 rounded border border-black/10"
                              style="background-color: {{ $kategori->warna_marker }}"></span>
                        {{ $kategori->warna_marker }}
                    @else
                        -
                    @endif
                </span>

                <span @class([
                    'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold',
                    'bg-hijau/10 text-hijau' => strtolower((string) $kategori->status) === 'aktif',
                    'bg-abu-muda text-abu-tua' => strtolower((string) $kategori->status) !== 'aktif',
                ])>
                    {{ $kategori->status ?: '-' }}
                </span>
            </div>
        </div>

                {{-- Tombol di kanan bawah, tanpa garis pemisah --}}
        <div class="flex items-center justify-end gap-3 px-6 pt-4 pb-6">
            <a href="{{ route('super_admin.kategori.index') }}"
               class="px-5 py-2.5 rounded-lg bg-white border border-gray-300 text-gray-700 text-sm font-bold no-underline hover:no-underline transition hover:bg-gray-100">
                Batal
            </a>

            <a href="{{ route('super_admin.kategori.edit', $kategori->id) }}"
               class="px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold no-underline hover:no-underline transition">
                Edit
            </a>
        </div>
    </div>
</div>
@endsection
