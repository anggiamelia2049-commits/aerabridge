@extends('template.layout')

@section('title', 'Tim Satgas')

@section('content')

<div class="mb-3 flex items-end justify-between">

    <div>
        <h1 class="text-h1 leading-none">Tim Satgas</h1>
        <p class="text-subtitle mt-2 leading-tight">
            Kelola tim satgas yang menangani laporan kerusakan infrastruktur
        </p>
    </div>

    <a href="{{ route('instansi.tim-satgas.create') }}"
       class="rounded-lg bg-cyan-4 px-5 py-2.5 text-sm font-medium text-white hover:bg-cyan-6">
        + Tambah Tim
    </a>
</div>
{{-- Kartu ringkasan --}}
<div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-4">
    <div class="rounded-lg border-l-4 border-cyan-4 bg-white p-4 shadow">
        <p class="text-xs text-abu-tua">Total Tim</p>
        <p class="text-2xl font-bold text-cyan-6">{{ $timSatgas->total() }}</p>
    </div>
    <div class="rounded-lg border-l-4 border-hijau bg-white p-4 shadow">
        <p class="text-xs text-abu-tua">Tim Aktif</p>
        <p class="text-2xl font-bold text-cyan-6">{{ $totalAktif }}</p>
    </div>
    <div class="rounded-lg border-l-4 border-oranye bg-white p-4 shadow">
        <p class="text-xs text-abu-tua">Sedang Menangani</p>
        <p class="text-2xl font-bold text-cyan-6">{{ $totalBertugas }}</p>
    </div>
    <div class="rounded-lg border-l-4 border-merah bg-white p-4 shadow">
        <p class="text-xs text-abu-tua">SLA Overdue</p>
        <p class="text-2xl font-bold text-cyan-6">{{ $totalOverdue }}</p>
    </div>
</div>

{{-- Pencarian & filter --}}
<form method="GET" class="mb-4 flex gap-3">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama tim atau ketua..."
           class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-cyan-4 focus:ring-cyan-4">
    <select name="status" onchange="this.form.submit()"
            class="rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-10 text-sm focus:border-cyan-4 focus:ring-cyan-4">
        <option value="">Semua Status</option>
        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
        <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
    </select>
</form>
<div class="overflow-x-auto rounded-lg bg-white shadow">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">Nama Tim</th>
                <th class="px-4 py-3">Ketua</th>
                <th class="px-4 py-3">Kontak</th>
                <th class="px-4 py-3">Anggota</th>
                <th class="px-4 py-3">Tugas Aktif</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($timSatgas as $tim)
                <tr class="border-b">
                    <td class="px-4 py-3">{{ $timSatgas->firstItem() + $loop->index }}</td>
                    <td class="px-4 py-3 font-medium text-cyan-6">{{ $tim->nama_tim }}</td>
                    <td class="px-4 py-3">{{ $tim->ketua }}</td>
                    <td class="px-4 py-3">{{ $tim->kontak }}</td>
                    <td class="px-4 py-3">{{ $tim->jumlah_anggota }} orang</td>
                    <td class="px-4 py-3">{{ $tim->penugasan_aktif_count }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $tim->status === 'aktif' ? 'bg-hijau/10 text-hijau' : 'bg-gray-200 text-abu-tua' }}">
                            {{ ucfirst($tim->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a href="{{ route('instansi.tim-satgas.show', $tim->id) }}" class="text-cyan-4 hover:underline">Detail</a>
                        |
                        <a href="{{ route('instansi.tim-satgas.edit', $tim->id) }}" class="text-cyan-4 hover:underline">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-abu-tua">Belum ada data tim satgas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $timSatgas->links() }}</div>

@endsection
