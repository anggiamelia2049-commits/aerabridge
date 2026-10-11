<x-app-layout>
    <div class="flex min-h-screen bg-abu-muda">
        <aside class="sticky top-0 h-screen shrink-0 self-start">@include('template.sidebar-superadmin')</aside>

        <main class="flex-1 p-6 min-w-0">
            @if (session('success'))
                <div class="mb-4 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-hijau font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                {{-- Bagian atas kartu --}}
                <div class="flex items-center justify-between gap-4 px-6 py-5">
                    <p class="text-sm text-gray-600">Kelola template pesan otomatis yang dikirim ke pengguna.</p>
                    <a href="{{ route('super_admin.template-pesan.create') }}"
                       class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                        + Tambah Template
                    </a>
                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-cyan-6 text-white">
                            <tr>
                                <th class="px-4 py-3.5 font-semibold">Judul</th>
                                <th class="px-4 py-3.5 font-semibold">Kode</th>
                                <th class="px-4 py-3.5 font-semibold">Kategori</th>
                                <th class="px-4 py-3.5 font-semibold">Status</th>
                                <th class="px-4 py-3.5 font-semibold">Tanggal</th>
                                <th class="px-4 py-3.5 font-semibold"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-700">
                            @forelse ($templatePesan as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3.5 font-semibold">{{ $item->judul }}</td>
                                    <td class="px-4 py-3.5 font-mono text-xs">{{ $item->kode_template }}</td>
                                    <td class="px-4 py-3.5 capitalize">{{ $item->kategori }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $item->status === 'aktif' ? 'bg-green-50 text-hijau' : 'bg-gray-200 text-gray-600' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">{{ $item->created_at?->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex items-center justify-end gap-4 text-sm font-semibold">
                                            <a href="{{ route('super_admin.template-pesan.show', $item) }}" class="text-cyan-4 hover:underline">Detail</a>
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
            </div>
        </main>
    </div>
</x-app-layout>