
@extends('template.layout')

@section('title', 'Edit Tenggat Perbaikan')

@section('content')
<div class="p-6">

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-merah">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">
                Ubah batas waktu penyelesaian sesuai prioritas AI.
            </p>
        </div>

        <form method="POST"
              action="{{ route('super_admin.tenggat-perbaikan.update', $tenggat->id) }}">

            @csrf
            @method('PUT')

            <div class="px-6 pb-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Prioritas AI
                    </label>

                    <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm capitalize text-gray-600">
                        {{ $tenggat->prioritas }}
                    </div>
                </div>

                <div>
                    <label for="waktu_penyelesaian"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Tenggat Penyelesaian (Jam)
                    </label>

                    <input type="number"
                           id="waktu_penyelesaian"
                           name="waktu_penyelesaian"
                           min="1"
                           step="1"
                           required
                           value="{{ old('waktu_penyelesaian', $tenggat->waktu_penyelesaian) }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none focus:border-cyan-4 focus:ring-2 focus:ring-cyan-4/20 @error('waktu_penyelesaian') border-merah @enderror">

                    @error('waktu_penyelesaian')
                        <p class="mt-1 text-xs text-merah">{{ $message }}</p>
                    @enderror

                    <p class="mt-2 text-xs text-gray-400">
                        Contoh: 12 jam untuk 12 jam, 72 jam untuk 3 hari,
                        atau 168 jam untuk 7 hari.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi
                    </label>

                    <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500">
                        {{ $tenggat->deskripsi ?: 'Belum ada deskripsi.' }}
                    </div>
                </div>

            </div>

            <div class="flex items-center gap-4 border-t border-gray-200 px-6 py-5">
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                    Simpan Perubahan
                </button>

                <a href="{{ route('super_admin.tenggat-perbaikan.index') }}"
                   class="text-sm font-semibold text-gray-600 hover:underline">
                    Kembali
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
