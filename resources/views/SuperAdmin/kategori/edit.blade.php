@extends('template.layout')

@section('title', 'Detail Kategori')

@section('content')
<div class="px-6 pb-6 pt-0">
    @php
        $warna = (string) $kategori->warna_marker;
        $isHex = preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $warna);
        $aktif = in_array(strtolower((string) $kategori->status), ['aktif', 'active', '1', 'true'], true);
    @endphp

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        {{-- Banner warna marker + label icon --}}
        <div class="h-16 flex items-end px-6 {{ $isHex ? '' : 'bg-abu-muda' }}"
             @if ($isHex) style="background-color: {{ $warna }}" @endif>
            <span class="-mb-3 inline-block px-2.5 py-0.5 rounded-md bg-white text-xs font-medium text-gray-600 shadow-sm">
                {{ $kategori->icon ?: '-' }}
            </span>
        </div>

        <div class="px-6 pt-8 pb-6">
            <div class="flex items-start justify-between gap-3 mb-5">
                <h2 class="text-base font-semibold text-gray-900">{{ $kategori->nama_kategori }}</h2>
                <span @class([
                    'shrink-0 inline-block px-3 py-1 rounded-full text-xs font-semibold',
                    'bg-hijau/10 text-hijau' => $aktif,
                    'bg-abu-muda text-abu-tua' => ! $aktif,
                ])>{{ $kategori->status ?: '-' }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Warna Marker</p>
                    <p class="text-sm text-gray-600">{{ $warna ?: '-' }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Icon</p>
                    <p class="text-sm text-gray-600">{{ $kategori->icon ?: '-' }}</p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Deskripsi</p>
                    <p class="text-sm text-gray-600">{{ $kategori->deskripsi ?: '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Tombol aksi: pojok bawah kanan --}}
        <div class="flex items-center justify-end gap-3 px-6 pb-6">
            <a href="{{ route('super_admin.kategori.index') }}"
               class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-semibold text-gray-700 no-underline hover:no-underline hover:bg-gray-50 transition">
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
