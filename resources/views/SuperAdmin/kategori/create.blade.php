
@extends('template.layout')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="px-6 py-4">
        </div>

        <form action="{{ route('super_admin.kategori.store') }}" method="POST">
            @csrf

            <div class="px-6 pb-6 space-y-5">
                @if ($errors->any())
                    <div class="rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-merah">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="nama_kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Kategori
                    </label>
                    <input type="text" id="nama_kategori" name="nama_kategori"
                           value="{{ old('nama_kategori') }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-cyan-4 focus:ring-2 focus:ring-cyan-4/20 outline-none">
                </div>

                <div>
                    <label for="icon" class="block text-sm font-semibold text-gray-700 mb-2">
                        Icon
                    </label>
                    <input type="text" id="icon" name="icon"
                           value="{{ old('icon') }}" placeholder="Contoh: 🕳️" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-cyan-4 focus:ring-2 focus:ring-cyan-4/20 outline-none">
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" required
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-cyan-4 focus:ring-2 focus:ring-cyan-4/20 outline-none">{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label for="warna_marker" class="block text-sm font-semibold text-gray-700 mb-2">
                        Warna Marker
                    </label>
                    <input type="text" id="warna_marker" name="warna_marker"
                           value="{{ old('warna_marker') }}" placeholder="#FF0000" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-cyan-4 focus:ring-2 focus:ring-cyan-4/20 outline-none">
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status
                    </label>
                    <select id="status" name="status" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-cyan-4 focus:ring-2 focus:ring-cyan-4/20 outline-none">
                        <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

                        {{-- Tombol di kanan bawah, tanpa garis pemisah --}}
            <div class="flex items-center justify-end gap-3 px-6 pt-4 pb-6">
                <a href="{{ route('super_admin.kategori.index') }}"
                   class="px-5 py-2.5 rounded-lg bg-white border border-gray-300 text-gray-700 text-sm font-bold no-underline hover:no-underline transition hover:bg-gray-100">
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
