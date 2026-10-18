@extends('template.layout')

@section('title', 'Detail Instansi')

@section('content')
<div class="p-6">
    <div class="max-w-3xl mx-auto">

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-6 space-y-5 text-sm text-gray-700">
                @if ($instansi->logo)
                    <img src="{{ asset('storage/' . $instansi->logo) }}" alt="{{ $instansi->nama_instansi }}"
                         class="w-40 h-40 object-contain rounded-xl border border-abu-muda bg-white">
                @endif

                <div>
                    <p class="text-xs text-gray-500">Nama Instansi</p>
                    <p class="font-semibold text-cyan-6 text-base">{{ $instansi->nama_instansi }}</p>
                </div>

                <div class="flex flex-wrap gap-10">
                    <div>
                        <p class="text-xs text-gray-500">No. Telp</p>
                        <p>{{ $instansi->no_telp }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Email</p>
                        <p>{{ $instansi->email ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Status</p>
                        <span @class([
                            'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                            'bg-hijau/10 text-hijau' => $instansi->status === 'Aktif',
                            'bg-abu-muda text-abu-tua' => $instansi->status === 'Nonaktif',
                        ])>{{ $instansi->status }}</span>
                    </div>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">Alamat</p>
                    <div class="rounded-lg border border-abu-muda bg-gray-50 p-4">{{ $instansi->alamat ?: '-' }}</div>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">Deskripsi</p>
                    <div class="rounded-lg border border-abu-muda bg-gray-50 p-4">{{ $instansi->deskripsi ?: '-' }}</div>
                </div>

                <div class="flex flex-wrap gap-x-10 gap-y-1 text-xs text-gray-500">
                    <p class="m-0">Dibuat: {{ $instansi->created_at?->translatedFormat('d F Y, H:i') }}</p>
                    <p class="m-0">Diperbarui: {{ $instansi->updated_at?->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>

            {{-- ===== Tombol aksi (pojok kanan bawah) ===== --}}
           <div class="flex items-center justify-end gap-3 px-6 py-5">
                <a href="{{ route('super_admin.instansi.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-semibold text-gray-700 !no-underline hover:!no-underline hover:bg-gray-50 transition">Batal</a>
                <a href="{{ route('super_admin.instansi.edit', $instansi->id) }}"
                   class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white hover:text-white text-sm font-bold !no-underline hover:!no-underline transition">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
