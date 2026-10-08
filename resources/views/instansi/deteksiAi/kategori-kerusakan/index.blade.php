@extends('template.layout')

@section('title', 'Kategori Kerusakan')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Kategori kerusakan</h1>
        <p class="text-sm text-slate-500">Jenis kerusakan yang dipakai untuk mengelompokkan laporan. Perubahan kategori dilakukan oleh Super Admin.</p>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 font-medium">No</th>
                    <th class="px-4 py-3 font-medium">Kategori</th>
                    <th class="px-4 py-3 font-medium">Deskripsi</th>
                    <th class="px-4 py-3 font-medium">Warna marker</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($kategori as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $kategori->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $item->nama_kategori }}</td>
                        <td class="px-4 py-3">{{ $item->deskripsi }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block h-4 w-4 rounded-full align-middle"
                                  style="background-color: {{ $item->warna_marker }}"></span>
                            <span class="ml-2 text-slate-500">{{ $item->warna_marker }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $item->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $kategori->links() }}</div>
</div>
@endsection
