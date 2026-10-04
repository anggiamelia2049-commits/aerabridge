<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-cyan-6 leading-tight">
            Detail Laporan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6 sm:p-8">

                @if (session('success'))
                    <div class="mb-6 p-4 bg-hijau/10 border border-hijau text-hijau rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                    <h3 class="text-xl font-semibold text-cyan-6">{{ $laporan->judul }}</h3>
                    <span @class([
                        'px-2.5 py-1 rounded-full text-xs font-semibold',
                        'bg-merah/10 text-merah' => $laporan->tingkat_prioritas === 'Krisis',
                        'bg-oranye/10 text-oranye' => $laporan->tingkat_prioritas === 'Sedang',
                        'bg-kuning/20 text-abu-tua' => $laporan->tingkat_prioritas === 'Rendah',
                    ])>
                        {{ $laporan->tingkat_prioritas }}
                    </span>
                </div>
                <p class="text-sm text-abu-tua/70 mb-5">
                    Dilaporkan pada {{ $laporan->created_at->format('d M Y, H:i') }}
                </p>

                @if ($laporan->foto)
                    <img src="{{ Storage::url($laporan->foto) }}" alt="Foto laporan"
                         class="w-full max-h-96 object-cover rounded-lg mb-5 border border-abu-muda">
                @endif

                @if ($laporan->lampiran)
                    <div class="mb-5">
                        <span class="text-abu-tua/70 text-sm">Lampiran Tambahan</span>
                        <div class="mt-1">
                            @php
                                $ekstensi = pathinfo($laporan->lampiran, PATHINFO_EXTENSION);
                            @endphp

                            @if (in_array(strtolower($ekstensi), ['jpg', 'jpeg', 'png']))
                                <img src="{{ Storage::url($laporan->lampiran) }}" alt="Lampiran"
                                     class="w-full max-h-64 object-cover rounded-lg border border-abu-muda">
                            @else
                                <a href="{{ Storage::url($laporan->lampiran) }}" target="_blank"
                                   class="inline-flex items-center gap-1 text-cyan-4 hover:text-cyan-6 hover:underline text-sm">
                                    📎 Lihat Lampiran (PDF)
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5 text-sm">
                    <div>
                        <span class="text-abu-tua/70">Kategori</span>
                        <p class="font-medium text-abu-tua">{{ $laporan->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-abu-tua/70">Instansi Tujuan</span>
                        <p class="font-medium text-abu-tua">{{ $laporan->instansi->nama_instansi ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-abu-tua/70">Status</span>
                        <p class="mt-0.5">
                            <span @class([
                                'px-2.5 py-1 rounded-full text-xs font-semibold',
                                'bg-abu-muda text-abu-tua' => $laporan->status === 'Menunggu',
                                'bg-cyan-3/20 text-cyan-6' => $laporan->status === 'Diverifikasi',
                                'bg-oranye/10 text-oranye' => $laporan->status === 'Diproses',
                                'bg-hijau/10 text-hijau' => $laporan->status === 'Selesai',
                                'bg-merah/10 text-merah' => $laporan->status === 'Ditolak',
                            ])>
                                {{ $laporan->status }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <span class="text-abu-tua/70">Alamat</span>
                        <p class="font-medium text-abu-tua">{{ $laporan->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-abu-tua/70">Diverifikasi Oleh</span>
                        <p class="font-medium text-abu-tua">{{ $laporan->diverifikasiOleh->nama ?? 'Belum diverifikasi' }}</p>
                    </div>
                </div>

                <div class="mb-5">
                    <span class="text-abu-tua/70 text-sm">Deskripsi</span>
                    <p class="mt-1 text-abu-tua">{{ $laporan->deskripsi }}</p>
                </div>

                <div class="mb-6">
                    <span class="text-abu-tua/70 text-sm">Koordinat Lokasi</span>
                    <p class="mt-1 text-abu-tua">{{ $laporan->latitude }}, {{ $laporan->longitude }}</p>
                    <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}"
                       target="_blank" class="text-cyan-4 hover:text-cyan-6 text-sm hover:underline">
                        Lihat di Google Maps
                    </a>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('warga.laporan.index') }}"
                       class="px-4 py-2 rounded-lg border border-abu-muda text-abu-tua text-sm font-medium hover:bg-abu-muda/20 transition">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>