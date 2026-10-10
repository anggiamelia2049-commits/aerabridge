@extends('SuperAdmin.layouts.app')

@section('title', 'Konten Edukasi')

@section('content')
@php
    $badge = [
        'publish'  => 'bg-[#4CAF50]/15 text-[#2e7d32]',
        'draft'    => 'bg-[#FFC107]/20 text-[#8a6500]',
        'nonaktif' => 'bg-gray-200 text-gray-600',
    ];
@endphp

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-[#0C343D]">Konten Edukasi</h1>
        <p class="text-sm text-[#4A4A4A]">Kelola materi edukasi perawatan fasilitas publik.</p>
    </div>
    <a href="{{ route('super_admin.konten-edukasi.create') }}"
        class="px-4 py-2.5 text-sm font-semibold text-white rounded-lg bg-[#45818E] hover:bg-[#0C343D]">
        + Tambah Konten
    </a>
</div>

@if (session('success'))
    <div class="p-4 mb-5 text-sm rounded-lg border border-[#4CAF50]/30 bg-[#4CAF50]/10 text-[#2e7d32]">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-[#D9D9D9] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-[#4A4A4A]">
            <thead class="text-xs uppercase bg-[#0C343D] text-white">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Thumbnail</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Penulis</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#D9D9D9]">
                @forelse ($konten as $v)
                    <tr class="hover:bg-[#A9D6DD]/20">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            @if ($v->thumbnail)
                                <img src="{{ asset('storage/' . $v->thumbnail) }}" alt="Thumbnail {{ $v->judul }}"
                                    class="object-cover w-16 h-16 rounded-lg border border-[#D9D9D9]">
                            @else
                                <span class="flex items-center justify-center w-16 h-16 text-[10px] text-gray-400 rounded-lg bg-gray-100 border border-[#D9D9D9]">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 max-w-xs">
                            <div class="font-semibold text-[#0C343D]">{{ $v->judul }}</div>
                            <div class="mt-0.5 text-xs text-gray-500">{{ \Illuminate\Support\Str::limit(strip_tags($v->isi), 80) }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-[#A9D6DD]/60 text-[#0C343D]">{{ $v->kategori }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $v->penulisUser->nama ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badge[$v->status] ?? 'bg-gray-200 text-gray-600' }}">
                                {{ ucfirst($v->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('super_admin.konten-edukasi.show', $v->id) }}"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-[#D9D9D9] hover:bg-gray-50">Detail</a>
                                <a href="{{ route('super_admin.konten-edukasi.edit', $v->id) }}"
                                    class="px-3 py-1.5 text-xs font-medium text-white rounded-lg bg-[#45818E] hover:bg-[#0C343D]">Edit</a>
                                <form action="{{ route('super_admin.konten-edukasi.destroy', $v->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus konten ini?')"
                                        class="px-3 py-1.5 text-xs font-medium text-white rounded-lg bg-[#E53935] hover:bg-[#c62828]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-500">Belum ada konten edukasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection