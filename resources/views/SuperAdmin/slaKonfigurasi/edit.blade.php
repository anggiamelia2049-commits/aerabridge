@extends('template.layout')

@section('title', 'Edit Konfigurasi SLA')

@section('content')
@php
    $field = 'w-full h-11 px-4 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
    $label = 'block text-sm font-semibold text-cyan-6 mb-1.5';
@endphp

<div class="p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow overflow-hidden">

        <div class="px-6 py-4">
        </div>

        <form action="{{ route('super_admin.sla-konfigurasi.update', $slaKonfigurasi->id) }}"
              method="POST" class="px-6 pb-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label for="prioritas" class="{{ $label }}">Prioritas <span class="text-merah">*</span></label>
                    <select id="prioritas" name="prioritas" class="{{ $field }}" required>
                        <option value="">Pilih prioritas</option>
                        <option value="kritis" @selected(old('prioritas', $slaKonfigurasi->prioritas) == 'kritis')>Kritis</option>
                        <option value="sedang" @selected(old('prioritas', $slaKonfigurasi->prioritas) == 'sedang')>Sedang</option>
                        <option value="rendah" @selected(old('prioritas', $slaKonfigurasi->prioritas) == 'rendah')>Rendah</option>
                    </select>
                    @error('prioritas')
                        <p class="text-xs text-merah mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="{{ $label }}">Status <span class="text-merah">*</span></label>
                    <select id="status" name="status" class="{{ $field }}" required>
                        <option value="aktif" @selected(old('status', $slaKonfigurasi->status) == 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status', $slaKonfigurasi->status) == 'nonaktif')>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-merah mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="waktu_respon" class="{{ $label }}">Waktu Respon (jam) <span class="text-merah">*</span></label>
                    <input type="number" id="waktu_respon" name="waktu_respon" min="0"
                           value="{{ old('waktu_respon', $slaKonfigurasi->waktu_respon) }}"
                           placeholder="Masukkan waktu respon" class="{{ $field }}" required>
                    @error('waktu_respon')
                        <p class="text-xs text-merah mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="waktu_penyelesaian" class="{{ $label }}">Waktu Penyelesaian (jam) <span class="text-merah">*</span></label>
                    <input type="number" id="waktu_penyelesaian" name="waktu_penyelesaian" min="0"
                           value="{{ old('waktu_penyelesaian', $slaKonfigurasi->waktu_penyelesaian) }}"
                           placeholder="Masukkan waktu penyelesaian" class="{{ $field }}" required>
                    @error('waktu_penyelesaian')
                        <p class="text-xs text-merah mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="{{ $label }}">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                              placeholder="Masukkan deskripsi konfigurasi SLA"
                              class="w-full px-4 py-3 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">{{ old('deskripsi', $slaKonfigurasi->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-xs text-merah mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <a href="{{ route('super_admin.sla-konfigurasi.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
