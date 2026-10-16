@extends('template.layout')

@section('title', 'Detail Notifikasi')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        @if (session('success'))
    <div class="mb-4 rounded-lg border border-hijau/30 bg-hijau/10 px-4 py-3 text-sm font-medium text-hijau" role="status">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 rounded-lg border border-merah/30 bg-merah/10 px-4 py-3 text-sm font-medium text-merah" role="alert">{{ session('error') }}</div>
@endif

        <div class="bg-white rounded-2xl shadow overflow-hidden">

            <div class="px-6 py-5">
                <h2 class="text-lg font-bold text-cyan-6">{{ $notifikasi->judul }}</h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ ucfirst($notifikasi->tipe) }}, {{ $notifikasi->created_at->format('d-m-Y H:i') }}
                </p>
            </div>

            <div class="px-6 pb-6 text-sm text-gray-700">
                <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line">{{ $notifikasi->isi }}</div>

                @if ($notifikasi->laporan)
                    <div class="mt-4">
                        <p class="text-xs text-gray-500">Laporan terkait</p>
                        <p class="font-semibold">{{ $notifikasi->laporan->judul }}</p>
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 px-6 py-5">
                <a href="{{ route('petugas.notifikasi.index') }}" class="mr-auto text-sm text-gray-500 hover:underline">← Kembali</a>

                <form action="{{ route('petugas.notifikasi.destroy', $notifikasi->id) }}" method="POST"
                      onsubmit="return confirm('Hapus notifikasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 rounded-lg border border-merah/40 text-sm font-semibold text-merah hover:bg-merah/10 transition">Hapus notifikasi</button>
                </form>

                @if ($penugasan)
                    <a href="{{ route('petugas.penugasan.show', $penugasan->id) }}"
                       class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Buka tugas</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection