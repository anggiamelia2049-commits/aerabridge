@extends('template.layout')

@section('title', 'Tambah Konten Edukasi')

@section('content')
<div class="p-6">

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-merah">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <form action="{{ route('super_admin.konten-edukasi.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="px-6 py-6 space-y-5">

                <div>
                    <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">
                        Judul <span class="text-merah">*</span>
                    </label>
                    <input type="text" id="judul" name="judul"
                           value="{{ old('judul') }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-cyan-4 focus:ring-2 focus:ring-cyan-4/20 @error('judul') border-merah @enderror">
                    @error('judul')
                        <p class="mt-1 text-xs text-merah">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kategori <span class="text-merah">*</span>
                    </label>
                    <input type="text" id="kategori" name="kategori"
                           value="{{ old('kategori') }}"
                           list="daftar-kategori" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-cyan-4 focus:ring-2 focus:ring-cyan-4/20 @error('kategori') border-merah @enderror">
                    <datalist id="daftar-kategori">
                        <option value="Jalan Berlubang">
                        <option value="Saluran Air (Got)">
                        <option value="Fasilitas Umum">
                        <option value="Tips Merawat Fasilitas Publik">
                    </datalist>
                    @error('kategori')
                        <p class="mt-1 text-xs text-merah">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-2">
                        Thumbnail <span class="font-normal text-gray-400">(opsional, JPG/PNG maks 2 MB)</span>
                    </label>
                    <input type="file" id="thumbnail" name="thumbnail"
                           accept="image/png,image/jpeg"
                           class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
                    @error('thumbnail')
                        <p class="mt-1 text-xs text-merah">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="isi" class="block text-sm font-semibold text-gray-700 mb-2">
                        Isi Konten <span class="text-merah">*</span>
                    </label>
                    <textarea id="isi" name="isi" rows="10" required
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-cyan-4 focus:ring-2 focus:ring-cyan-4/20 @error('isi') border-merah @enderror">{{ old('isi') }}</textarea>
                    @error('isi')
                        <p class="mt-1 text-xs text-merah">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status
                    </label>
                    <select id="status" name="status"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-cyan-4 focus:ring-2 focus:ring-cyan-4/20">
                        <option value="draft"    @selected(old('status', 'draft') === 'draft')>Draft (belum tampil)</option>
                        <option value="publish"  @selected(old('status') === 'publish')>Publish (tampil untuk warga)</option>
                        <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-merah">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Tombol di pojok kanan bawah --}}
            <div class="flex items-center justify-end gap-3 px-6 pb-6">
                <a href="{{ route('super_admin.konten-edukasi.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold no-underline hover:no-underline transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
