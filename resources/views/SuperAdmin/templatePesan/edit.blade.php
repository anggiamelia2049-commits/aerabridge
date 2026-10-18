@extends('template.layout')

@section('content')
@php
    $field = 'w-full h-11 px-4 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
    $label = 'block text-sm font-semibold text-cyan-6 mb-1.5';
@endphp

<div class="p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-5">
        </div>

        <form method="POST" action="{{ route('super_admin.template-pesan.update', $templatePesan) }}" class="px-6 pb-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="kode_template" class="{{ $label }}">Kode Template <span class="text-merah">*</span></label>
                <input type="text" id="kode_template" name="kode_template"
                       value="{{ old('kode_template', $templatePesan->kode_template) }}"
                       class="{{ $field }}" required>
                <p class="text-xs text-gray-500 mt-1">Harus unik. Dipakai sistem untuk memilih pesan yang tepat.</p>
                @error('kode_template') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="judul" class="{{ $label }}">Judul Pesan <span class="text-merah">*</span></label>
                <input type="text" id="judul" name="judul"
                       value="{{ old('judul', $templatePesan->judul) }}"
                       class="{{ $field }}" required>
                @error('judul') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="kategori" class="{{ $label }}">Kategori <span class="text-merah">*</span></label>
                    <select id="kategori" name="kategori" class="{{ $field }}" required>
                        @foreach (['laporan' => 'Laporan', 'penugasan' => 'Penugasan', 'sla' => 'SLA', 'reward' => 'Reward', 'lainnya' => 'Lainnya'] as $nilai => $teks)
                            <option value="{{ $nilai }}" @selected(old('kategori', $templatePesan->kategori) === $nilai)>{{ $teks }}</option>
                        @endforeach
                    </select>
                    @error('kategori') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="{{ $label }}">Status <span class="text-merah">*</span></label>
                    <select id="status" name="status" class="{{ $field }}" required>
                        @foreach (['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $nilai => $teks)
                            <option value="{{ $nilai }}" @selected(old('status', $templatePesan->status) === $nilai)>{{ $teks }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="isi_pesan" class="{{ $label }}">Isi Pesan <span class="text-merah">*</span></label>
                <textarea id="isi_pesan" name="isi_pesan" rows="7" required
                          class="w-full px-4 py-3 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">{{ old('isi_pesan', $templatePesan->isi_pesan) }}</textarea>
                @error('isi_pesan') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-3">
                <a href="{{ route('super_admin.template-pesan.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Batal</a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
