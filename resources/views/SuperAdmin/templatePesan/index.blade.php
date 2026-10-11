<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">
        <aside class="shrink-0">@include('template.sidebar-superadmin')</aside>

        <main class="flex-1 p-6 min-w-0">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-cyan-6">Pesan Konfirmasi</h1>
                    <p class="text-sm text-gray-500">Kelola template pesan otomatis untuk pengguna.</p>
                </div>
                <a href="{{ route('super_admin.template-pesan.create') }}"
                   class="px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">+ Tambah Template</a>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-hijau font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-cyan-6 text-white">
                        <tr>
                            <th class="px-4 py-3 font-semibold">No</th>
                            <th class="px-4 py-3 font-semibold">Kode</th>
                            <th class="px-4 py-3 font-semibold">Judul</th>
                            <th class="px-4 py-3 font-semibold">Kategori</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-abu-muda">
                        @forelse ($templatePesan as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-mono text-xs">{{ $item->kode_template }}</td>
                                <td class="px-4 py-3 font-medium text-cyan-6">{{ $item->judul }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-1 rounded-full bg-cyan-muda/50 text-cyan-6 text-xs font-semibold capitalize">{{ $item->kategori }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $item->status === 'aktif' ? 'bg-green-100 text-hijau' : 'bg-gray-200 text-gray-600' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-3 text-xs font-semibold">
                                        <a href="{{ route('super_admin.template-pesan.show', $item) }}" class="text-cyan-4 hover:underline">Lihat</a>
                                        <a href="{{ route('super_admin.template-pesan.edit', $item) }}" class="text-oranye hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('super_admin.template-pesan.destroy', $item) }}"
                                              onsubmit="return confirm('Hapus template ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-merah hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-gray-400">Belum ada template pesan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-app-layout>