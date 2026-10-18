<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Konten Edukasi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6 sm:p-8">

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-300 text-red-700 rounded-lg text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('super_admin.konten-edukasi.update', $konten->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <input type="text" name="judul" value="{{ old('judul', $konten->judul) }}"
                               class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               required>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>

                        @if ($konten->thumbnail)
                            <img src="{{ asset('storage/' . $konten->thumbnail) }}" alt="Thumbnail saat ini"
                                 class="w-48 h-32 object-cover rounded-lg mb-3 border border-gray-200">
                        @endif

                        <input type="file" name="thumbnail" accept="image/*"
                               class="w-full border border-gray-300 rounded-lg p-2.5 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-600 file:text-sm">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti thumbnail.</p>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <input type="text" name="kategori" value="{{ old('kategori', $konten->kategori) }}"
                               class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               required>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Isi Konten</label>
                        <textarea name="isi" rows="8"
                                  class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                  required>{{ old('isi', $konten->isi) }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="draft" {{ old('status', $konten->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="publish" {{ old('status', $konten->status) == 'publish' ? 'selected' : '' }}>Publish</option>
                            <option value="nonaktif" {{ old('status', $konten->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('super_admin.konten-edukasi.index') }}"
                           class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Simpan Perubahan</button>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>