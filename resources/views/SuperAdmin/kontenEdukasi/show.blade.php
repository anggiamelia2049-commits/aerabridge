<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Konten Edukasi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6 sm:p-8">

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-300 text-green-700 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($konten->thumbnail)
                    <img src="{{ asset('storage/' . $konten->thumbnail) }}" alt="Thumbnail"
                         class="w-full max-h-72 object-cover rounded-lg mb-5">
                @endif

                <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $konten->judul }}</h3>

                <div class="flex flex-wrap items-center gap-2 mb-5 text-sm text-gray-500">
                    <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 font-medium">
                        {{ $konten->kategori }}
                    </span>

                    <span @class([
                        'px-2.5 py-1 rounded-full text-xs font-semibold',
                        'bg-gray-100 text-gray-600' => $konten->status === 'draft',
                        'bg-green-100 text-green-700' => $konten->status === 'publish',
                        'bg-red-100 text-red-700' => $konten->status === 'nonaktif',
                    ])>
                        {{ ucfirst($konten->status) }}
                    </span>

                    <span>•</span>
                    <span>Ditulis oleh {{ $konten->penulisUser->nama ?? '-' }}</span>

                    <span>•</span>
                    <span>{{ $konten->created_at->format('d M Y, H:i') }}</span>
                </div>

                <div class="prose max-w-none text-gray-700 text-sm leading-relaxed whitespace-pre-line mb-8">
                    {{ $konten->isi }}
                </div>

                <div class="flex justify-end gap-2 border-t pt-5">
                    <a href="{{ route('super_admin.konten-edukasi.index') }}"
                       class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Kembali
                    </a>
                    <a href="{{ route('super_admin.konten-edukasi.edit', $konten->id) }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-lg">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>