<x-app-layout>
    @php
        $prioritasClasses = [
            'kritis' => 'bg-merah/10 text-merah',
            'sedang' => 'bg-oranye/10 text-oranye',
            'rendah' => 'bg-kuning/20 text-abu-tua',
        ];

        $statusClasses = [
            'menunggu'         => 'bg-abu-muda text-abu-tua',
            'terverifikasi ai' => 'bg-cyan-3/10 text-cyan-4',
            'diverifikasi'     => 'bg-cyan-3/20 text-cyan-6',
            'diproses'         => 'bg-oranye/10 text-oranye',
            'selesai'          => 'bg-hijau/10 text-hijau',
            'ditolak'          => 'bg-merah/10 text-merah',
        ];

        $prioritasClass = $prioritasClasses[strtolower((string) $laporan->tingkat_prioritas)] ?? 'bg-gray-200 text-gray-600';
        $statusClass    = $statusClasses[strtolower((string) $laporan->status)] ?? 'bg-gray-200 text-gray-600';

        // Status yang diterima controller verify()
        $opsiVerifikasi = ['Diverifikasi', 'Diproses', 'Selesai', 'Ditolak'];
    @endphp

    <div class="flex min-h-screen bg-abu-muda">
        <aside class="sticky top-0 h-screen shrink-0 self-start">@include('template.sidebar-superadmin')</aside>

        <main class="flex-1 p-6 min-w-0 space-y-6">
            @if (session('success'))
                <div class="rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-hijau font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc space-y-0.5 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Kartu utama --}}
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-5 border-b border-gray-200">
                    <div>
                        <p class="text-xs text-gray-500">Kode Lacak</p>
                        <p class="font-mono font-semibold text-gray-800">{{ $laporan->kode_lacak ?? '-' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold capitalize {{ $prioritasClass }}">
                            {{ $laporan->tingkat_prioritas ?? '-' }}
                        </span>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                            {{ $laporan->status ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="px-6 py-5">
                    <h1 class="text-lg font-bold text-gray-800">{{ $laporan->judul }}</h1>
                    <p class="mt-2 text-sm text-gray-600 whitespace-pre-line">{{ $laporan->deskripsi }}</p>
                </div>

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 px-6 pb-6 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500">Pelapor</dt>
                        <dd class="mt-1 font-semibold text-gray-700">{{ $laporan->nama_pelapor ?? optional($laporan->user)->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Jenis Laporan</dt>
                        <dd class="mt-1 font-semibold text-gray-700">{{ $laporan->jenis_laporan ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Kategori</dt>
                        <dd class="mt-1 font-semibold text-gray-700">
                            {{ optional($laporan->kategori)->nama_kategori ?? '-' }}
                            @if ($laporan->kategori_lainnya)
                                <span class="font-normal text-gray-500">({{ $laporan->kategori_lainnya }})</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Instansi</dt>
                        <dd class="mt-1 font-semibold text-gray-700">{{ optional($laporan->instansi)->nama_instansi ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Tanggal Kejadian</dt>
                        <dd class="mt-1 font-semibold text-gray-700">
                            {{ $laporan->tanggal_kejadian ? \Carbon\Carbon::parse($laporan->tanggal_kejadian)->translatedFormat('d M Y') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Diverifikasi Oleh</dt>
                        <dd class="mt-1 font-semibold text-gray-700">{{ optional($laporan->diverifikasiOleh)->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Kecamatan</dt>
                        <dd class="mt-1 font-semibold text-gray-700">{{ $laporan->kecamatan ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Alamat</dt>
                        <dd class="mt-1 font-semibold text-gray-700">{{ $laporan->alamat ?: '-' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs text-gray-500">Koordinat</dt>
                        <dd class="mt-1 font-semibold text-gray-700">
                            @if ($laporan->latitude && $laporan->longitude)
                                <span class="font-mono text-xs">{{ $laporan->latitude }}, {{ $laporan->longitude }}</span>
                                <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}"
                                   target="_blank" rel="noopener"
                                   class="ml-3 text-cyan-4 hover:underline">Lihat di peta</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Verifikasi cepat (controller: verify) --}}
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-gray-800">Ubah Status</h2>
                </div>
                <form method="POST" action="{{ route('super_admin.laporan.verify', $laporan->id) }}"
                      class="flex flex-wrap items-center gap-3 px-6 py-5">
                    @csrf
                    @method('PATCH')
                    <select name="status" required
                            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-cyan-4 focus:outline-none">
                        @foreach ($opsiVerifikasi as $s)
                            <option value="{{ $s }}" {{ old('status', $laporan->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                        Simpan Status
                    </button>
                    <p class="text-xs text-gray-500">Pemverifikasi akan tercatat atas nama Anda.</p>
                </form>
            </div>

            {{-- Foto & lampiran --}}
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-gray-800">Foto & Lampiran</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 py-5 text-sm">
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Foto</p>
                        @if ($laporan->foto)
                            <a href="{{ asset('storage/' . $laporan->foto) }}" target="_blank" rel="noopener">
                                <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto laporan"
                                     class="w-full max-w-sm rounded-lg border border-gray-200 object-cover">
                            </a>
                        @else
                            <p class="text-gray-400">Tidak ada foto</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Lampiran</p>
                        @if ($laporan->lampiran)
                            <a href="{{ asset('storage/' . $laporan->lampiran) }}" target="_blank" rel="noopener">
                                <img src="{{ asset('storage/' . $laporan->lampiran) }}" alt="Lampiran laporan"
                                     class="w-full max-w-sm rounded-lg border border-gray-200 object-cover">
                            </a>
                        @else
                            <p class="text-gray-400">Tidak ada lampiran</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Hasil deteksi AI --}}
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-gray-800">Hasil Deteksi AI</h2>
                </div>
                @if ($laporan->deteksiAi)
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 px-6 py-5 text-sm">
                        <div>
                            <dt class="text-xs text-gray-500">Objek</dt>
                            <dd class="mt-1 font-semibold text-gray-700">{{ $laporan->deteksiAi->jenis_objek }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Confidence</dt>
                            <dd class="mt-1 font-semibold text-gray-700">{{ number_format($laporan->deteksiAi->confidence * 100, 0) }}%</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Tingkat Kerusakan</dt>
                            <dd class="mt-1 font-semibold text-gray-700">{{ $laporan->deteksiAi->tingkat_kerusakan }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Estimasi Prioritas</dt>
                            <dd class="mt-1 font-semibold text-gray-700">{{ $laporan->deteksiAi->estimasi_prioritas }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-xs text-gray-500">Validasi</dt>
                            <dd class="mt-1 font-semibold text-gray-700">{{ $laporan->deteksiAi->hasil_validasi }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-xs text-gray-500">Ringkasan</dt>
                            <dd class="mt-1 text-gray-700 whitespace-pre-line">{{ $laporan->deteksiAi->response_llm }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="px-6 py-8 text-center text-sm text-gray-400">Belum ada hasil deteksi AI.</p>
                @endif
            </div>

            {{-- Aksi --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.laporan.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold transition">
                    Kembali
                </a>
                <a href="{{ route('super_admin.laporan.edit', $laporan->id) }}"
                   class="px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                    Edit
                </a>
            </div>
        </main>
    </div>
</x-app-layout>