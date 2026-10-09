@extends('template.layout')

@section('title', 'Detail Penugasan')

@section('content')

@php
    $statusClass = match ($penugasan->status) {
        'ditugaskan'   => 'bg-cyan-3/20 text-cyan-6',
        'dalam_proses' => 'bg-oranye/10 text-oranye',
        'selesai'      => 'bg-hijau/10 text-hijau',
        'dibatalkan'   => 'bg-gray-200 text-abu-tua',
        default        => 'bg-gray-100 text-abu-tua',
    };

    $laporan   = $penugasan->laporan;
    $prioritas = $laporan->tingkat_prioritas ?? null;
    $prioritasClass = match ($prioritas) {
        'Krisis', 'Kritis' => 'bg-merah/10 text-merah',
        'Sedang'           => 'bg-oranye/10 text-oranye',
        default            => 'bg-kuning/20 text-yellow-700',
    };

    // SLA dihitung langsung dari model, tidak bergantung pada variabel controller
    $overdue = $penugasan->isOverdue();
    $batas   = $penugasan->batasWaktuSla();
    $sisa    = $penugasan->sisaWaktuSla();
    $berjalan = in_array($penugasan->status, ['ditugaskan', 'dalam_proses'], true);

    $formatMenit = function (int $menit) {
        $abs = abs($menit);
        $hari = intdiv($abs, 1440);
        $jam  = intdiv($abs % 1440, 60);
        $mnt  = $abs % 60;
        return trim(($hari ? $hari . ' hari ' : '') . ($jam ? $jam . ' jam ' : '') . $mnt . ' menit');
    };

    $adaKoordinat = ($laporan->latitude ?? null) && ($laporan->longitude ?? null);
@endphp

<div class="mb-5 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-cyan-6">Detail Penugasan</h1>
    <a href="{{ route('instansi.penugasan.index') }}" class="text-sm font-medium text-cyan-4 hover:text-cyan-6">
        &larr; Kembali
    </a>
</div>

@if (session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <div class="space-y-6 lg:col-span-2">

        {{-- Informasi penugasan --}}
        <div class="rounded-lg bg-white p-6 shadow">
            <h2 class="mb-4 text-base font-semibold text-cyan-6">Informasi Penugasan</h2>

            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 text-sm sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-xs text-abu-tua">Laporan</dt>
                    <dd class="mt-0.5 font-medium text-cyan-6">{{ $laporan->judul ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-abu-tua">Kategori</dt>
                    <dd class="mt-0.5">{{ $laporan->kategori->nama_kategori ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-abu-tua">Prioritas</dt>
                    <dd class="mt-0.5">
                        @if ($prioritas)
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $prioritasClass }}">{{ $prioritas }}</span>
                        @else
                            -
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-abu-tua">Tim Satgas</dt>
                    <dd class="mt-0.5">{{ $penugasan->timSatgas->nama_tim ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-abu-tua">Petugas Lapangan</dt>
                    <dd class="mt-0.5">{{ $penugasan->petugas->nama ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-abu-tua">Status</dt>
                    <dd class="mt-0.5">
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $penugasan->status)) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-abu-tua">Tanggal Penugasan</dt>
                    <dd class="mt-0.5">{{ optional($penugasan->tanggal_penugasan)->format('d M Y H:i') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-abu-tua">Tanggal Selesai</dt>
                    <dd class="mt-0.5">{{ optional($penugasan->tanggal_selesai)->format('d M Y H:i') ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs text-abu-tua">Catatan Teknis</dt>
                    <dd class="mt-0.5">{{ $penugasan->catatan ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Dokumentasi & lokasi laporan (sesuai alur proposal: meninjau dokumentasi & koordinat) --}}
        <div class="rounded-lg bg-white p-6 shadow">
            <h2 class="mb-4 text-base font-semibold text-cyan-6">Dokumentasi & Lokasi Laporan</h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    @if (! empty($laporan->foto))
                        <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto laporan"
                             class="w-full rounded-lg border border-gray-200 object-cover">
                    @else
                        <div class="flex h-40 items-center justify-center rounded-lg bg-abu-muda/40 text-sm text-abu-tua">
                            Tidak ada foto
                        </div>
                    @endif
                </div>

                <div class="text-sm">
                    <p class="text-xs text-abu-tua">Alamat</p>
                    <p class="mt-0.5">{{ $laporan->alamat ?? '-' }}</p>

                    @if ($adaKoordinat)
                        <p class="mt-3 text-xs text-abu-tua">Koordinat</p>
                        <p class="mt-0.5">{{ $laporan->latitude }}, {{ $laporan->longitude }}</p>

                        <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}"
                           target="_blank" rel="noopener"
                           class="mt-4 inline-block rounded-lg bg-cyan-4 px-4 py-2 text-sm font-medium text-white hover:bg-cyan-6">
                            Lihat di Peta
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- SLA --}}
    <div class="h-fit rounded-lg bg-white p-6 shadow {{ $overdue ? 'ring-1 ring-merah/40' : '' }}">
        <h2 class="mb-4 text-base font-semibold text-cyan-6">Status SLA</h2>

        <p class="mb-4">
            @if ($overdue)
                <span class="rounded-full bg-merah/10 px-3 py-1.5 text-sm font-semibold text-merah">Overdue</span>
            @elseif ($berjalan)
                <span class="rounded-full bg-hijau/10 px-3 py-1.5 text-sm font-semibold text-hijau">Dalam batas waktu</span>
            @else
                <span class="rounded-full bg-gray-100 px-3 py-1.5 text-sm font-semibold text-abu-tua">Tidak aktif</span>
            @endif
        </p>

        <dl class="space-y-3 text-sm">
            <div>
                <dt class="text-xs text-abu-tua">Batas Waktu Penyelesaian</dt>
                <dd class="mt-0.5">{{ $batas ? $batas->format('d M Y H:i') : '-' }}</dd>
            </div>
            @if ($berjalan && $sisa !== null)
                <div>
                    <dt class="text-xs text-abu-tua">{{ $overdue ? 'Terlambat' : 'Sisa Waktu' }}</dt>
                    <dd class="mt-0.5 font-semibold {{ $overdue ? 'text-merah' : 'text-hijau' }}">
                        {{ $formatMenit($sisa) }}
                    </dd>
                </div>
            @endif
        </dl>
    </div>
</div>

{{-- Closing report dari petugas --}}
@if ($penugasan->status === 'selesai' && $laporan->status === 'Diproses')
    <div class="mt-6 rounded-lg bg-white p-6 shadow">
        <h2 class="mb-4 text-base font-semibold text-cyan-6">Closing Report dari Petugas</h2>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                @if ($penugasan->foto_hasil)
                    <img src="{{ asset('storage/' . $penugasan->foto_hasil) }}" alt="Foto hasil pekerjaan"
                         class="w-full max-w-sm rounded-lg border border-gray-200 object-cover">
                @else
                    <div class="flex h-40 items-center justify-center rounded-lg bg-abu-muda/40 text-sm text-abu-tua">
                        Tidak ada foto hasil
                    </div>
                @endif
            </div>

            <div>
                <p class="text-xs text-abu-tua">Catatan Penyelesaian</p>
                <p class="mt-1 text-sm">{{ $penugasan->catatan_penyelesaian ?? '-' }}</p>
            </div>
        </div>

        <hr class="my-6 border-gray-200">

        <h3 class="mb-3 text-sm font-semibold text-cyan-6">Validasi Closing Report</h3>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <form action="{{ route('instansi.penugasan.update', $penugasan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="aksi" value="validasi">
                <button type="submit"
                        onclick="return confirm('Yakin closing report ini valid dan laporan selesai ditangani?')"
                        class="rounded-lg bg-hijau px-5 py-2.5 text-sm font-medium text-white hover:opacity-90">
                    Validasi (Laporan Selesai)
                </button>
            </form>

            <form action="{{ route('instansi.penugasan.update', $penugasan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="aksi" value="revisi">
                <textarea name="catatan" required rows="3"
                          placeholder="Alasan ditolak / perlu diperbaiki..."
                          class="mb-3 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"></textarea>
                <button type="submit"
                        class="rounded-lg bg-merah px-5 py-2.5 text-sm font-medium text-white hover:opacity-90">
                    Tolak, Kembalikan ke Petugas
                </button>
            </form>
        </div>
    </div>
@endif

@endsection
