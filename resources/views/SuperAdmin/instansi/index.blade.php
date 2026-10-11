@extends('template.layout')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">Kelola instansi pemerintah yang menerima dan menangani laporan.</p>
            <a href="{{ route('super_admin.instansi.create') }}"
               class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                + Tambah Instansi
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">Logo</th>
                        <th class="px-4 py-3.5 font-semibold">Nama Instansi</th>
                        <th class="px-4 py-3.5 font-semibold">No. Telp</th>
                        <th class="px-4 py-3.5 font-semibold">Email</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($instansis as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">
                                @if ($item->logo)
                                    <img src="{{ asset('storage/' . $item->logo) }}" alt="{{ $item->nama_instansi }}"
                                         class="w-12 h-12 object-contain rounded-lg border border-abu-muda bg-white">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 border border-abu-muda flex items-center justify-center text-[10px] text-gray-400">Tidak ada</div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 font-semibold">{{ $item->nama_instansi }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">{{ $item->no_telp }}</td>
                            <td class="px-4 py-3.5">{{ $item->email ?: '-' }}</td>
                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                    'bg-hijau/10 text-hijau' => $item->status === 'Aktif',
                                    'bg-abu-muda text-abu-tua' => $item->status === 'Nonaktif',
                                ])>{{ $item->status }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-4 text-sm font-semibold">
                                    <a href="{{ route('super_admin.instansi.show', $item->id) }}" class="text-cyan-4 hover:underline">Detail</a>
                                    <a href="{{ route('super_admin.instansi.edit', $item->id) }}" class="text-oranye hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('super_admin.instansi.destroy', $item->id) }}"
                                          onsubmit="return confirm('Hapus instansi ini? Semua laporan yang terhubung ke instansi ini juga akan ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-merah hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-gray-400">Belum ada instansi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection