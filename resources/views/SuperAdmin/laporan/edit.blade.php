@extends('template.layout')

@section('title', 'Edit Laporan')

@section('content')

@php
    $inputClass = 'w-full rounded border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-[#86b7fe] focus:outline-none focus:ring-2 focus:ring-[#0d6efd]/25';
    $labelClass = 'mb-1 block text-sm font-medium text-gray-700';
@endphp

<div class="mb-5">
    <h1 class="text-xl font-semibold text-gray-800">Edit Laporan</h1>
    <p class="text-sm text-gray-500">Perbarui data laporan. Informasi dari pelapor tidak dapat diubah.</p>
</div>

@if ($errors->any())
    <div class="mb-5 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <p class="mb-1 font-medium">Terdapat kesalahan pada isian:</p>
        <ul class="list-disc space-y-0.5 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Info laporan (hanya baca, tidak diubah lewat form ini) --}}
<div class="mb-6 overflow-hidden rounded-md border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 bg-gray-50 px-5 py-3 text-sm font-semibold text-gray-700">
        Informasi Pelapor
    </div>

    <dl class="divide-y divide-gray-100 text-sm">
        <div class="grid grid-cols-1 gap-1 px-5 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-gray-500">Kode Lacak</dt>
            <dd class="text-gray-800 sm:col-span-2">{{ $laporan->kode_lacak ?? '-' }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-5 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-gray-500">Pelapor</dt>
            <dd class="text-gray-800 sm:col-span-2">{{ $laporan->nama_pelapor }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-5 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-gray-500">Jenis Laporan</dt>
            <dd class="text-gray-800 sm:col-span-2">{{ $laporan->jenis_laporan ?? '-' }}</dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-5 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-gray-500">Tanggal Kejadian</dt>
            <dd class="text-gray-800 sm:col-span-2">
                {{ $laporan->tanggal_kejadian ? \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d-m-Y') : '-' }}
            </dd>
        </div>

        <div class="grid grid-cols-1 gap-1 px-5 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-gray-500">Kecamatan</dt>
            <dd class="text-gray-800 sm:col-span-2">{{ $laporan->kecamatan ?: '-' }}</dd>
        </div>

        @if ($laporan->kategori_lainnya)
            <div class="grid grid-cols-1 gap-1 px-5 py-3 sm:grid-cols-3 sm:gap-4">
                <dt class="font-medium text-gray-500">Kategori Lainnya</dt>
                <dd class="text-gray-800 sm:col-span-2">{{ $laporan->kategori_lainnya }}</dd>
            </div>
        @endif

        @if ($laporan->lampiran)
            <div class="grid grid-cols-1 gap-1 px-5 py-3 sm:grid-cols-3 sm:gap-4">
                <dt class="font-medium text-gray-500">Lampiran</dt>
                <dd class="sm:col-span-2">
                    <img src="{{ asset('storage/' . $laporan->lampiran) }}"
                         alt="Lampiran laporan"
                         class="max-h-40 rounded border border-gray-200 object-contain">
                </dd>
            </div>
        @endif
    </dl>
</div>

<form action="{{ route('super_admin.laporan.update', $laporan->id) }}"
      method="POST"
      enctype="multipart/form-data"
      class="rounded-md border border-gray-200 bg-white shadow-sm">
    @csrf
    @method('PUT')

    <div class="border-b border-gray-200 bg-gray-50 px-5 py-3 text-sm font-semibold text-gray-700">
        Data Laporan
    </div>

    <div class="grid grid-cols-1 gap-5 px-5 py-5 md:grid-cols-2">

        {{-- Kategori --}}
        <div>
            <label for="kategori_id" class="{{ $labelClass }}">Kategori</label>
            <select id="kategori_id" name="kategori_id" required class="{{ $inputClass }}">
                @foreach ($kategoris as $v)
                    <option value="{{ $v->id }}"
                        {{ old('kategori_id', $laporan->kategori_id) == $v->id ? 'selected' : '' }}>
                        {{ $v->nama_kategori }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Instansi --}}
        <div>
            <label for="instansi_id" class="{{ $labelClass }}">Instansi</label>
            <select id="instansi_id" name="instansi_id" required class="{{ $inputClass }}">
                @foreach ($instansis as $v)
                    <option value="{{ $v->id }}"
                        {{ old('instansi_id', $laporan->instansi_id) == $v->id ? 'selected' : '' }}>
                        {{ $v->nama_instansi }}
                    </option>
                @endforeach
            </select>
            @error('instansi_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Judul --}}
        <div class="md:col-span-2">
            <label for="judul" class="{{ $labelClass }}">Judul</label>
            <input type="text" id="judul" name="judul" required
                   value="{{ old('judul', $laporan->judul) }}"
                   class="{{ $inputClass }}">
            @error('judul')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="md:col-span-2">
            <label for="deskripsi" class="{{ $labelClass }}">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" required
                      class="{{ $inputClass }}">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
            @error('deskripsi')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Foto --}}
        <div class="md:col-span-2">
            <label for="foto" class="{{ $labelClass }}">Foto</label>

            @if ($laporan->foto)
                <img src="{{ asset('storage/' . $laporan->foto) }}"
                     alt="Foto laporan"
                     class="mb-3 max-h-40 rounded border border-gray-200 object-contain">
            @endif

            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png"
                   class="block w-full text-sm text-gray-600 file:mr-3 file:rounded file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200">
            <p class="mt-1 text-xs text-gray-500">JPG atau PNG, maksimal 2 MB. Kosongkan jika tidak ingin mengganti foto.</p>
            @error('foto')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Latitude --}}
        <div>
            <label for="latitude" class="{{ $labelClass }}">Latitude</label>
            <input type="number" step="any" min="-90" max="90" id="latitude" name="latitude" required
                   value="{{ old('latitude', $laporan->latitude) }}"
                   class="{{ $inputClass }}">
            @error('latitude')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Longitude --}}
        <div>
            <label for="longitude" class="{{ $labelClass }}">Longitude</label>
            <input type="number" step="any" min="-180" max="180" id="longitude" name="longitude" required
                   value="{{ old('longitude', $laporan->longitude) }}"
                   class="{{ $inputClass }}">
            @error('longitude')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tingkat Prioritas --}}
        <div>
            <label for="tingkat_prioritas" class="{{ $labelClass }}">Tingkat Prioritas</label>
            <select id="tingkat_prioritas" name="tingkat_prioritas" required class="{{ $inputClass }}">
                @foreach (['Kritis', 'Sedang', 'Rendah'] as $p)
                    <option value="{{ $p }}"
                        {{ old('tingkat_prioritas', $laporan->tingkat_prioritas) == $p ? 'selected' : '' }}>
                        {{ $p }}
                    </option>
                @endforeach
            </select>
            @error('tingkat_prioritas')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status --}}
        <div>
            <label for="status" class="{{ $labelClass }}">Status</label>
            <select id="status" name="status" required class="{{ $inputClass }}">
                @foreach (['Menunggu', 'Diverifikasi', 'Diproses', 'Selesai', 'Ditolak'] as $s)
                    <option value="{{ $s }}"
                        {{ old('status', $laporan->status) == $s ? 'selected' : '' }}>
                        {{ $s }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 border-t border-gray-200 bg-gray-50 px-5 py-3">
        <a href="{{ route('super_admin.laporan.index') }}"
           class="rounded bg-[#6c757d] px-4 py-2 text-sm text-white transition hover:bg-[#5c636a]">
            Kembali
        </a>

        <button type="submit"
                class="rounded bg-[#0d6efd] px-4 py-2 text-sm text-white transition hover:bg-[#0b5ed7]">
            Update Laporan
        </button>
    </div>
</form>

@endsection