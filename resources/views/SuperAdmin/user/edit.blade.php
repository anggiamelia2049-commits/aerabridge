@extends('template.layout')

@section('title', 'Edit User')

@section('content')
@php
    $field = 'w-full h-11 px-4 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
    $label = 'block text-sm font-semibold text-cyan-6 mb-1.5';
    $u = $dataedituser;

    $groups = [
        'Data Pribadi' => [
            ['name' => 'nik', 'label' => 'NIK', 'value' => $u->nik],
            ['name' => 'nama', 'label' => 'Nama', 'value' => $u->nama],
            ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'value' => $u->jenis_kelamin, 'options' => ['Laki-laki', 'Perempuan']],
            ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'value' => $u->tanggal_lahir?->format('Y-m-d')],
            ['name' => 'pekerjaan', 'label' => 'Pekerjaan', 'value' => $u->pekerjaan],
            ['name' => 'penyandang_disabilitas', 'label' => 'Penyandang Disabilitas', 'type' => 'select', 'value' => $u->penyandang_disabilitas, 'options' => ['Ya', 'Tidak']],
        ],
        'Akun & Kontak' => [
            ['name' => 'username', 'label' => 'Username', 'value' => $u->username],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'value' => $u->email],
            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => false, 'note' => 'Kosongkan jika tidak ingin mengubah password.'],
            ['name' => 'no_hp', 'label' => 'No HP', 'value' => $u->no_hp],
            ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'value' => $u->alamat, 'required' => false, 'full' => true],
        ],
    ];
@endphp

<div class="p-6">
    <div class="max-w-5xl mx-auto">

        <form action="{{ route('super_admin.user.update', $u->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow p-6 mb-5 overflow-hidden">

                {{-- Banner + profil ringkas --}}
                <div class="-mx-6 -mt-6 h-24 bg-gradient-to-r from-cyan-6 to-cyan-4"></div>

                <div class="-mt-12 mb-6 flex flex-wrap items-start gap-5">
                    <div class="w-24 h-24 shrink-0">
                        <img id="previewFoto" src="{{ $u->foto ? asset('storage/' . $u->foto) : '' }}" alt="{{ $u->nama }}"
                             class="{{ $u->foto ? '' : 'hidden' }} w-24 h-24 rounded-full object-cover border-4 border-white bg-white">
                        <div id="placeholderFoto"
                             class="{{ $u->foto ? 'hidden' : '' }} w-24 h-24 rounded-full border-4 border-white bg-cyan-3 flex items-center justify-center text-3xl font-bold text-white">
                            {{ mb_strtoupper(mb_substr($u->nama, 0, 1)) }}
                        </div>
                    </div>

                    <div class="sm:pt-14">
                        <p class="text-h3">{{ $u->nama }}</p>
                        <div class="mt-1 flex flex-wrap gap-2">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-cyan-3/20 text-cyan-6">{{ str_replace('_', ' ', ucfirst($u->role)) }}</span>
                            <span @class([
                                'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold',
                                'bg-hijau/10 text-hijau' => $u->status === 'Aktif',
                                'bg-abu-muda text-abu-tua' => $u->status !== 'Aktif',
                            ])>
                                <span @class(['w-1.5 h-1.5 rounded-full', 'bg-hijau' => $u->status === 'Aktif', 'bg-abu-tua' => $u->status !== 'Aktif'])></span>
                                {{ $u->status }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Ganti foto --}}
                <div class="pb-6">
                    <h2 class="text-h2 mb-3">Ganti Foto</h2>
                    <label for="foto" class="sr-only">Foto</label>
                    <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-cyan-3/20 file:text-cyan-6 file:font-semibold">
                    <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengganti foto. JPG atau PNG, maksimal 2 MB.</p>
                    @error('foto') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Role & Status --}}
                <div class="py-6 border-t border-gray-200">
                    <h2 class="text-h2 mb-4">Role &amp; Status</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="role" class="{{ $label }}">Role <span class="text-merah">*</span></label>
                            <select id="role" name="role" class="{{ $field }}" required>
                                @foreach (['super_admin' => 'Super Admin', 'warga' => 'Warga', 'instansi' => 'Instansi', 'petugas' => 'Petugas'] as $nilai => $teks)
                                    <option value="{{ $nilai }}" @selected(old('role', $u->role) === $nilai)>{{ $teks }}</option>
                                @endforeach
                            </select>
                            @error('role') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="status" class="{{ $label }}">Status <span class="text-merah">*</span></label>
                            <select id="status" name="status" class="{{ $field }}" required>
                                @foreach (['Aktif', 'Nonaktif'] as $s)
                                    <option value="{{ $s }}" @selected(old('status', $u->status) === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Instansi: hanya untuk role instansi / petugas --}}
                        <div id="boxInstansi" class="hidden sm:col-span-2">
                            <label for="instansi_id" class="{{ $label }}">Instansi <span class="text-merah">*</span></label>
                            <select id="instansi_id" name="instansi_id" class="{{ $field }}">
                                <option value="">Pilih instansi</option>
                                @foreach ($instansis as $i)
                                    <option value="{{ $i->id }}" @selected(old('instansi_id', $u->instansi_id) == $i->id)>{{ $i->nama_instansi }}</option>
                                @endforeach
                            </select>
                            @error('instansi_id') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Data Pribadi & Akun/Kontak --}}
                @foreach ($groups as $judul => $fields)
                    <div class="py-6 border-t border-gray-200 last:pb-0">
                        <h2 class="text-h2 mb-4">{{ $judul }}</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            @foreach ($fields as $f)
                                @php
                                    $tipe = $f['type'] ?? 'text';
                                    $wajib = $f['required'] ?? true;
                                    $n = $f['name'];
                                    $nilai = old($n, $f['value'] ?? '');
                                @endphp
                                <div class="{{ ($f['full'] ?? false) ? 'sm:col-span-2' : '' }}">
                                    <label for="{{ $n }}" class="{{ $label }}">{{ $f['label'] }} @if ($wajib)<span class="text-merah">*</span>@endif</label>

                                    @if ($tipe === 'select')
                                        <select id="{{ $n }}" name="{{ $n }}" class="{{ $field }}" @required($wajib)>
                                            @foreach ($f['options'] as $o)
                                                <option value="{{ $o }}" @selected($nilai === $o)>{{ $o }}</option>
                                            @endforeach
                                        </select>
                                    @elseif ($tipe === 'textarea')
                                        <textarea id="{{ $n }}" name="{{ $n }}" rows="3"
                                                  class="w-full px-4 py-3 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">{{ $nilai }}</textarea>
                                    @else
                                        <input type="{{ $tipe }}" id="{{ $n }}" name="{{ $n }}"
                                               @if ($tipe !== 'password') value="{{ $nilai }}" @endif
                                               class="{{ $field }}" @required($wajib)>
                                    @endif

                                    @isset($f['note']) <p class="text-xs text-gray-500 mt-1">{{ $f['note'] }}</p> @endisset
                                    @error($n) <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ===== Tombol aksi (di bawah card) ===== --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('super_admin.user.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-semibold text-gray-700 !no-underline hover:!no-underline hover:bg-gray-50 transition">Batal</a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const roleEl = document.getElementById('role');
    const boxInstansi = document.getElementById('boxInstansi');
    const instansiEl = document.getElementById('instansi_id');
    const fotoEl = document.getElementById('foto');
    const previewEl = document.getElementById('previewFoto');
    const placeholderEl = document.getElementById('placeholderFoto');

    // Role yang wajib punya instansi
    const roleButuhInstansi = ['instansi', 'petugas'];

    function syncInstansi() {
        const tampil = roleButuhInstansi.includes(roleEl.value);
        boxInstansi.classList.toggle('hidden', !tampil);
        instansiEl.required = tampil;
        if (!tampil) instansiEl.value = '';
    }

    roleEl.addEventListener('change', syncInstansi);
    syncInstansi();

    // Pratinjau foto baru
    fotoEl.addEventListener('change', function () {
        const file = fotoEl.files[0];
        if (!file) return;
        previewEl.src = URL.createObjectURL(file);
        previewEl.classList.remove('hidden');
        placeholderEl.classList.add('hidden');
    });
</script>
@endsection
