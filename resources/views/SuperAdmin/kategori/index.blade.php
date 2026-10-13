@extends('template.layout')

@section('title', 'Data Kategori Kerusakan')

@section('content')

<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-semibold text-gray-800">Kategori Kerusakan</h1>
        <p class="text-sm text-gray-500">Kelola kategori yang bisa dipilih saat membuat laporan.</p>
    </div>

    <a href="{{ route('super_admin.kategori.create') }}"
       class="rounded bg-[#0d6efd] px-4 py-2 text-sm text-white transition hover:bg-[#0b5ed7]">
        Tambah Data
    </a>
</div>

<div class="overflow-x-auto rounded-md border border-gray-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-800">
            <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">Nama Kategori</th>
                <th class="px-4 py-3">Icon</th>
                <th class="px-4 py-3">Deskripsi</th>
                <th class="px-4 py-3 whitespace-nowrap">Warna Marker</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
            @forelse ($kategoris as $v)
                @php
                    $warna = (string) $v->warna_marker;
                    $isHex = preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $warna);
                    $aktif = in_array(strtolower((string) $v->status), ['aktif', 'active', '1', 'true'], true);
                @endphp

                <tr class="align-middle hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>

                    <td class="px-4 py-3 font-medium text-gray-800">{{ $v->nama_kategori }}</td>

                    <td class="px-4 py-3">{{ $v->icon ?: '-' }}</td>

                    <td class="px-4 py-3 min-w-[14rem]">{{ $v->deskripsi ?: '-' }}</td>

                    <td class="px-4 py-3 whitespace-nowrap">
                        @if ($isHex)
                            <span class="inline-flex items-center gap-2">
                                <span class="inline-block h-4 w-4 rounded border border-gray-300"
                                      style="background-color: {{ $warna }};"></span>
                                {{ $warna }}
                            </span>
                        @else
                            {{ $warna ?: '-' }}
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        <span class="inline-block whitespace-nowrap rounded px-2 py-1 text-xs font-bold {{ $aktif ? 'bg-[#198754] text-white' : 'bg-[#6c757d] text-white' }}">
                            {{ $v->status ?: '-' }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('super_admin.kategori.show', $v->id) }}"
                               class="rounded bg-[#0dcaf0] px-2 py-1 text-sm text-black transition hover:bg-[#31d2f2]">
                                Detail
                            </a>

                            <a href="{{ route('super_admin.kategori.edit', $v->id) }}"
                               class="rounded bg-[#ffc107] px-2 py-1 text-sm text-black transition hover:bg-[#ffca2c]">
                                Edit
                            </a>

                            <form action="{{ route('super_admin.kategori.destroy', $v->id) }}"
                                  method="POST"
                                  class="inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                        class="rounded bg-[#dc3545] px-2 py-1 text-sm text-white transition hover:bg-[#bb2d3b]">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                        Belum ada kategori.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection