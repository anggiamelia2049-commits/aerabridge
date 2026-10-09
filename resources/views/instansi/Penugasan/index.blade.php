@extends('template.layout')

@section('title', 'Penugasan')

@section('content')

@php
    $filterAktif = request('filter');
    $tabs = [
        ['label' => 'Aktif',             'filter' => null],
        ['label' => 'Menunggu Validasi', 'filter' => 'validasi'],
        ['label' => 'Selesai',           'filter' => 'selesai'],
        ['label' => 'Dibatalkan',        'filter' => 'dibatalkan'],
    ];

    // Format menit menjadi "2 hari 3 jam 10 menit"
    $formatMenit = function (int $menit) {
        $abs = abs($menit);
        $hari = intdiv($abs, 1440);
        $jam  = intdiv($abs % 1440, 60);
        $mnt  = $abs % 60;
        return trim(($hari ? $hari . ' hari ' : '') . ($jam ? $jam . ' jam ' : '') . $mnt . ' menit');
    };
@endphp

<div class="mb-5 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-cyan-6">Penugasan</h1>
    <a href="{{ route('instansi.penugasan.create') }}"
       class="rounded-lg bg-cyan-4 px-5 py-2.5 text-sm font-medium text-white hover:bg-cyan-6">
        + Disposisi Tugas
    </a>
</div>

@if (session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
@endif

{{-- Tab filter --}}
<div class="mb-4 flex gap-6 border-b border-gray-300 text-sm font-medium">
    @foreach ($tabs as $tab)
        @php $aktif = $filterAktif === $tab['filter']; @endphp
        <a href="{{ route('instansi.penugasan.index', $tab['filter'] ? ['filter' => $tab['filter']] : []) }}"
           class="-mb-px border-b-2 pb-3 transition {{ $aktif ? 'border-cyan-4 text-cyan-6' : 'border-transparent text-abu-tua hover:text-cyan-6' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>

<div class="overflow-x-auto rounded-lg bg-white shadow">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">Laporan</th>
                <th class="px-4 py-3">Tim Satgas</th>
                <th class="px-4 py-3">Petugas</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Batas SLA</th>
                <th class="px-4 py-3">Tgl Penugasan</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penugasan as $v)
                @php
                    // Warna status mengikuti proposal: oranye = diproses, hijau = selesai
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

                    // Indikator SLA: merah = Overdue, kuning = mendekati batas
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
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-cyan-6">{{ $v->laporan->judul ?? '-' }}</p>
                        @if ($prioritas)
                            <span class="mt-1 inline-block rounded-full px-2 py-0.5 text-xs font-medium {{ $prioritasClass }}">
                                {{ $prioritas }}
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $v->timSatgas->nama_tim ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $v->petugas->nama ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $v->status)) }}
                        </span>
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
                        @if ($berjalan)
                            |
                            <a href="{{ route('instansi.penugasan.edit', $v->id) }}" class="text-cyan-4 hover:underline">Alihkan</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-abu-tua">Belum ada data penugasan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
