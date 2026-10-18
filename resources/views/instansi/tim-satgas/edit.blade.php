@extends('template.layout')

@section('title', 'Edit Tim Satgas')

@section('content')

@php
    $f = fn ($k, $d = '') => old($k, $tim->$k ?? $d);
    $in = 'w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-[15px] focus:border-cyan-4 focus:ring-cyan-4';
    $lb = 'mb-1.5 block text-[15px] font-semibold text-cyan-6';
@endphp

<div class="mx-auto w-full max-w-[820px]">


    <form method="POST" action="{{ route('instansi.tim-satgas.update', $tim->id) }}" class="rounded-lg bg-white p-7 shadow">
        @csrf
        @method('PUT')

        <div class="space-y-5">
            <div>
                <label class="{{ $lb }}">Nama Tim</label>
                <input type="text" name="nama_tim" value="{{ $f('nama_tim') }}" placeholder="Contoh: Satgas Jalan Berlubang" class="{{ $in }}">
                @error('nama_tim')<p class="mt-1 text-caption text-merah">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="{{ $lb }}">Ketua Tim</label>
                    <input type="text" name="ketua" value="{{ $f('ketua') }}" class="{{ $in }}">
                    @error('ketua')<p class="mt-1 text-caption text-merah">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $lb }}">Kontak (No. HP)</label>
                    <input type="text" name="kontak" value="{{ $f('kontak') }}" placeholder="08xxxxxxxxxx" class="{{ $in }}">
                    @error('kontak')<p class="mt-1 text-caption text-merah">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $lb }}">Jumlah Anggota</label>
                    <input type="number" min="0" name="jumlah_anggota" value="{{ $f('jumlah_anggota', 0) }}" class="{{ $in }}">
                    @error('jumlah_anggota')<p class="mt-1 text-caption text-merah">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="{{ $lb }}">Status</label>
                    <select name="status" class="{{ $in }}">
                        <option value="aktif" @selected($f('status', 'aktif') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected($f('status', 'aktif') === 'nonaktif')>Nonaktif</option>
                    </select>
                    @error('status')<p class="mt-1 text-caption text-merah">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="mt-7 flex justify-end gap-3">
            <a href="{{ route('instansi.tim-satgas.index', $tim->id) }}" class="rounded-lg bg-gray-200 px-6 py-2.5 text-[15px] font-medium text-abu-tua no-underline hover:bg-gray-300">Batal</a>
            <button type="submit" class="rounded-lg bg-cyan-4 px-6 py-2.5 text-[15px] font-medium text-white hover:bg-cyan-6">Simpan</button>
        </div>
    </form>

</div>

@endsection
