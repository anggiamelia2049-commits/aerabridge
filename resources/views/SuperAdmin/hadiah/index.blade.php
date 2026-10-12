@extends('template.layout')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">Kelola hadiah yang bisa ditukar dengan poin kontribusi.</p>
            <a href="{{ route('super_admin.hadiah.create') }}"
               class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                + Tambah Hadiah
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">Gambar</th>
                        <th class="px-4 py-3.5 font-semibold">Nama Hadiah</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Poin</th>
                        <th class="px-4 py-3.5 font-semibold text-right">Stok</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($hadiahs as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_hadiah }}"
                                         class="w-12 h-12 object-cover rounded-lg border border-abu-muda">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 border border-abu-muda flex items-center justify-center text-[10px] text-gray-400">Tidak ada</div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 font-semibold">{{ $item->nama_hadiah }}</td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">{{ number_format($item->poin_dibutuhkan, 0, ',', '.') }} poin</td>
                            <td class="px-4 py-3.5 text-right">{{ $item->stok }}</td>
                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold capitalize',
                                    'bg-hijau/10 text-hijau' => $item->status === 'tersedia',
                                    'bg-kuning/20 text-abu-tua' => $item->status === 'habis',
                                    'bg-abu-muda text-abu-tua' => $item->status === 'nonaktif',
                                ])>{{ $item->status }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-4 text-sm font-semibold">
                                    <a href="{{ route('super_admin.hadiah.show', $item->id) }}" class="text-cyan-4 hover:underline">Detail</a>
                                    <a href="{{ route('super_admin.hadiah.edit', $item->id) }}" class="text-oranye hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('super_admin.hadiah.destroy', $item->id) }}"
                                          onsubmit="return confirm('Hapus hadiah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-merah hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-gray-400">Belum ada hadiah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection