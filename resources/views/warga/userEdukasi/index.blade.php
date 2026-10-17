@extends('template.layout')

@section('title', 'Edukasi')

@section('content')
<div class="p-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="px-6 py-5">
            <p class="text-sm text-gray-600">
                Pelajari perawatan fasilitas publik. Setiap konten yang kamu selesaikan memberi <span class="font-semibold text-cyan-6">+10 poin</span>.
            </p>
        </div>

        @if ($kontenList->isEmpty())
            <p class="px-6 pb-10 pt-4 text-center text-gray-400">Belum ada konten edukasi.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 px-6 pb-6">
                @foreach ($kontenList as $konten)
                    @php
                        $progress = $konten->progress->first();
                        $status = $progress->status ?? 'belum_dibaca';
                    @endphp

                    <div class="rounded-xl border border-abu-muda overflow-hidden flex flex-col">
                        @if ($konten->thumbnail)
                            <img src="{{ asset('storage/' . $konten->thumbnail) }}" alt="{{ $konten->judul }}"
                                 class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-xs text-gray-400">Tidak ada gambar</div>
                        @endif

                        <div class="p-4 flex flex-col flex-1">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="text-xs text-gray-500">{{ $konten->kategori ?: 'Umum' }}</span>
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                    'bg-hijau/10 text-hijau' => $status === 'selesai',
                                    'bg-oranye/10 text-oranye' => $status === 'sedang',
                                    'bg-abu-muda text-abu-tua' => ! in_array($status, ['selesai', 'sedang']),
                                ])>
                                    @switch($status)
                                        @case('selesai') Selesai @break
                                        @case('sedang') Sedang dibaca @break
                                        @default Belum dibaca
                                    @endswitch
                                </span>
                            </div>

                            <h3 class="font-semibold text-cyan-6 leading-snug">{{ $konten->judul }}</h3>

                            <a href="{{ route('warga.user-edukasi.show', $konten->id) }}"
                               class="mt-4 self-start text-sm font-semibold text-cyan-4 hover:underline">
                                {{ $status === 'selesai' ? 'Baca lagi' : 'Baca sekarang' }} →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection