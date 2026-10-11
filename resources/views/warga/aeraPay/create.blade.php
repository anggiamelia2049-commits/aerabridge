@extends('template.layout')

@section('content')
@php
    $field = 'w-full h-11 px-4 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
    $label = 'block text-sm font-semibold text-cyan-6 mb-1.5';
    $maksPoin = intdiv($totalPoin, 1000) * 1000;
@endphp

<div class="p-6">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-5">
            <p class="text-sm text-gray-600">Tukar poin kontribusi menjadi saldo AERA Pay simulasi.</p>
        </div>

        <div class="px-6 pb-6 space-y-5">
            <div class="rounded-lg border border-abu-muda bg-gray-50 p-4">
                <p class="text-xs text-gray-500">Poin kamu saat ini</p>
                <p class="text-2xl font-bold text-cyan-6">{{ number_format($totalPoin, 0, ',', '.') }} poin</p>
                <p class="text-xs text-gray-500 mt-1">Rate: 1.000 poin = Rp500. Jumlah harus kelipatan 1.000.</p>
            </div>

            @if ($maksPoin < 1000)
                <div class="rounded-lg border border-kuning bg-kuning/10 px-4 py-3 text-sm text-abu-tua">
                    Poinmu belum cukup. Minimal 1.000 poin untuk bisa dikonversi.
                </div>
            @endif

            <form method="POST" action="{{ route('warga.aeraPay.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="jumlah_poin" class="{{ $label }}">Jumlah Poin <span class="text-merah">*</span></label>
                    <input type="number" id="jumlah_poin" name="jumlah_poin"
                           min="1000" step="1000" max="{{ max($maksPoin, 1000) }}"
                           value="{{ old('jumlah_poin') }}" placeholder="contoh: 1000"
                           class="{{ $field }}" required @disabled($maksPoin < 1000)>
                    @error('jumlah_poin') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror

                    <p class="text-sm text-gray-600 mt-2">
                        Kamu akan menerima:
                        <span id="estimasi" class="font-bold text-cyan-4">Rp0</span>
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('warga.aeraPay.index') }}"
                       class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" @disabled($maksPoin < 1000)
                            class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition disabled:opacity-50 disabled:cursor-not-allowed">
                        Konversi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const inputPoin = document.getElementById('jumlah_poin');
    const estimasi = document.getElementById('estimasi');

    function hitung() {
        const poin = parseInt(inputPoin.value) || 0;
        const nominal = Math.floor(poin / 1000) * 500;
        estimasi.textContent = 'Rp' + nominal.toLocaleString('id-ID');
    }

    inputPoin.addEventListener('input', hitung);
    hitung();
</script>
@endsection