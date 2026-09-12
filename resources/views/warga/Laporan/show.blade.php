<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Laporan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <h3 class="text-xl font-semibold mb-1">{{ $laporan->judul }}</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Dilaporkan pada {{ $laporan->created_at->format('d M Y, H:i') }}
                </p>

                @if ($laporan->foto)
                    <img src="{{ Storage::url($laporan->foto) }}" alt="Foto laporan"
                         class="w-full max-h-96 object-cover rounded mb-4">
                @endif

                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <span class="text-gray-500">Kategori</span>
                        <p class="font-medium">{{ $laporan->kategori->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Instansi Tujuan</span>
                        <p class="font-medium">{{ $laporan->instansi->nama_instansi ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Tingkat Prioritas</span>
                        <p class="font-medium">{{ $laporan->tingkat_prioritas }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Status</span>
                        <p class="font-medium">{{ $laporan->status }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Alamat</span>
                        <p class="font-medium">{{ $laporan->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Diverifikasi Oleh</span>
                        <p class="font-medium">{{ $laporan->diverifikasiOleh->nama ?? 'Belum diverifikasi' }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <span class="text-gray-500 text-sm">Deskripsi</span>
                    <p class="mt-1">{{ $laporan->deskripsi }}</p>
                </div>

                <div class="mb-4">
                    <span class="text-gray-500 text-sm">Koordinat Lokasi</span>
                    <p class="mt-1">{{ $laporan->latitude }}, {{ $laporan->longitude }}</p>
                    <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}"
                       target="_blank" class="text-indigo-600 text-sm hover:underline">
                        Lihat di Google Maps
                    </a>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('warga.laporan.index') }}"
                       class="px-4 py-2 rounded border">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>