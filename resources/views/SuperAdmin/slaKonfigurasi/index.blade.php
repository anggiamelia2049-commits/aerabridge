@extends('template.layout')

@section('title', 'Manajemen Tanggapan')

@section('content')
    @php
        // Warna penanda di sisi kiri tiap baris
        $prioritasWarna = [
            'kritis' => '#E53935',
            'sedang' => '#FF9800',
            'rendah' => '#FFC107',
        ];

        $statusAktif = ['aktif', 'active', '1', 'true'];
    @endphp

    <div class="p-6">

        {{-- Tombol tambah di atas card --}}
        <div class="flex justify-end mb-4">
            <a href="{{ route('super_admin.sla-konfigurasi.create') }}"
                class="px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold no-underline hover:no-underline transition">
                + Tambah SLA
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 text-sm">
                        <tr>
                            <th class="px-6 py-4 font-bold">Prioritas</th>
                            <th class="px-4 py-4 font-bold whitespace-nowrap">Waktu Respon</th>
                            <th class="px-4 py-4 font-bold whitespace-nowrap">Waktu Penyelesaian</th>
                            <th class="px-4 py-4 font-bold">Deskripsi</th>
                            <th class="px-4 py-4 font-bold">Status</th>
                            <th class="px-4 py-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @forelse ($slaKonfigurasi as $v)
                            @php
                                $warna = $prioritasWarna[strtolower((string) $v->prioritas)] ?? '#D1D5DB';

                                $aktif = in_array(strtolower((string) $v->status), $statusAktif, true);
                            @endphp

                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3.5 font-semibold text-gray-800 whitespace-nowrap"
                                    style="border-left: 4px solid {{ $warna }}">
                                    {{ $v->prioritas ?: '-' }}
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    {{ $v->waktu_respon }}
                                </td>

                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    {{ $v->waktu_penyelesaian }}
                                </td>

                                <td class="px-4 py-3.5 min-w-[14rem]">
                                    {{ $v->deskripsi ?: '-' }}
                                </td>

                                <td class="px-4 py-3.5">
                                    <span @class([
                                        'inline-block px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap',
                                        'bg-hijau/10 text-hijau' => $aktif,
                                        'bg-abu-muda text-abu-tua' => !$aktif,
                                    ])>
                                        {{ $v->status ?: '-' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-center gap-4 text-sm font-semibold">
                                        <a href="{{ route('super_admin.sla-konfigurasi.edit', $v->id) }}"
                                            class="text-oranye hover:underline">
                                            Edit
                                        </a>

                                        <form action="{{ route('super_admin.sla-konfigurasi.destroy', $v->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus konfigurasi SLA ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-merah hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-gray-400">
                                    Belum ada konfigurasi SLA.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
