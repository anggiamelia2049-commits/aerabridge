@extends('template.layout')

@section('content')
<div class="p-6">
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm font-medium text-hijau">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tombol di luar card --}}
    <div class="mb-4 flex justify-end">
        <a href="{{ route('super_admin.template-pesan.create') }}"
           class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold no-underline hover:no-underline transition">
            <span class="text-base leading-none">+</span> Tambah Template
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-400/20 text-gray-700">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">No</th>
                        <th class="px-4 py-3.5 font-semibold">Judul</th>
                        <th class="px-4 py-3.5 font-semibold">Kode</th>
                        <th class="px-4 py-3.5 font-semibold">Kategori</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold">Tanggal</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($templatePesan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-3.5 font-semibold">
                                {{ $item->judul }}
                            </td>

                            <td class="px-4 py-3.5 font-mono text-xs whitespace-nowrap">
                                {{ $item->kode_template }}
                            </td>

                            <td class="px-4 py-3.5 capitalize">
                                {{ $item->kategori }}
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

                            <td class="px-4 py-3.5 whitespace-nowrap">
                                {{ $item->created_at?->translatedFormat('d M Y') }}
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-4 text-sm font-semibold">
                                    <a href="{{ route('super_admin.template-pesan.show', $item) }}"
                                       class="text-cyan-4 hover:underline">
                                        Detail
                                    </a>

                                    <a href="{{ route('super_admin.template-pesan.edit', $item) }}"
                                       class="text-oranye hover:underline">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('super_admin.template-pesan.destroy', $item) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus template ini?')">
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
                            <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                                Belum ada template pesan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
