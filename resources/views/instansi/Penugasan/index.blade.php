@extends('template.layout')

@section('title', 'Penugasan')

@section('content')

    @php
        $filterAktif = request('filter');
        $tabs = [
            ['label' => 'Aktif', 'filter' => null],
            ['label' => 'Menunggu Validasi', 'filter' => 'validasi'],
            ['label' => 'Selesai', 'filter' => 'selesai'],
            ['label' => 'Dibatalkan', 'filter' => 'dibatalkan'],
        ];

        // Format menit menjadi "2 hari 3 jam 10 menit"
        $formatMenit = function (int $menit) {
            $abs = abs($menit);
            $hari = intdiv($abs, 1440);
            $jam = intdiv($abs % 1440, 60);
            $mnt = $abs % 60;
            return trim(($hari ? $hari . ' hari ' : '') . ($jam ? $jam . ' jam ' : '') . $mnt . ' menit');
        };
    @endphp

    {{-- ==================== JUDUL ==================== --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-cyan-6">Penugasan</h1>
            <p class="mt-1 text-sm text-abu-tua">Pantau disposisi tugas, tim Satgas, dan batas waktu penyelesaian (SLA).</p>
        </div>
        <a href="{{ route('instansi.penugasan.create') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-cyan-4 px-5 py-2.5 text-sm font-semibold text-white no-underline shadow-sm transition hover:bg-cyan-6">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" d="M12 5v14M5 12h14" />
            </svg>
            Disposisi Tugas
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}
        </div>
    @endif

    {{-- ==================== TAB FILTER ==================== --}}
    <div class="mb-4 overflow-x-auto">
        <div class="inline-flex gap-1 rounded-xl bg-white p-1 shadow-sm">
            @foreach ($tabs as $tab)
                @php $aktif = $filterAktif === $tab['filter']; @endphp
                <a href="{{ route('instansi.penugasan.index', $tab['filter'] ? ['filter' => $tab['filter']] : []) }}"
                    class="whitespace-nowrap rounded-lg px-4 py-2 text-sm font-medium no-underline transition {{ $aktif ? 'bg-cyan-6 text-white' : 'text-abu-tua hover:bg-gray-100' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- ==================== TABEL ==================== --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow">
        <div class="overflow-x-auto">
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


                <tbody class="divide-y divide-gray-100">
                    @forelse ($penugasan as $v)
                        @php
                            // [kelas badge, kelas titik]
                            [$statusClass, $statusDot] = match ($v->status) {
                                'ditugaskan' => ['bg-cyan-3/20 text-cyan-6', 'bg-cyan-4'],
                                'dalam_proses' => ['bg-oranye/10 text-oranye', 'bg-oranye'],
                                'selesai' => ['bg-hijau/10 text-hijau', 'bg-hijau'],
                                'dibatalkan' => ['bg-gray-200 text-abu-tua', 'bg-gray-400'],
                                default => ['bg-gray-100 text-abu-tua', 'bg-gray-400'],
                            };

                            $prioritas = $v->laporan->tingkat_prioritas ?? null;
                            [$prioritasClass, $prioritasDot] = match (strtolower($prioritas ?? '')) {
                                'kritis', 'tinggi' => ['bg-merah/10 text-merah', 'bg-merah'],
                                'sedang' => ['bg-oranye/10 text-oranye', 'bg-oranye'],
                                default => ['bg-kuning/20 text-yellow-700', 'bg-kuning'],
                            };

                            // Indikator SLA: merah = Overdue, kuning = mendekati batas
                            $berjalan = in_array($v->status, ['ditugaskan', 'dalam_proses'], true);
                            $batas = $berjalan ? $v->batasWaktuSla() : null;
                            $sisa = $berjalan ? $v->sisaWaktuSla() : null;
                            $overdue = $v->isOverdue();

                            $total = null;
                            $mendekati = false;
                            if ($batas && $sisa !== null && $sisa >= 0 && $v->tanggal_penugasan) {
                                $total = max(1, $v->tanggal_penugasan->diffInMinutes($batas));
                                $mendekati = $sisa <= $total * 0.2;
                            }
                            // Persentase waktu yang sudah terpakai (untuk bar progres)
                            $persen = $total ? min(100, max(0, (int) round((1 - $sisa / $total) * 100))) : 0;

                            $namaPetugas = $v->petugas->nama ?? null;
                        @endphp
                        <tr class="align-top transition hover:bg-gray-50/70">
                            <td class="px-4 py-4 text-abu-tua">{{ $loop->iteration }}</td>

                            <td class="px-4 py-4">
                                <p class="max-w-[260px] font-semibold text-cyan-6">{{ $v->laporan->judul ?? '-' }}</p>
                                @if ($prioritas)
                                    <span
                                        class="mt-1.5 inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $prioritasClass }}">
                                        <span
                                            class="h-1.5 w-1.5 rounded-full {{ $prioritasDot }}"></span>{{ $prioritas }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-4">{{ $v->timSatgas->nama_tim ?? '-' }}</td>

                            <td class="px-4 py-4">
                                @if ($namaPetugas)
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-cyan-3/25 text-xs font-bold text-cyan-6">
                                            {{ mb_strtoupper(mb_substr($namaPetugas, 0, 1)) }}
                                        </span>
                                        <span>{{ $namaPetugas }}</span>
                                    </div>
                                @else
                                    <span class="text-abu-tua">-</span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                    {{ ucfirst(str_replace('_', ' ', $v->status)) }}
                                </span>
                            </td>

                            <td class="min-w-[170px] px-4 py-4">
                                @if ($overdue)
                                    <span
                                        class="rounded-full bg-merah/10 px-2.5 py-1 text-xs font-semibold text-merah">Overdue</span>
                                    @if ($sisa !== null)
                                        <p class="mt-1.5 text-xs text-merah">Lewat {{ $formatMenit($sisa) }}</p>
                                    @endif
                                    <div class="mt-2 h-1.5 w-full rounded-full bg-merah/20">
                                        <div class="h-1.5 w-full rounded-full bg-merah"></div>
                                    </div>
                                @elseif ($batas)
                                    <p class="text-xs text-abu-tua">{{ $batas->format('d M Y H:i') }}</p>
                                    <div class="mt-1.5 h-1.5 w-full rounded-full bg-gray-200">
                                        <div class="h-1.5 rounded-full {{ $mendekati ? 'bg-yellow-500' : 'bg-hijau' }}"
                                            style="width: {{ $persen }}%"></div>
                                    </div>
                                    <p
                                        class="mt-1 text-xs font-medium {{ $mendekati ? 'text-yellow-700' : 'text-hijau' }}">
                                        {{ $mendekati ? 'Mendekati batas · ' : '' }}Sisa {{ $formatMenit($sisa) }}
                                    </p>
                                @else
                                    <span class="text-abu-tua">-</span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 text-abu-tua">
                                {{ optional($v->tanggal_penugasan)->format('d M Y H:i') ?? '-' }}</td>

                            <td class="whitespace-nowrap px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('instansi.penugasan.show', $v->id) }}"
                                        class="rounded-lg border border-cyan-4 px-3 py-1.5 text-xs font-semibold text-cyan-4 no-underline transition hover:bg-cyan-4 hover:text-white">Detail</a>
                                    @if ($berjalan)
                                        <a href="{{ route('instansi.penugasan.edit', $v->id) }}"
                                            class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-semibold text-cyan-6 no-underline transition hover:bg-gray-200">Alihkan</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-14 text-center">
                                <div
                                    class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-abu-tua">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h6M9 16h6M9 8h2M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-cyan-6">Belum ada data penugasan</p>
                                <p class="mt-1 text-xs text-abu-tua">Disposisikan laporan ke tim Satgas untuk memulai
                                    penanganan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if (method_exists($penugasan, 'links'))
        <div class="mt-4">{{ $penugasan->links() }}</div>
    @endif

@endsection
