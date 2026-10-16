
@extends('template.layout')

@section('title', 'Tenggat Perbaikan')

@section('content')
<div class="p-6">

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-hijau font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-merah">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">
                Atur batas waktu penyelesaian laporan berdasarkan prioritas AI.
                <span class="text-gray-400">
                    ({{ $tenggatPerbaikan->count() }} konfigurasi)
                </span>
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">No</th>
                        <th class="px-4 py-3.5 font-semibold">Prioritas AI</th>
                        <th class="px-4 py-3.5 font-semibold whitespace-nowrap">Tenggat Penyelesaian</th>
                        <th class="px-4 py-3.5 font-semibold">Deskripsi</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($tenggatPerbaikan as $item)
                        @php
                            $prioritasKey = strtolower((string) $item->prioritas);
                            $statusKey = strtolower((string) $item->status);

                            $prioritasClasses = [
                                'kritis' => 'bg-merah/10 text-merah',
                                'sedang' => 'bg-oranye/10 text-oranye',
                                'rendah' => 'bg-kuning/20 text-abu-tua',
                            ];

                            $statusClasses = [
                                'aktif' => 'bg-hijau/10 text-hijau',
                                'nonaktif' => 'bg-abu-muda text-abu-tua',
                            ];

                            $jam = (int) $item->waktu_penyelesaian;
                            $hari = intdiv($jam, 24);
                            $sisaJam = $jam % 24;
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">{{ $loop->iteration }}</td>

                            <td class="px-4 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold capitalize {{ $prioritasClasses[$prioritasKey] ?? 'bg-gray-200 text-gray-600' }}">
                                    {{ $item->prioritas }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="font-semibold text-gray-800">
                                    {{ $jam }} jam
                                </span>

                                <p class="text-xs text-gray-400 mt-1">
                                    @if ($hari > 0)
                                        {{ $hari }} hari
                                        @if ($sisaJam > 0)
                                            {{ $sisaJam }} jam
                                        @endif
                                    @else
                                        {{ $jam }} jam
                                    @endif
                                </p>
                            </td>

                            <td class="px-4 py-3.5 min-w-[200px]">
                                {{ $item->deskripsi ?: 'Belum ada deskripsi.' }}
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$statusKey] ?? 'bg-gray-200 text-gray-600' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-4 text-sm font-semibold whitespace-nowrap">
                                    <a href="{{ route('super_admin.tenggat-perbaikan.edit', $item->id) }}"
                                       class="text-oranye hover:underline">
                                        Edit Tenggat
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-gray-400">
                                Belum ada konfigurasi tenggat perbaikan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                <span class="font-semibold">Catatan:</span>
                Tenggat perbaikan mengatur waktu penyelesaian laporan,
                bukan waktu respons awal.
            </p>
        </div>

    </div>
</div>
@endsection
