@extends('template.layout')

@section('content')
@php
    $field = 'w-full h-11 px-4 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
    $label = 'block text-sm font-semibold text-cyan-6 mb-1.5';
@endphp

<div class="p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-5">
            <p class="text-sm text-gray-600">Tambah hadiah yang bisa ditukar dengan poin kontribusi.</p>
        </div>

        <form method="POST" action="{{ route('super_admin.hadiah.store') }}" enctype="multipart/form-data" class="px-6 pb-6 space-y-5">
            @csrf

            <div>
                <label for="nama_hadiah" class="{{ $label }}">Nama Hadiah <span class="text-merah">*</span></label>
                <input type="text" id="nama_hadiah" name="nama_hadiah" value="{{ old('nama_hadiah') }}" maxlength="150"
                       placeholder="contoh: Voucher Pulsa 10.000" class="{{ $field }}" required>
                @error('nama_hadiah') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="poin_dibutuhkan" class="{{ $label }}">Poin Dibutuhkan <span class="text-merah">*</span></label>
                    <input type="number" id="poin_dibutuhkan" name="poin_dibutuhkan" min="0"
                           value="{{ old('poin_dibutuhkan') }}" class="{{ $field }}" required>
                    @error('poin_dibutuhkan') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="stok" class="{{ $label }}">Stok <span class="text-merah">*</span></label>
                    <input type="number" id="stok" name="stok" min="0"
                           value="{{ old('stok', 0) }}" class="{{ $field }}" required>
                    @error('stok') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="{{ $label }}">Status <span class="text-merah">*</span></label>
                    <select id="status" name="status" class="{{ $field }}" required>
                        @foreach (['tersedia' => 'Tersedia', 'habis' => 'Habis', 'nonaktif' => 'Nonaktif'] as $nilai => $teks)
                            <option value="{{ $nilai }}" @selected(old('status', 'tersedia') === $nilai)>{{ $teks }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="deskripsi" class="{{ $label }}">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Tulis deskripsi hadiah (opsional)..."
                          class="w-full px-4 py-3 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="gambar" class="{{ $label }}">Gambar Hadiah</label>
                <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png"
                       class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-cyan-3/20 file:text-cyan-6 file:font-semibold">
                <p class="text-xs text-gray-500 mt-1">JPG atau PNG, maksimal 2 MB.</p>
                <img id="previewGambar" class="hidden mt-3 w-32 h-32 object-cover rounded-lg border border-abu-muda" alt="Pratinjau gambar">
                @error('gambar') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-3">
                <a href="{{ route('super_admin.hadiah.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('gambar').addEventListener('change', (e) => {
        const file = e.target.files[0];
        const img = document.getElementById('previewGambar');
        if (!file) { img.classList.add('hidden'); return; }
        img.src = URL.createObjectURL(file);
        img.classList.remove('hidden');
    });
</script>
@endsection