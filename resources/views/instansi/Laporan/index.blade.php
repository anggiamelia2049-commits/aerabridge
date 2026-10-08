@extends('template.layout')

@section('title', request()->routeIs('instansi.verifikasi.index') ? 'Verifikasi Laporan' : 'Semua Laporan')

@section('content')

<div class="mb-5 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-cyan-6">
        {{ request()->routeIs('instansi.verifikasi.index') ? 'Verifikasi Laporan' : 'Semua Laporan' }}
    </h1>

    @if (request()->routeIs('instansi.verifikasi.index'))
        <a href="{{ route('instansi.laporan.index') }}" class="text-sm font-medium text-cyan-4 hover:text-cyan-6">
            Lihat semua laporan &rarr;
        </a>
    @else
        <a href="{{ route('instansi.verifikasi.index') }}" class="text-sm font-medium text-cyan-4 hover:text-cyan-6">
            Lihat yang perlu diverifikasi &rarr;
        </a>
    @endif
</div>

@if (session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

{{-- Filter hanya tampil di halaman Semua Laporan --}}
@unless (request()->routeIs('instansi.verifikasi.index'))
    <form method="GET" class="mb-4 flex flex-wrap gap-2">
        <select name="status" onchange="this.form.submit()" class="rounded-lg border border-abu-muda px-3 py-1.5 text-sm">
            <option value="">Semua status</option>
            <option value="Menunggu" {{ $status === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="Diverifikasi" {{ $status === 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
            <option value="Diproses" {{ $status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="Selesai" {{ $status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="Ditolak" {{ $status === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>

        <select name="prioritas" onchange="this.form.submit()" class="rounded-lg border border-abu-muda px-3 py-1.5 text-sm">
            <option value="">Semua prioritas</option>
            <option value="Krisis" {{ $prioritas === 'Krisis' ? 'selected' : '' }}>Krisis</option>
            <option value="Sedang" {{ $prioritas === 'Sedang' ? 'selected' : '' }}>Sedang</option>
            <option value="Rendah" {{ $prioritas === 'Rendah' ? 'selected' : '' }}>Rendah</option>
        </select>
    </form>
@endunless

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">User</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Judul</th>
                <th class="px-4 py-3">Foto</th>
                <th class="px-4 py-3">Alamat</th>
                <th class="px-4 py-3">Prioritas</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $v)
                @php
                    $prioritasClass = match ($v->tingkat_prioritas) {
                        'Krisis' => 'bg-merah/10 text-merah',
                        'Sedang' => 'bg-oranye/10 text-oranye',
                        default => 'bg-kuning/20 text-yellow-700',
                    };
                @endphp
                <tr class="border-b">
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">{{ $v->user->nama ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $v->kategori->nama_kategori ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $v->judul }}</td>
                    <td class="px-4 py-3">
                        @if ($v->foto)
                            <img src="{{ asset('storage/' . $v->foto) }}" width="60" class="rounded object-cover">
                        @else
                            <span class="text-gray-500">Tidak ada foto</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $v->alamat }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $prioritasClass }}">
                            {{ $v->tingkat_prioritas }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $v->status }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a href="{{ route('instansi.laporan.show', $v->id) }}" class="text-cyan-4 hover:underline">
                            Tinjau
                        </a>

                        @if ($v->status === 'Menunggu')
                            <form action="{{ route('instansi.laporan.verify', $v->id) }}" method="POST" class="mt-1 inline-flex gap-2">
                                @csrf
                                <input type="hidden" name="status" value="Diverifikasi">
                                <button type="submit" class="text-hijau hover:underline">Setujui</button>
                            </form>

                            <form action="{{ route('instansi.laporan.verify', $v->id) }}" method="POST" class="mt-1 inline-flex gap-2"
                                  onsubmit="return confirm('Tolak laporan ini?')">
                                @csrf
                                <input type="hidden" name="status" value="Ditolak">
                                <button type="submit" class="text-merah hover:underline">Tolak</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-4 py-6 text-center">
                        @if (request()->routeIs('instansi.verifikasi.index'))
                            Tidak ada laporan yang perlu diverifikasi saat ini.
                        @else
                            Belum ada data laporan.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
