@extends('template.layout')

@php
    $isVerifikasi = request()->routeIs('instansi.verifikasi.index');
    $judulHalaman = $isVerifikasi ? 'Verifikasi Laporan' : 'Semua Laporan';
    $adaRouteVerifikasi = \Illuminate\Support\Facades\Route::has('instansi.verifikasi.index');

    $selectClass = 'h-10 rounded-lg border border-abu-muda bg-abu-muda/40 pl-3 pr-9 text-sm text-gray-700 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
@endphp

@section('title', $judulHalaman)

@section('content')
<div class="px-6 pb-6 pt-0">

    {{-- ===== Bar filter ===== --}}
    <div class="bg-white rounded-2xl shadow px-5 py-3.5 mb-4 flex flex-wrap items-center justify-between gap-3">

        {{-- Switch tampilan: Semua Laporan / Perlu Diverifikasi --}}
        @if ($adaRouteVerifikasi)
            <div class="inline-flex rounded-[10px] bg-gray-100 p-1">
                <a href="{{ route('instansi.laporan.index') }}"
                   @class([
                       'inline-flex items-center gap-2 px-4 py-1.5 rounded-[7px] text-[13px] font-semibold no-underline hover:no-underline transition',
                       'bg-white text-cyan-6 shadow-sm' => ! $isVerifikasi,
                       'text-gray-500 hover:text-gray-700' => $isVerifikasi,
                   ])>
                    Semua Laporan
                </a>

                <a href="{{ route('instansi.verifikasi.index') }}"
                   @class([
                       'inline-flex items-center gap-2 px-4 py-1.5 rounded-[7px] text-[13px] font-semibold no-underline hover:no-underline transition',
                       'bg-white text-cyan-6 shadow-sm' => $isVerifikasi,
                       'text-gray-500 hover:text-gray-700' => ! $isVerifikasi,
                   ])>
                    Perlu Diverifikasi
                    @if (($jumlahMenunggu ?? 0) > 0)
                        <span class="px-1.5 rounded-full text-[11px] leading-4 bg-merah/10 text-merah">{{ $jumlahMenunggu }}</span>
                    @endif
                </a>
            </div>
        @else
            <span></span>
        @endif

        {{-- Dropdown filter (hanya di halaman Semua Laporan) --}}
        @unless ($isVerifikasi)
            <form method="GET" class="flex flex-wrap gap-2">
                <select name="status" onchange="this.form.submit()" class="{{ $selectClass }}">
                    <option value="">Semua status</option>

                    @foreach (['Menunggu', 'Diverifikasi', 'Diproses', 'Selesai', 'Ditolak'] as $s)
                        <option value="{{ $s }}" @selected($status === $s)>{{ $s }}</option>
                    @endforeach
                </select>

                <select name="prioritas" onchange="this.form.submit()" class="{{ $selectClass }}">
                    <option value="">Semua prioritas</option>

                    @foreach (['Kritis', 'Sedang', 'Rendah'] as $p)
                        <option value="{{ $p }}" @selected($prioritas === $p)>{{ $p }}</option>
                    @endforeach
                </select>
            </form>
        @endunless
    </div>

    {{-- ===== Daftar laporan (kartu) ===== --}}
    <div class="space-y-3">
        @forelse ($laporan as $v)
            @php
                $gambar = $v->foto;

                if (
                    !$gambar &&
                    $v->lampiran &&
                    in_array(
                        strtolower(pathinfo($v->lampiran, PATHINFO_EXTENSION)),
                        ['jpg', 'jpeg', 'png']
                    )
                ) {
                    $gambar = $v->lampiran;
                }
            @endphp

            <div class="bg-white rounded-2xl shadow px-5 py-4 flex flex-wrap items-center gap-4">

                {{-- Foto --}}
                @if ($gambar)
                    <img src="{{ asset('storage/' . $gambar) }}" alt="Foto laporan"
                         class="w-[72px] h-[72px] shrink-0 object-cover rounded-lg border border-abu-muda">
                @else
                    <div class="w-[72px] h-[72px] shrink-0 rounded-lg bg-gray-100 border border-abu-muda flex items-center justify-center text-[10px] text-gray-400">
                        Tidak ada
                    </div>
                @endif

                {{-- Judul + info --}}
                <div class="flex-1 min-w-[220px]">
                    <p class="text-base font-semibold text-gray-900">{{ $v->judul }}</p>
                    <p class="mt-1 text-[13px] text-gray-500">
                        {{ $v->nama_pelapor }} · {{ $v->kategori->nama_kategori ?? '-' }} · Kec. {{ $v->kecamatan ?: '-' }}
                    </p>
                </div>

                {{-- Prioritas + status --}}
                <div class="flex items-center gap-2">
                    <span @class([
                        'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                        'bg-merah/10 text-merah' => $v->tingkat_prioritas === 'Kritis',
                        'bg-oranye/10 text-oranye' => $v->tingkat_prioritas === 'Sedang',
                        'bg-kuning/20 text-abu-tua' => $v->tingkat_prioritas === 'Rendah',
                    ])>
                        {{ $v->tingkat_prioritas }}
                    </span>

                    <span @class([
                        'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                        'bg-abu-muda text-abu-tua' => $v->status === 'Menunggu',
                        'bg-cyan-3/20 text-cyan-6' => $v->status === 'Diverifikasi',
                        'bg-oranye/10 text-oranye' => $v->status === 'Diproses',
                        'bg-hijau/10 text-hijau' => $v->status === 'Selesai',
                        'bg-merah/10 text-merah' => $v->status === 'Ditolak',
                    ])>
                        {{ $v->status }}
                    </span>
                </div>

                {{-- Aksi --}}
                <div class="flex items-center gap-2 whitespace-nowrap">
                    <a href="{{ route('instansi.laporan.show', $v->id) }}"
                       class="px-3.5 py-1.5 rounded-lg border border-gray-300 bg-white text-[13px] font-semibold text-gray-700 no-underline hover:no-underline hover:bg-gray-50 transition">
                        Tinjau
                    </a>

                    @if ($v->status === 'Menunggu')

                        {{-- Setujui laporan --}}
                        <form action="{{ route('instansi.laporan.verify', $v->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="Diverifikasi">

                            <button type="submit"
                                    class="px-3.5 py-1.5 rounded-lg bg-hijau/10 text-hijau text-[13px] font-semibold hover:bg-hijau/20 transition">
                                Setujui
                            </button>
                        </form>

                        {{-- Tolak laporan --}}
                        <form action="{{ route('instansi.laporan.verify', $v->id) }}" method="POST"
                              onsubmit="return confirm('Tolak laporan ini?')">
                            @csrf
                            <input type="hidden" name="status" value="Ditolak">

                            <button type="submit"
                                    class="px-3.5 py-1.5 rounded-lg bg-merah/10 text-merah text-[13px] font-semibold hover:bg-merah/20 transition">
                                Tolak
                            </button>
                        </form>

                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow px-4 py-10 text-center text-gray-400">
                {{ $isVerifikasi
                    ? 'Tidak ada laporan yang perlu diverifikasi saat ini.'
                    : 'Belum ada data laporan.' }}
            </div>
        @endforelse
    </div>
</div>
@endsection
