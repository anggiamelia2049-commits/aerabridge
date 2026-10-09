@extends('template.layout')

@section('title', 'Alihkan Tugas')

@section('content')

<div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 px-6 py-4">
        <h2 class="text-lg font-semibold text-cyan-6">Alihkan Tugas</h2>
        <p class="mt-1 text-sm text-abu-tua">{{ $penugasan->laporan->judul ?? '-' }}</p>
    </div>

    <form action="{{ route('instansi.penugasan.update', $penugasan->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <input type="hidden" name="aksi" value="redisposisi">

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <div>
                <label for="tim_satgas_id" class="mb-2 block text-sm font-medium text-gray-700">Tim Satgas</label>
                <select id="tim_satgas_id" name="tim_satgas_id"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]">
                    <option value="">-- Pilih Tim Satgas --</option>
                    @foreach ($timSatgas as $item)
                        <option value="{{ $item->id }}"
                            {{ old('tim_satgas_id', $penugasan->tim_satgas_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_tim }}
                        </option>
                    @endforeach
                </select>
                @error('tim_satgas_id')
                    <p class="mt-1 text-xs text-merah">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="petugas_id" class="mb-2 block text-sm font-medium text-gray-700">Petugas</label>
                <select id="petugas_id" name="petugas_id"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]">
                    <option value="">-- Pilih Petugas --</option>
                    @foreach ($petugas as $item)
                        <option value="{{ $item->id }}"
                            {{ old('petugas_id', $penugasan->petugas_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
                @error('petugas_id')
                    <p class="mt-1 text-xs text-merah">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="catatan" class="mb-2 block text-sm font-medium text-gray-700">Catatan</label>
                <textarea id="catatan" name="catatan" rows="4"
                          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]">{{ old('catatan', $penugasan->catatan) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
            <a href="{{ route('instansi.penugasan.index') }}"
               class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                Kembali
            </a>
            <button type="submit"
                    class="rounded-lg bg-cyan-4 px-5 py-2.5 text-sm font-medium text-white hover:bg-cyan-6">
                Alihkan Tugas
            </button>
        </div>
    </form>
</div>

@endsection
