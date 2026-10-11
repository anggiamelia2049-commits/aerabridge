<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">
        <aside class="shrink-0">@include('template.sidebar-superadmin')</aside>

        <main class="flex-1 p-6 min-w-0">
            <div class="flex items-center justify-between mb-6 max-w-3xl">
                <h1 class="text-2xl font-bold text-cyan-6">Detail Template Pesan</h1>
                <a href="{{ route('super_admin.template-pesan.edit', $templatePesan) }}"
                   class="px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Edit</a>
            </div>

            <div class="bg-white rounded-2xl shadow p-6 max-w-3xl space-y-5 text-sm">
                <div>
                    <p class="text-xs text-gray-500">Kode Template</p>
                    <p class="font-mono">{{ $templatePesan->kode_template }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">Judul</p>
                    <p class="font-semibold text-cyan-6">{{ $templatePesan->judul }}</p>
                </div>

                <div class="flex gap-10">
                    <div>
                        <p class="text-xs text-gray-500">Kategori</p>
                        <p class="capitalize">{{ $templatePesan->kategori }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $templatePesan->status === 'aktif' ? 'bg-green-100 text-hijau' : 'bg-gray-200 text-gray-600' }}">
                            {{ ucfirst($templatePesan->status) }}
                        </span>
                    </div>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">Isi Pesan</p>
                    <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line">{{ $templatePesan->isi_pesan }}</div>
                </div>

                <div class="flex gap-10 text-xs text-gray-500">
                    <p>Dibuat: {{ $templatePesan->created_at?->translatedFormat('d F Y, H:i') }}</p>
                    <p>Diperbarui: {{ $templatePesan->updated_at?->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>

            <a href="{{ route('super_admin.template-pesan.index') }}" class="inline-block mt-5 text-sm text-gray-500 hover:underline">← Kembali</a>
        </main>
    </div>
</x-app-layout>