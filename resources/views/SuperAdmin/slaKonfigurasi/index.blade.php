{{-- resources/views/SuperAdmin/sla-konfigurasi/index.blade.php --}}
@extends('template.layout')

@section('title', 'Manajemen Tanggapan')

@section('content')

@php
    $prioritasMap = [
        'kritis' => 'bg-[#dc3545] text-white',
        'sedang' => 'bg-[#ffc107] text-black',
        'rendah' => 'bg-[#6c757d] text-white',
    ];
@endphp

<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-semibold text-gray-800">Manajemen Tanggapan</h1>
        <p class="text-sm text-gray-500">Atur batas waktu respon dan penyelesaian untuk tiap tingkat prioritas.</p>
    </div>

    <a href="{{ route('super_admin.sla-konfigurasi.create') }}"
       class="rounded bg-[#0d6efd] px-4 py-2 text-sm text-white transition hover:bg-[#0b5ed7]">
        Tambah
    </a>
</div>

<div class="overflow-x-auto rounded-md border border-gray-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-800">
            <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">Prioritas</th>
                <th class="px-4 py-3 whitespace-nowrap">Waktu Respon</th>
                <th class="px-4 py-3 whitespace-nowrap">Waktu Penyelesaian</th>
                <th class="px-4 py-3">Deskripsi</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
            @forelse ($slaKonfigurasi as $v)
                @php
                    $prioritasClass = $prioritasMap[strtolower((string) $v->prioritas)] ?? 'bg-[#6c757d] text-white';
                    $aktif = in_array(strtolower((string) $v->status), ['aktif', 'active', '1', 'true'], true);
                @endphp

                <tr class="align-middle hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>

                    <td class="px-4 py-3">
                        <span class="inline-block whitespace-nowrap rounded px-2 py-1 text-xs font-bold {{ $prioritasClass }}">
                            {{ $v->prioritas ?: '-' }}
                        </span>
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap">{{ $v->waktu_respon }}</td>

                    <td class="px-4 py-3 whitespace-nowrap">{{ $v->waktu_penyelesaian }}</td>

                    <td class="px-4 py-3 min-w-[14rem]">{{ $v->deskripsi ?: '-' }}</td>

                    <td class="px-4 py-3">
                        <span class="inline-block whitespace-nowrap rounded px-2 py-1 text-xs font-bold {{ $aktif ? 'bg-[#198754] text-white' : 'bg-[#6c757d] text-white' }}">
                            {{ $v->status ?: '-' }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('super_admin.sla-konfigurasi.edit', $v->id) }}"
                               class="rounded bg-[#ffc107] px-2 py-1 text-sm text-black transition hover:bg-[#ffca2c]">
                                Edit
                            </a>

                            <form action="{{ route('super_admin.sla-konfigurasi.destroy', $v->id) }}"
                                  method="POST"
                                  class="inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
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
                        Belum ada data.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection