@extends('template.layout')

@section('title', 'Deteksi AI')

@section('content')
@php
    // Nomor urut ikut paginasi (kalau controller masih ->get(), mulai dari 1)
    $mulai = method_exists($deteksiAIs, 'firstItem') ? ($deteksiAIs->firstItem() ?? 1) : 1;

    $badgePrioritas = [
        'kritis' => 'bg-merah/10 text-merah',
        'sedang' => 'bg-oranye/10 text-oranye',
        'rendah' => 'bg-kuning/20 text-abu-tua',
    ];

    $badgeKerusakan = [
        'berat'  => 'bg-merah/10 text-merah',
        'sedang' => 'bg-oranye/10 text-oranye',
        'ringan' => 'bg-kuning/20 text-abu-tua',
    ];

    $badgeValidasi = [
        'valid'       => ['Valid', 'bg-hijau/10 text-hijau'],
        'tidak_valid' => ['Tidak Valid', 'bg-merah/10 text-merah'],
    ];

    $selectClass = 'h-9 pr-8 rounded-lg border-abu-muda text-sm text-gray-700 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
    $labelClass  = 'block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1';
    $adaFilter   = request()->hasAny(['tingkat_kerusakan', 'estimasi_prioritas', 'hasil_validasi']);
@endphp

{{-- ===== Judul halaman ===== --}}
<div class="mb-5">
    <h1 class="text-xl font-bold text-gray-800">Hasil Deteksi AI</h1>
    <p class="text-sm text-gray-500 mt-1">
        Dihasilkan otomatis oleh layanan AI (YOLO + LLM) setiap ada laporan masuk. Halaman ini hanya untuk dipantau.
    </p>
</div>

{{-- ===== Filter ===== --}}
<form method="GET" action="{{ route('super_admin.deteksi-ai.index') }}"
      class="bg-white rounded-xl shadow-sm p-4 mb-4 flex flex-wrap items-end gap-3">

    <div>
        <label class="{{ $labelClass }}">Tingkat Kerusakan</label>
        <select name="tingkat_kerusakan" class="{{ $selectClass }}">
            <option value="">Semua</option>
            @foreach (['ringan' => 'Ringan', 'sedang' => 'Sedang', 'berat' => 'Berat'] as $nilai => $teks)
                <option value="{{ $nilai }}" @selected(request('tingkat_kerusakan') === $nilai)>{{ $teks }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="{{ $labelClass }}">Prioritas AI</label>
        <select name="estimasi_prioritas" class="{{ $selectClass }}">
            <option value="">Semua</option>
            @foreach (['kritis' => 'Kritis', 'sedang' => 'Sedang', 'rendah' => 'Rendah'] as $nilai => $teks)
                <option value="{{ $nilai }}" @selected(request('estimasi_prioritas') === $nilai)>{{ $teks }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="{{ $labelClass }}">Validasi</label>
        <select name="hasil_validasi" class="{{ $selectClass }}">
            <option value="">Semua</option>
            @foreach (['valid' => 'Valid', 'tidak_valid' => 'Tidak Valid'] as $nilai => $teks)
                <option value="{{ $nilai }}" @selected(request('hasil_validasi') === $nilai)>{{ $teks }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex gap-2">
        <button type="submit"
                class="h-9 px-4 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-semibold transition">
            Terapkan
        </button>

        @if ($adaFilter)
            <a href="{{ route('super_admin.deteksi-ai.index') }}"
               class="h-9 inline-flex items-center px-4 rounded-lg border border-abu-muda text-sm text-abu-tua hover:bg-gray-50 transition">
                Reset
            </a>
        @endif
    </div>
</form>

{{-- ===== Tabel ===== --}}
<section class="bg-white rounded-xl shadow-sm p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 rounded-l-md">No</th>
                    <th class="px-4 py-3">Laporan</th>
                    <th class="px-4 py-3">Jenis Objek</th>
                    <th class="px-4 py-3">Confidence</th>
                    <th class="px-4 py-3 text-center">Kerusakan</th>
                    <th class="px-4 py-3 text-center">Prioritas AI</th>
                    <th class="px-4 py-3 text-center">Validasi</th>
                    <th class="px-4 py-3">Respon LLM</th>
                    <th class="px-4 py-3 rounded-r-md text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($deteksiAIs as $v)
                    @php
                        $persen    = (int) round(((float) $v->confidence) * 100);
                        $kerusakan = strtolower($v->tingkat_kerusakan ?? '');
                        $prioritas = strtolower($v->estimasi_prioritas ?? '');
                        [$teksValidasi, $kelasValidasi] = $badgeValidasi[strtolower($v->hasil_validasi ?? '')]
                            ?? [ucfirst(str_replace('_', ' ', $v->hasil_validasi ?? '-')), 'bg-gray-200 text-gray-700'];
                    @endphp

                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-500">{{ $mulai + $loop->index }}</td>

                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $v->laporan->judul ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">{{ ucfirst($v->jenis_objek ?? '-') }}</td>

                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="w-16 h-1.5 rounded-full bg-gray-200 overflow-hidden">
                                    <div class="h-full bg-cyan-4" style="width: {{ $persen }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600">{{ $persen }}%</span>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="inline-block min-w-[64px] rounded-md px-3 py-1 text-xs font-semibold {{ $badgeKerusakan[$kerusakan] ?? 'bg-gray-200 text-gray-700' }}">
                                {{ ucfirst($kerusakan ?: '-') }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="inline-block min-w-[64px] rounded-md px-3 py-1 text-xs font-semibold {{ $badgePrioritas[$prioritas] ?? 'bg-gray-200 text-gray-700' }}">
                                {{ ucfirst($prioritas ?: '-') }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="inline-block min-w-[64px] rounded-md px-3 py-1 text-xs font-semibold {{ $kelasValidasi }}">
                                {{ $teksValidasi }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-gray-600 max-w-xs">
                            <span title="{{ $v->response_llm }}">
                                {{ \Illuminate\Support\Str::limit($v->response_llm, 80) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('super_admin.deteksi-ai.show', $v->id) }}"
                               class="text-cyan-4 hover:text-cyan-6 font-medium hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-10 text-center text-gray-400">
                            {{ $adaFilter ? 'Tidak ada hasil deteksi yang cocok dengan filter.' : 'Belum ada hasil deteksi AI.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (method_exists($deteksiAIs, 'hasPages') && $deteksiAIs->hasPages())
        <div class="mt-4">
            {{ $deteksiAIs->links() }}
        </div>
    @endif
</section>
@endsection