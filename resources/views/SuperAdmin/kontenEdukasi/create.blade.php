<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-semibold text-[#0C343D] mb-1">Tambah Konten Edukasi</h1>
        <p class="text-sm text-[#4A4A4A] mb-6">
            Konten berstatus <b>publish</b> akan tampil untuk warga. Warga yang menyelesaikan
            membacanya mendapat <b>+10 poin kontribusi</b>.
        </p>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-3 text-sm text-[#E53935]">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('super_admin.konten-edukasi.store') }}" method="POST"
              enctype="multipart/form-data"
              class="space-y-5 rounded-xl bg-white p-6 shadow border border-[#D9D9D9]">
            @csrf

            <div>
                <label for="judul" class="block text-sm font-medium text-[#4A4A4A] mb-1">Judul *</label>
                <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required
                       class="w-full rounded-lg border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]">
            </div>

            <div>
                <label for="kategori" class="block text-sm font-medium text-[#4A4A4A] mb-1">Kategori *</label>
                <input type="text" id="kategori" name="kategori" value="{{ old('kategori') }}"
                       list="daftar-kategori" required
                       class="w-full rounded-lg border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]">
                <datalist id="daftar-kategori">
                    <option value="Jalan Berlubang">
                    <option value="Saluran Air (Got)">
                    <option value="Fasilitas Umum">
                    <option value="Tips Merawat Fasilitas Publik">
                </datalist>
            </div>

            <div>
                <label for="thumbnail" class="block text-sm font-medium text-[#4A4A4A] mb-1">
                    Thumbnail (opsional, JPG/PNG maks 2 MB)
                </label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/png,image/jpeg"
                       class="block w-full text-sm text-[#4A4A4A]">
            </div>

            <div>
                <label for="isi" class="block text-sm font-medium text-[#4A4A4A] mb-1">Isi Konten *</label>
                <textarea id="isi" name="isi" rows="10" required
                          class="w-full rounded-lg border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]">{{ old('isi') }}</textarea>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-[#4A4A4A] mb-1">Status</label>
                <select id="status" name="status"
                        class="w-full rounded-lg border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]">
                    <option value="draft"    @selected(old('status', 'draft') === 'draft')>Draft (belum tampil)</option>
                    <option value="publish"  @selected(old('status') === 'publish')>Publish (tampil untuk warga)</option>
                    <option value="nonaktif" @selected(old('status') === 'nonaktif')>Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('super_admin.konten-edukasi.index') }}"
                   class="rounded-lg border border-[#D9D9D9] px-4 py-2 text-sm text-[#4A4A4A] hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="rounded-lg bg-[#45818E] px-5 py-2 text-sm font-medium text-white hover:bg-[#0C343D]">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>