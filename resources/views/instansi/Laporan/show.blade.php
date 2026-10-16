@extends('template.layout')

@section('title', 'Tinjau Laporan')

@section('content')
@php
    $gambar = $laporan->foto;
    $lampiranPdf = false;
    if (! $gambar && $laporan->lampiran) {
        $ext = strtolower(pathinfo($laporan->lampiran, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) { $gambar = $laporan->lampiran; }
        else { $lampiranPdf = true; }
    }
@endphp

<div class="p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">Tinjau laporan sebelum diverifikasi.</p>
            <a href="{{ route('instansi.laporan.index') }}" class="text-sm text-gray-500 hover:underline">← Kembali</a>
        </div>

        <div class="px-6 pb-6 space-y-5 text-sm text-gray-700">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div><p class="text-xs text-gray-500">Kode Lacak</p><p class="font-mono">{{ $laporan->kode_lacak ?? '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Pelapor</p><p class="font-semibold">{{ $laporan->nama_pelapor }}</p></div>
                <div><p class="text-xs text-gray-500">Kategori</p>
                    <p class="font-semibold">{{ $laporan->kategori->nama_kategori ?? '-' }}
                        @if ($laporan->kategori_lainnya) ({{ $laporan->kategori_lainnya }}) @endif
                    </p>
                </div>
                <div><p class="text-xs text-gray-500">Jenis Laporan</p><p class="font-semibold">{{ $laporan->jenis_laporan ?? '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Tanggal Kejadian</p>
                    <p class="font-semibold">{{ $laporan->tanggal_kejadian ? \Carbon\Carbon::parse($laporan->tanggal_kejadian)->translatedFormat('d F Y') : '-' }}</p>
                </div>
                <div><p class="text-xs text-gray-500">Kecamatan</p><p class="font-semibold">{{ $laporan->kecamatan ?: '-' }}</p></div>
                <div><p class="text-xs text-gray-500">Koordinat GPS</p>
                    <p class="font-semibold">
                        {{ $laporan->latitude }}, {{ $laporan->longitude }}
                        <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank"
                           class="ml-2 text-cyan-4 hover:underline">Buka peta</a>
                    </p>
                </div>
                <div><p class="text-xs text-gray-500">Diverifikasi Oleh</p><p class="font-semibold">{{ $laporan->diverifikasiOleh->name ?? '-' }}</p></div>
            </div>

            <div>
                <p class="text-xs text-gray-500">Judul</p>
                <p class="font-semibold text-cyan-6 text-base">{{ $laporan->judul }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Deskripsi</p>
                <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line">{{ $laporan->deskripsi }}</div>
            </div>

            @if ($laporan->alamat)
                <div>
                    <p class="text-xs text-gray-500 mb-1">Alamat / Patokan</p>
                    <div class="rounded-lg border border-abu-muda bg-gray-50 p-4">{{ $laporan->alamat }}</div>
                </div>
            @endif

            <div>
                <p class="text-xs text-gray-500 mb-1">Foto / Lampiran</p>
                @if ($gambar)
                    <img src="{{ asset('storage/' . $gambar) }}" alt="Foto laporan"
                         class="max-h-72 rounded-xl border border-abu-muda object-contain">
                @elseif ($lampiranPdf)
                    <a href="{{ asset('storage/' . $laporan->lampiran) }}" target="_blank"
                       class="text-cyan-4 font-semibold hover:underline">Buka lampiran (PDF)</a>
                @else
                    <p class="text-gray-400">Tidak ada foto</p>
                @endif
            </div>

            <div class="flex flex-wrap gap-10">
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Prioritas</p>
                    <span @class([
                        'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                        'bg-merah/10 text-merah' => $laporan->tingkat_prioritas === 'Kritis',
                        'bg-oranye/10 text-oranye' => $laporan->tingkat_prioritas === 'Sedang',
                        'bg-kuning/20 text-abu-tua' => $laporan->tingkat_prioritas === 'Rendah',
                    ])>{{ $laporan->tingkat_prioritas }}</span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Status</p>
                    <span @class([
                        'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                        'bg-abu-muda text-abu-tua' => $laporan->status === 'Menunggu',
                        'bg-cyan-3/20 text-cyan-6' => $laporan->status === 'Diverifikasi',
                        'bg-oranye/10 text-oranye' => $laporan->status === 'Diproses',
                        'bg-hijau/10 text-hijau' => $laporan->status === 'Selesai',
                        'bg-merah/10 text-merah' => $laporan->status === 'Ditolak',
                    ])>{{ $laporan->status }}</span>
                </div>
            </div>

            @if ($laporan->status === 'Menunggu')
                <form action="{{ route('instansi.laporan.verify', $laporan->id) }}" method="POST"
                      class="rounded-xl border border-abu-muda bg-gray-50 p-4 space-y-4">
                    @csrf


                    <div class="max-w-xs">
                        <label for="tingkat_prioritas" class="block text-sm font-semibold text-cyan-6 mb-1.5">Tingkat Prioritas</label>
                        <select id="tingkat_prioritas" name="tingkat_prioritas"
                                class="w-full h-11 px-4 rounded-lg border border-abu-muda bg-white text-sm focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">
                            @foreach (['Kritis', 'Sedang', 'Rendah'] as $p)
                                <option value="{{ $p }}" @selected($laporan->tingkat_prioritas === $p)>{{ $p }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Bisa disesuaikan dengan rekomendasi AI.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" name="status" value="Diverifikasi"
                                class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                            Verifikasi (Valid)
                        </button>
                        <button type="submit" name="status" value="Ditolak"
                                onclick="return confirm('Yakin ingin menolak laporan ini?')"
                                class="px-6 py-2.5 rounded-lg border border-merah text-merah text-sm font-bold hover:bg-merah/10 transition">
                            Tolak
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection