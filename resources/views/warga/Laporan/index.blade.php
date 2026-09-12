<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Saya
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Daftar Laporan</h3>
                    <a href="{{ route('warga.laporan.create') }}"
                       class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        + Buat Laporan
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Judul</th>
                            <th class="px-4 py-2">Kategori</th>
                            <th class="px-4 py-2">Instansi</th>
                            <th class="px-4 py-2">Prioritas</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporan as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $item->judul }}</td>
                                <td class="px-4 py-2">{{ $item->kategori->nama ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $item->instansi->nama_instansi ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    <span @class([
                                        'px-2 py-1 rounded text-xs font-medium',
                                        'bg-red-100 text-red-700' => $item->tingkat_prioritas === 'Krisis',
                                        'bg-yellow-100 text-yellow-700' => $item->tingkat_prioritas === 'Sedang',
                                        'bg-green-100 text-green-700' => $item->tingkat_prioritas === 'Rendah',
                                    ])>
                                        {{ $item->tingkat_prioritas }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $item->status }}</td>
                                <td class="px-4 py-2">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('warga.laporan.show', $item->id) }}"
                                       class="text-indigo-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada laporan yang kamu buat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>