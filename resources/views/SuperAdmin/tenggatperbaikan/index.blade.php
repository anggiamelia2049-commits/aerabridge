@extends('template.layout')

@section('title', 'Tenggat Perbaikan')

@section('content')
<div class="p-6">
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm font-medium text-hijau">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-400/20 text-gray-700">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">No</th>
                        <th class="px-4 py-3.5 font-semibold">Prioritas AI</th>
                        <th class="px-4 py-3.5 font-semibold">Tenggat Penyelesaian</th>
                        <th class="px-4 py-3.5 font-semibold">Deskripsi</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($tenggatPerbaikan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-3.5 font-semibold capitalize">
                                {{ $item->prioritas }}
                            </td>

                            <td class="px-4 py-3.5 whitespace-nowrap">
                                {{ $item->waktu_penyelesaian }} jam
                            </td>

                            <td class="px-4 py-3.5">
                                {{ $item->deskripsi ?: '-' }}
                            </td>

                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                    'bg-hijau/10 text-hijau' => $item->status === 'aktif',
                                    'bg-abu-muda text-abu-tua' => $item->status !== 'aktif',
                                ])>
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-4 text-sm font-semibold">
                                    <a href="{{ route('super_admin.tenggat-perbaikan.edit', $item->id) }}"
                                       class="text-oranye hover:underline">
                                        Edit
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

    </div>
</div>
@endsection
