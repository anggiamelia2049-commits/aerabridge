@extends('template.layout')

@section('title', $tim->nama_tim)

@section('content')

@php
    // Format menit menjadi "2 hari 3 jam 10 menit"
    $formatMenit = function (int $menit) {
        $abs = abs($menit);
        $hari = intdiv($abs, 1440);
        $jam  = intdiv($abs % 1440, 60);
        $mnt  = $abs % 60;
        return trim(($hari ? $hari . ' hari ' : '') . ($jam ? $jam . ' jam ' : '') . $mnt . ' menit');
    };
    $jumlahAktif = $tim->penugasan->whereIn('status', ['ditugaskan', 'dalam_proses'])->count();
@endphp

<a href="{{ route('instansi.tim-satgas.index') }}" class="text-sm text-cyan-4 hover:underline">&larr; Kembali ke Tim Satgas</a>

<div class="my-4 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-cyan-6">{{ $tim->nama_tim }}</h1>
    <div class="flex items-center gap-3">
        <span class="rounded-full px-3 py-1 text-xs font-medium {{ $tim->status === 'aktif' ? 'bg-hijau/10 text-hijau' : 'bg-gray-200 text-abu-tua' }}">
            {{ ucfirst($tim->status) }}
        </span>
        <a href="{{ route('instansi.tim-satgas.edit', $tim->id) }}"
           class="rounded-lg bg-gray-200 px-4 py-2.5 text-sm font-medium text-abu-tua hover:bg-gray-300">Edit</a>
        <a href="{{ route('instansi.penugasan.create', ['tim_satgas_id' => $tim->id]) }}"
           class="rounded-lg bg-cyan-4 px-5 py-2.5 text-sm font-medium text-white hover:bg-cyan-6">+ Disposisi Tugas</a>
    </div>
</div>

<div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-4">
    <div class="rounded-lg bg-white p-4 shadow"><p class="text-xs text-abu-tua">Ketua Tim</p><p class="font-bold text-cyan-6">{{ $tim->ketua }}</p></div>
    <div class="rounded-lg bg-white p-4 shadow"><p class="text-xs text-abu-tua">Kontak</p><p class="font-bold text-cyan-6">{{ $tim->kontak }}</p></div>
    <div class="rounded-lg bg-white p-4 shadow"><p class="text-xs text-abu-tua">Jumlah Anggota</p><p class="font-bold text-cyan-6">{{ $tim->jumlah_anggota }} orang</p></div>
    <div class="rounded-lg bg-white p-4 shadow"><p class="text-xs text-abu-tua">Tugas Aktif</p><p class="font-bold text-cyan-6">{{ $jumlahAktif }}</p></div>
</div>

<div class="overflow-x-auto rounded-lg bg-white shadow">
    <h2 class="px-4 pt-4 font-semibold text-cyan-6">Riwayat Penugasan</h2>
    <table class="mt-2 w-full text-left text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3">Laporan</th>
                <th class="px-4 py-3">Petugas</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Batas SLA</th>
                <th class="px-4 py-3">Tgl Penugasan</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tim->penugasan as $v)
                @php
                    $statusClass = match ($v->status) {
                        'ditugaskan'   => 'bg-cyan-3/20 text-cyan-6',
                        'dalam_proses' => 'bg-oranye/10 text-oranye',
                        'selesai'      => 'bg-hijau/10 text-hijau',
                        'dibatalkan'   => 'bg-gray-200 text-abu-tua',
                        default        => 'bg-gray-100 text-abu-tua',
                    };

                    $prioritas = $v->laporan->tingkat_prioritas ?? null;
                    $prioritasClass = match ($prioritas) {
                        'Krisis', 'Kritis' => 'bg-merah/10 text-merah',
                        'Sedang'           => 'bg-oranye/10 text-oranye',
                        default            => 'bg-kuning/20 text-yellow-700',
                    };

                    $berjalan = in_array($v->status, ['ditugaskan', 'dalam_proses'], true);
                    $batas    = $berjalan ? $v->batasWaktuSla() : null;
                    $sisa     = $berjalan ? $v->sisaWaktuSla() : null;
                    $overdue  = $v->isOverdue();

                    $mendekati = false;
                    if ($batas && $sisa !== null && $sisa >= 0 && $v->tanggal_penugasan) {
                        $total = max(1, $v->tanggal_penugasan->diffInMinutes($batas));
                        $mendekati = $sisa <= ($total * 0.2);
                    }
                @endphp
                <tr class="border-b align-top">
                    <td class="px-4 py-3">
                        <p class="font-medium text-cyan-6">{{ $v->laporan->judul ?? '-' }}</p>
                        @if ($prioritas)
                            <span class="mt-1 inline-block rounded-full px-2 py-0.5 text-xs font-medium {{ $prioritasClass }}">{{ $prioritas }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $v->petugas->nama ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $v->status)) }}</span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if ($overdue)
                            <span class="rounded-full bg-merah/10 px-2.5 py-1 text-xs font-semibold text-merah">Overdue</span>
                            @if ($sisa !== null)
                                <p class="mt-1 text-xs text-merah">Lewat {{ $formatMenit($sisa) }}</p>
                            @endif
                        @elseif ($batas)
                            <p class="text-xs text-abu-tua">{{ $batas->format('d M Y H:i') }}</p>
                            <p class="mt-0.5 text-xs font-medium {{ $mendekati ? 'text-yellow-700' : 'text-hijau' }}">
                                {{ $mendekati ? 'Mendekati batas · ' : '' }}Sisa {{ $formatMenit($sisa) }}
                            </p>
                        @else
                            <span class="text-abu-tua">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ optional($v->tanggal_penugasan)->format('d M Y H:i') ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a href="{{ route('instansi.penugasan.show', $v->id) }}" class="text-cyan-4 hover:underline">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-abu-tua">Belum ada penugasan untuk tim ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
