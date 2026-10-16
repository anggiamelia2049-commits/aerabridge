@extends('template.layout')

@section('title', 'Closing Report')

@section('content')
@php
    $label = 'block text-sm font-semibold text-cyan-6 mb-1.5';
@endphp

<div class="p-6">
    <div class="max-w-4xl mx-auto">
        @if (session('success'))
    <div class="mb-4 rounded-lg border border-hijau/30 bg-hijau/10 px-4 py-3 text-sm font-medium text-hijau" role="status">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 rounded-lg border border-merah/30 bg-merah/10 px-4 py-3 text-sm font-medium text-merah" role="alert">{{ session('error') }}</div>
@endif

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-5">
                <h2 class="text-lg font-bold text-cyan-6">{{ $penugasan->laporan->judul }}</h2>
                <p class="text-sm text-gray-600">Unggah foto hasil perbaikan dan tulis catatan penyelesaian.</p>
            </div>

            <form action="{{ route('petugas.penugasan.update', $penugasan->id) }}" method="POST" enctype="multipart/form-data" class="px-6 pb-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="selesai">

                <div class="grid grid-cols-1 gap-5">

                    <div>
                        <label for="foto_hasil" class="{{ $label }}">Foto hasil perbaikan <span class="text-merah">*</span></label>
                        <img id="previewFoto" src="" alt="Pratinjau foto hasil perbaikan"
                             class="mb-3 hidden h-40 w-auto max-w-full rounded-lg border border-abu-muda object-cover">
                        <input type="file" id="foto_hasil" name="foto_hasil" accept=".jpg,.jpeg,.png" required
                               class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-cyan-3/20 file:text-cyan-6 file:font-semibold">
                        <p class="mt-1 text-xs text-gray-500">JPG atau PNG, maksimal 5 MB.</p>
                        <p id="errFoto" class="mt-1 hidden text-xs text-merah"></p>
                        @error('foto_hasil') <p class="mt-1 text-xs text-merah">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="catatan_penyelesaian" class="{{ $label }}">Catatan penyelesaian <span class="text-merah">*</span></label>
                        <textarea id="catatan_penyelesaian" name="catatan_penyelesaian" rows="5" minlength="10" maxlength="1000" required
                                  placeholder="Jelaskan pekerjaan yang sudah dilakukan"
                                  class="w-full px-4 py-3 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">{{ old('catatan_penyelesaian') }}</textarea>
                        <div class="mt-1 flex justify-between gap-4 text-xs text-gray-500">
                            <span>Minimal 10 karakter.</span>
                            <span id="hitung">0 / 1000</span>
                        </div>
                        @error('catatan_penyelesaian') <p class="mt-1 text-xs text-merah">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                    <a href="{{ route('petugas.penugasan.show', $penugasan->id) }}"
                       class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Batal</a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Kirim closing report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const fotoEl = document.getElementById('foto_hasil');
    const previewEl = document.getElementById('previewFoto');
    const errFotoEl = document.getElementById('errFoto');
    const catatanEl = document.getElementById('catatan_penyelesaian');
    const hitungEl = document.getElementById('hitung');

    fotoEl.addEventListener('change', function () {
        const file = fotoEl.files[0];
        errFotoEl.classList.add('hidden');
        previewEl.classList.add('hidden');
        if (!file) return;

        if (file.size > 5 * 1024 * 1024) {
            errFotoEl.textContent = 'Ukuran foto maksimal 5 MB.';
            errFotoEl.classList.remove('hidden');
            fotoEl.value = '';
            return;
        }

        previewEl.src = URL.createObjectURL(file);
        previewEl.classList.remove('hidden');
    });

    function hitung() { hitungEl.textContent = catatanEl.value.length + ' / 1000'; }
    catatanEl.addEventListener('input', hitung);
    hitung();
</script>
@endsection