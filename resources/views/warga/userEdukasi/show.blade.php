@extends('template.layout')

@section('title', $konten->judul)

@section('content')
<div class="p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <span class="text-xs text-gray-500">{{ $konten->kategori ?: 'Umum' }}</span>
            <a href="{{ route('warga.user-edukasi.index') }}" class="text-sm text-gray-500 hover:underline">← Kembali</a>
        </div>

        <div class="px-6 pb-6 space-y-5">
            <h1 class="text-2xl font-bold text-cyan-6">{{ $konten->judul }}</h1>

            @if ($konten->thumbnail)
                <img src="{{ asset('storage/' . $konten->thumbnail) }}" alt="{{ $konten->judul }}"
                     class="w-full max-h-80 object-cover rounded-xl border border-abu-muda">
            @endif

            <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $konten->isi }}</div>

            <div class="pt-5 border-t border-gray-200">
                @if ($progress->status === 'selesai')
                    <p class="text-sm font-semibold text-hijau">
                        ✓ Kamu sudah menyelesaikan konten ini
                        @if ($progress->selesai_pada)
                            pada {{ $progress->selesai_pada->translatedFormat('d M Y, H:i') }}
                        @endif
                    </p>
                @else
                    <form action="{{ route('warga.user-edukasi.selesai', $konten->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                            Tandai Selesai (+10 poin)
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection