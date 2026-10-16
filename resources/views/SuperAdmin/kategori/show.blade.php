
@extends('template.layout')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">
                Informasi lengkap kategori kerusakan.
            </p>

            <a href="{{ route('super_admin.kategori.index') }}"
               class="shrink-0 px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-bold transition hover:bg-gray-100">
                Kembali
            </a>
        </div>

        <div class="divide-y divide-gray-200 text-sm text-gray-700">
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <p class="font-semibold">Nama Kategori</p>
                <p class="col-span-2">{{ $kategori->nama_kategori }}</p>
            </div>

            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <p class="font-semibold">Icon</p>
                <p class="col-span-2 text-lg">{{ $kategori->icon ?: '-' }}</p>
            </div>

            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <p class="font-semibold">Deskripsi</p>
                <p class="col-span-2">{{ $kategori->deskripsi ?: '-' }}</p>
            </div>

            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <p class="font-semibold">Warna Marker</p>
                <div class="col-span-2 flex items-center gap-2">
                    @if ($kategori->warna_marker)
                        <span class="w-5 h-5 rounded-md border border-abu-muda"
                              style="background-color: {{ $kategori->warna_marker }}"></span>
                        {{ $kategori->warna_marker }}
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <p class="font-semibold">Status</p>
                <div class="col-span-2">
                    <span @class([
                        'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                        'bg-hijau/10 text-hijau' => strtolower((string) $kategori->status) === 'aktif',
                        'bg-abu-muda text-abu-tua' => strtolower((string) $kategori->status) !== 'aktif',
                    ])>
                        {{ $kategori->status ?: '-' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 border-t border-gray-200 px-6 py-5">
            <a href="{{ route('super_admin.kategori.edit', $kategori->id) }}"
               class="px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                Edit
            </a>

            <a href="{{ route('super_admin.kategori.index') }}"
               class="text-sm font-semibold text-gray-600 hover:underline">
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
