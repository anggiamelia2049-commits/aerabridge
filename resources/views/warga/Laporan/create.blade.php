<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Laporan Baru
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('warga.laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Kategori Kerusakan</label>
                        <select name="kategori_id" class="w-full border rounded p-2" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Instansi Tujuan</label>
                        <select name="instansi_id" class="w-full border rounded p-2" required>
                            <option value="">-- Pilih Instansi --</option>
                            @foreach ($instansis as $instansi)
                                <option value="{{ $instansi->id }}" {{ old('instansi_id') == $instansi->id ? 'selected' : '' }}>
                                    {{ $instansi->nama_instansi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Judul Laporan</label>
                        <input type="text" name="judul" value="{{ old('judul') }}"
                               class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="w-full border rounded p-2" required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Foto Kerusakan</label>
                        <input type="file" name="foto" accept="image/*" capture="environment"
                               class="w-full border rounded p-2" required>
                        <p class="text-xs text-gray-500 mt-1">Format JPG/PNG, maksimal 5MB.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Alamat / Patokan Lokasi</label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}"
                               class="w-full border rounded p-2" placeholder="Contoh: Depan Indomaret, Jl. Merdeka">
                    </div>

                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium mb-1">Latitude</label>
                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                                   class="w-full border rounded p-2 bg-gray-100" readonly required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Longitude</label>
                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                                   class="w-full border rounded p-2 bg-gray-100" readonly required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <button type="button" onclick="ambilLokasi()"
                                class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                            📍 Ambil Lokasi Saat Ini
                        </button>
                        <span id="statusLokasi" class="text-sm text-gray-500 ml-2"></span>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('warga.laporan.index') }}"
                           class="px-4 py-2 rounded border">Batal</a>
                        <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function ambilLokasi() {
            const status = document.getElementById('statusLokasi');
            if (!navigator.geolocation) {
                status.textContent = 'Browser tidak mendukung Geolocation.';
                return;
            }
            status.textContent = 'Mengambil lokasi...';
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    status.textContent = 'Lokasi berhasil dikunci ✅';
                },
                (error) => {
                    status.textContent = 'Gagal mengambil lokasi: ' + error.message;
                }
            );
        }
    </script>
</x-app-layout>