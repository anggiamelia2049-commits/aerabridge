@extends('template.layout')

@section('title', 'Tambah Tim Satgas')

@section('content')

@php
    $tim = null;
    $f = fn ($k, $d = '') => old($k, $tim->$k ?? $d);
    $in = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-cyan-4 focus:ring-cyan-4';
@endphp

<a href="{{ route('instansi.tim-satgas.index') }}" class="text-sm text-cyan-4 hover:underline">&larr; Kembali</a>
<h1 class="my-4 text-3xl font-bold text-cyan-6">Tambah Tim Satgas</h1>

<form method="POST" action="{{ route('instansi.tim-satgas.store') }}" class="max-w-3xl rounded-lg bg-white p-6 shadow">
    @csrf

    <div class="space-y-5">
        <div>
            <label class="mb-1 block text-sm font-semibold text-cyan-6">Nama Tim</label>
            <input type="text" name="nama_tim" value="{{ $f('nama_tim') }}" placeholder="Contoh: Satgas Jalan Berlubang" class="{{ $in }}">
            @error('nama_tim')<p class="mt-1 text-xs text-merah">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-semibold text-cyan-6">Ketua Tim</label>
                <input type="text" name="ketua" value="{{ $f('ketua') }}" class="{{ $in }}">
                @error('ketua')<p class="mt-1 text-xs text-merah">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-cyan-6">Kontak (No. HP)</label>
                <input type="text" name="kontak" value="{{ $f('kontak') }}" placeholder="08xxxxxxxxxx" class="{{ $in }}">
                @error('kontak')<p class="mt-1 text-xs text-merah">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-cyan-6">Jumlah Anggota</label>
                <input type="number" min="0" name="jumlah_anggota" value="{{ $f('jumlah_anggota', 0) }}" class="{{ $in }}">
                @error('jumlah_anggota')<p class="mt-1 text-xs text-merah">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-cyan-6">Status</label>
                <select name="status" class="{{ $in }}">
                    <option value="aktif" @selected($f('status', 'aktif') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected($f('status', 'aktif') === 'nonaktif')>Nonaktif</option>
                </select>
                @error('status')<p class="mt-1 text-xs text-merah">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-end gap-3">
        <a href="{{ route('instansi.tim-satgas.index') }}" class="rounded-lg bg-gray-200 px-5 py-2.5 text-sm font-medium text-abu-tua hover:bg-gray-300">Batal</a>
        <button type="submit" class="rounded-lg bg-cyan-4 px-5 py-2.5 text-sm font-medium text-white hover:bg-cyan-6">Simpan</button>
    </div>
</form>

@endsection
