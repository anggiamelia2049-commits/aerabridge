@extends('template.layout')

@php
    $isVerifikasi = request()->routeIs('instansi.verifikasi.index');
    $judulHalaman = $isVerifikasi ? 'Verifikasi Laporan' : 'Semua Laporan';
    $adaRouteVerifikasi = \Illuminate\Support\Facades\Route::has('instansi.verifikasi.index');
@endphp

@section('title', $judulHalaman)

@section('content')
<div class="p-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">
                {{ $isVerifikasi ? 'Laporan yang menunggu verifikasi instansi.' : 'Semua laporan yang ditujukan ke instansi kamu.' }}
            </p>

            <div class="flex flex-wrap items-center gap-2">
                @unless ($isVerifikasi)
                    <form method="GET" class="flex flex-wrap gap-2">
                        <select name="status" onchange="this.form.submit()"
                                class="h-10 rounded-lg border border-abu-muda bg-white pl-3 pr-9 text-sm">
                            <option value="">Semua status</option>
                            @foreach (['Menunggu', 'Diverifikasi', 'Diproses', 'Selesai', 'Ditolak'] as $s)
                                <option value="{{ $s }}" @selected($status === $s)>{{ $s }}</option>
                            @endforeach
                        </select>

                        <select name="prioritas" onchange="this.form.submit()"
                                class="h-10 rounded-lg border border-abu-muda bg-white pl-3 pr-9 text-sm">
                            <option value="">Semua prioritas</option>
                            @foreach (['Kritis', 'Sedang', 'Rendah'] as $p)
                                <option value="{{ $p }}" @selected($prioritas === $p)>{{ $p }}</option>
                            @endforeach
                        </select>
                    </form>
                @endunless

                @if ($adaRouteVerifikasi)
                    <a href="{{ $isVerifikasi ? route('instansi.laporan.index') : route('instansi.verifikasi.index') }}"
                       class="text-sm font-semibold text-cyan-4 hover:underline">
                        {{ $isVerifikasi ? 'Lihat semua laporan →' : 'Lihat yang perlu diverifikasi →' }}
                    </a>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">No</th>
                        <th class="px-4 py-3.5 font-semibold">Pelapor</th>
                        <th class="px-4 py-3.5 font-semibold">Kategori</th>
                        <th class="px-4 py-3.5 font-semibold">Judul</th>
                        <th class="px-4 py-3.5 font-semibold">Foto</th>
                        <th class="px-4 py-3.5 font-semibold">Kecamatan</th>
                        <th class="px-4 py-3.5 font-semibold">Prioritas</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($laporan as $v)
                        @php
                            $gambar = $v->foto;
                            if (! $gambar && $v->lampiran
                                && in_array(strtolower(pathinfo($v->lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])) {
                                $gambar = $v->lampiran;
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3.5">{{ $v->nama_pelapor }}</td>
                            <td class="px-4 py-3.5">{{ $v->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-4 py-3.5 font-semibold">{{ $v->judul }}</td>
                            <td class="px-4 py-3.5">
                                @if ($gambar)
                                    <img src="{{ asset('storage/' . $gambar) }}" alt="Foto laporan"
                                         class="w-14 h-14 object-cover rounded-lg border border-abu-muda">
                                @else
                                    <div class="w-14 h-14 rounded-lg bg-gray-100 border border-abu-muda flex items-center justify-center text-[10px] text-gray-400">Tidak ada</div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">{{ $v->kecamatan ?: '-' }}</td>
                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                    'bg-merah/10 text-merah' => $v->tingkat_prioritas === 'Kritis',
                                    'bg-oranye/10 text-oranye' => $v->tingkat_prioritas === 'Sedang',
                                    'bg-kuning/20 text-abu-tua' => $v->tingkat_prioritas === 'Rendah',
                                ])>{{ $v->tingkat_prioritas }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                    'bg-abu-muda text-abu-tua' => $v->status === 'Menunggu',
                                    'bg-cyan-3/20 text-cyan-6' => $v->status === 'Diverifikasi',
                                    'bg-oranye/10 text-oranye' => $v->status === 'Diproses',
                                    'bg-hijau/10 text-hijau' => $v->status === 'Selesai',
                                    'bg-merah/10 text-merah' => $v->status === 'Ditolak',
                                ])>{{ $v->status }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-4 text-sm font-semibold whitespace-nowrap">
                                    <a href="{{ route('instansi.laporan.show', $v->id) }}" class="text-cyan-4 hover:underline">Tinjau</a>

                                    @if ($v->status === 'Menunggu')
                                        <form action="{{ route('instansi.laporan.verify', $v->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Diverifikasi">
                                            <button type="submit" class="text-hijau hover:underline">Setujui</button>
                                        </form>

                                        <form action="{{ route('instansi.laporan.verify', $v->id) }}" method="POST"
                                              onsubmit="return confirm('Tolak laporan ini?')">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="text-merah hover:underline">Tolak</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-400">
                                {{ $isVerifikasi ? 'Tidak ada laporan yang perlu diverifikasi saat ini.' : 'Belum ada data laporan.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection