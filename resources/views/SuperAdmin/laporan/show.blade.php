@extends('template.layout')

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

    // Verifikasi hanya untuk laporan yang belum diverifikasi
    $bisaVerifikasi = in_array($laporan->status, ['Menunggu', 'Terverifikasi AI'], true);
    $ditolak = $laporan->status === 'Ditolak';

    // Keterangan tahap: [judul, penjelasan, kelas warna]
    $infoStatus = [
        'Diverifikasi' => ['Menunggu disposisi', 'Laporan sudah diverifikasi dan menunggu instansi menugaskan tim satgas dan petugas.', 'bg-cyan-3/10 text-cyan-6'],
        'Diproses'     => ['Sedang ditindaklanjuti', 'Laporan sedang ditangani petugas lapangan.', 'bg-oranye/10 text-oranye'],
        'Selesai'      => ['Laporan selesai', 'Closing report sudah divalidasi instansi.', 'bg-hijau/10 text-hijau'],
        'Ditolak'      => ['Laporan ditolak', 'Laporan ini tidak diproses lebih lanjut.', 'bg-merah/10 text-merah'],
    ][$laporan->status] ?? null;

    // Pemverifikasi
    $pemverifikasi = $laporan->diverifikasiOleh;
    $labelPemverifikasi = $ditolak ? 'Ditolak Oleh' : 'Diverifikasi Oleh';

    // Lampiran: tampil sebagai gambar hanya jika ekstensinya gambar
    $lampiranEkstensi = $laporan->lampiran ? strtolower(pathinfo($laporan->lampiran, PATHINFO_EXTENSION)) : null;
    $lampiranGambar   = in_array($lampiranEkstensi, ['jpg', 'jpeg', 'png'], true);

    // Hasil AI: confidence bisa tersimpan 0-1 atau 0-100
    $ai = $laporan->deteksiAi;
    $confidence = null;
    if ($ai && $ai->confidence !== null) {
        $c = (float) $ai->confidence;
        $confidence = $c <= 1 ? $c * 100 : $c;
    }
@endphp

@section('title', 'Detail Laporan')

@section('content')
<div class="p-6 space-y-4">
    @if (session('success'))
        <div class="rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-hijau font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-merah font-medium">
            {{ session('error') }}
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
        <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-gray-200">
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

        <div class="px-6 py-4">
            <h1 class="text-lg font-bold text-gray-800">{{ $laporan->judul }}</h1>
            <p class="mt-1 text-xs text-gray-500">Dilaporkan pada {{ $laporan->created_at->format('d M Y, H:i') }}</p>
            <p class="mt-2 text-sm text-gray-600 whitespace-pre-line">{{ $laporan->deskripsi }}</p>
        </div>

        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 px-6 pb-4 text-sm">
            <div>
                <dt class="text-xs text-gray-500">Pelapor</dt>
                <dd class="mt-1 font-semibold text-gray-700">{{ $laporan->nama_pelapor }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500">Jenis Laporan</dt>
                <dd class="mt-1 font-semibold text-gray-700">{{ $laporan->jenis_laporan ?: '-' }}</dd>
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
                <dt class="text-xs text-gray-500">Instansi Tujuan</dt>
                <dd class="mt-1 font-semibold text-gray-700">{{ optional($laporan->instansi)->nama_instansi ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500">Tanggal Kejadian</dt>
                <dd class="mt-1 font-semibold text-gray-700">
                    {{ $laporan->tanggal_kejadian ? \Carbon\Carbon::parse($laporan->tanggal_kejadian)->translatedFormat('d M Y') : '-' }}
                </dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500">{{ $labelPemverifikasi }}</dt>
                <dd class="mt-1 font-semibold text-gray-700">
                    @if ($pemverifikasi)
                        {{ $pemverifikasi->nama }}
                        <span class="font-normal text-gray-500">({{ ucwords(str_replace('_', ' ', $pemverifikasi->role)) }})</span>
                    @else
                        <span class="font-normal text-gray-400">Belum diverifikasi</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500">Lokasi</dt>
                <dd class="mt-1 font-semibold text-gray-700">
                    {{ $laporan->kecamatan ? 'Kecamatan ' . $laporan->kecamatan : ($laporan->alamat ?: '-') }}
                </dd>
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

    {{-- Keterangan tahap --}}
    @if ($infoStatus)
        <div class="rounded-2xl px-6 py-4 text-sm {{ $infoStatus[2] }}">
            <p class="font-bold">{{ $infoStatus[0] }}</p>
            <p class="mt-0.5">{{ $infoStatus[1] }}</p>
        </div>
    @endif

    {{-- Verifikasi laporan: hanya untuk status Menunggu / Terverifikasi AI --}}
    @if ($bisaVerifikasi)
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-bold text-gray-800">Verifikasi Laporan</h2>
            </div>
            <form method="POST" action="{{ route('super_admin.laporan.verify', $laporan->id) }}"
                  class="flex flex-wrap items-center gap-3 px-6 py-4">
                @csrf
                @method('PUT')
                <button type="submit" name="status" value="Diverifikasi"
                        class="px-5 py-2 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                    Setujui
                </button>
                <button type="submit" name="status" value="Ditolak"
                        onclick="return confirm('Tolak laporan ini?')"
                        class="px-5 py-2 rounded-lg bg-merah hover:opacity-90 text-white text-sm font-bold transition">
                    Tolak
                </button>
                <p class="text-xs text-gray-500">Pemverifikasi akan tercatat atas nama Anda. Laporan yang disetujui lanjut ke tahap disposisi instansi.</p>
            </form>
        </div>
    @endif

    {{-- Foto & lampiran --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-800">Foto &amp; Lampiran</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-6 py-4 text-sm">
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
                    @if ($lampiranGambar)
                        <a href="{{ asset('storage/' . $laporan->lampiran) }}" target="_blank" rel="noopener">
                            <img src="{{ asset('storage/' . $laporan->lampiran) }}" alt="Lampiran laporan"
                                 class="w-full max-w-sm rounded-lg border border-gray-200 object-cover">
                        </a>
                    @else
                        <a href="{{ asset('storage/' . $laporan->lampiran) }}" target="_blank" rel="noopener"
                           class="text-cyan-4 hover:underline">Buka lampiran</a>
                    @endif
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
        @if ($ai)
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 px-6 py-4 text-sm">
                <div>
                    <dt class="text-xs text-gray-500">Objek</dt>
                    <dd class="mt-1 font-semibold text-gray-700">{{ $ai->jenis_objek ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Confidence</dt>
                    <dd class="mt-1 font-semibold text-gray-700">
                        {{ $confidence !== null ? number_format($confidence, 0) . '%' : '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Tingkat Kerusakan</dt>
                    <dd class="mt-1 font-semibold text-gray-700">{{ $ai->tingkat_kerusakan ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Estimasi Prioritas</dt>
                    <dd class="mt-1 font-semibold text-gray-700">{{ $ai->estimasi_prioritas ?: '-' }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-xs text-gray-500">Validasi</dt>
                    <dd class="mt-1 font-semibold text-gray-700">{{ $ai->hasil_validasi ?: '-' }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-xs text-gray-500">Ringkasan</dt>
                    <dd class="mt-1 text-gray-700 whitespace-pre-line">{{ $ai->response_llm ?: '-' }}</dd>
                </div>
            </dl>
        @else
            <p class="px-6 py-8 text-center text-sm text-gray-400">Belum ada hasil deteksi AI.</p>
        @endif
    </div>

    {{-- Penugasan lapangan: untuk laporan yang sudah lewat verifikasi (bukan ditolak) --}}
    @if (! $bisaVerifikasi && ! $ditolak)
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-bold text-gray-800">Penugasan Lapangan</h2>
            </div>

            @forelse ($laporan->penugasan as $p)
                @php
                    // Supaya hitung SLA tidak memicu query tambahan
                    $p->setRelation('laporan', $laporan);
                    $warnaSla = $p->isOverdue() ? 'text-merah' : 'text-gray-700';
                @endphp
                <div class="px-6 py-4 {{ ! $loop->last ? 'border-b border-gray-100' : '' }}">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        <div>
                            <dt class="text-xs text-gray-500">Tim Satgas</dt>
                            <dd class="mt-1 font-semibold text-gray-700">{{ optional($p->timSatgas)->nama_tim ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Petugas</dt>
                            <dd class="mt-1 font-semibold text-gray-700">{{ optional($p->petugas)->nama ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Status Penugasan</dt>
                            <dd class="mt-1 font-semibold text-gray-700">{{ $p->labelStatus() }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">SLA</dt>
                            <dd class="mt-1 font-semibold {{ $warnaSla }}">{{ $p->teksSla() }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Tanggal Penugasan</dt>
                            <dd class="mt-1 font-semibold text-gray-700">{{ $p->tanggal_penugasan?->translatedFormat('d M Y, H:i') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Tanggal Selesai</dt>
                            <dd class="mt-1 font-semibold text-gray-700">{{ $p->tanggal_selesai?->translatedFormat('d M Y, H:i') ?? '-' }}</dd>
                        </div>

                        @if ($p->catatan)
                            <div class="md:col-span-2">
                                <dt class="text-xs text-gray-500">Catatan Instansi</dt>
                                <dd class="mt-1 text-gray-700 whitespace-pre-line">{{ $p->catatan }}</dd>
                            </div>
                        @endif

                        @if ($p->catatan_penyelesaian || $p->foto_hasil)
                            <div class="md:col-span-2">
                                <dt class="text-xs text-gray-500">Closing Report Petugas</dt>
                                <dd class="mt-1 space-y-3">
                                    @if ($p->catatan_penyelesaian)
                                        <p class="text-gray-700 whitespace-pre-line">{{ $p->catatan_penyelesaian }}</p>
                                    @endif
                                    @if ($p->foto_hasil)
                                        <a href="{{ asset('storage/' . $p->foto_hasil) }}" target="_blank" rel="noopener">
                                            <img src="{{ asset('storage/' . $p->foto_hasil) }}" alt="Foto hasil"
                                                 class="w-full max-w-sm rounded-lg border border-gray-200 object-cover">
                                        </a>
                                    @endif
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @empty
                <p class="px-6 py-8 text-center text-sm text-gray-400">Belum ada penugasan untuk laporan ini.</p>
            @endforelse
        </div>
    @endif

    {{-- Aksi --}}
    <div>
        <a href="{{ url()->previous() === url()->current() ? route('super_admin.laporan.index') : url()->previous() }}"
           class="inline-block px-5 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold transition">
            &larr; Kembali
        </a>
    </div>
</div>
@endsection