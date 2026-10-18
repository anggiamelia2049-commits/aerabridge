@extends('template.layout')

@section('content')
@php
    $field = 'w-full h-11 px-4 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
    $area  = 'w-full px-4 py-3 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
    $label = 'block text-sm font-semibold text-cyan-6 mb-1.5';
@endphp

<div class="p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow overflow-hidden">

        <form method="POST" action="{{ route('super_admin.instansi.update', $instansi->id) }}" enctype="multipart/form-data" class="px-6 pb-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_instansi" class="{{ $label }}">Nama Instansi <span class="text-merah">*</span></label>
                <input type="text" id="nama_instansi" name="nama_instansi" maxlength="100"
                       value="{{ old('nama_instansi', $instansi->nama_instansi) }}" class="{{ $field }}" required>
                @error('nama_instansi') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="no_telp" class="{{ $label }}">No. Telp <span class="text-merah">*</span></label>
                    <input type="text" id="no_telp" name="no_telp" maxlength="20"
                           value="{{ old('no_telp', $instansi->no_telp) }}" class="{{ $field }}" required>
                    @error('no_telp') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="{{ $label }}">Email</label>
                    <input type="email" id="email" name="email" maxlength="100"
                           value="{{ old('email', $instansi->email) }}" class="{{ $field }}">
                    @error('email') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="status" class="{{ $label }}">Status <span class="text-merah">*</span></label>
                <select id="status" name="status" class="{{ $field }}" required>
                    @foreach (['Aktif', 'Nonaktif'] as $s)
                        <option value="{{ $s }}" @selected(old('status', $instansi->status) === $s)>{{ $s }}</option>
                    @endforeach
                </select>
                @error('status') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="alamat" class="{{ $label }}">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3" class="{{ $area }}">{{ old('alamat', $instansi->alamat) }}</textarea>
                @error('alamat') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="deskripsi" class="{{ $label }}">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="{{ $area }}">{{ old('deskripsi', $instansi->deskripsi) }}</textarea>
                @error('deskripsi') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="logo" class="{{ $label }}">Logo</label>

                @if ($instansi->logo)
                    <div class="mb-3">
                        <p class="text-xs text-gray-500 mb-1">Logo saat ini</p>
                        <img src="{{ asset('storage/' . $instansi->logo) }}" alt="{{ $instansi->nama_instansi }}"
                             class="w-32 h-32 object-contain rounded-lg border border-abu-muda">
                    </div>
                @endif

                <input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png"
                       class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-cyan-3/20 file:text-cyan-6 file:font-semibold">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti logo. JPG atau PNG, maksimal 2 MB.</p>
                <img id="previewLogo" class="hidden mt-3 w-32 h-32 object-contain rounded-lg border border-abu-muda" alt="Pratinjau logo baru">
                @error('logo') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-200">
    <a href="{{ route('super_admin.instansi.index') }}"
       class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-semibold text-gray-700 !no-underline hover:!no-underline hover:bg-gray-50 transition">Batal</a>
    <button type="submit"
            class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Simpan Perubahan</button>
</div>
        </form>
    </div>
</div>

<script>
    document.getElementById('logo').addEventListener('change', (e) => {
        const file = e.target.files[0];
        const img = document.getElementById('previewLogo');
        if (!file) { img.classList.add('hidden'); return; }
        img.src = URL.createObjectURL(file);
        img.classList.remove('hidden');
    });
</script>
@endsection
