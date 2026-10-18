@extends('template.layout')

@section('title', 'Tambah User')

@section('content')
@php
    $field = 'w-full h-11 px-4 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
    $label = 'block text-sm font-semibold text-cyan-6 mb-1.5';

    $groups = [
        'Data Pribadi' => [
            ['name' => 'nik', 'label' => 'NIK', 'ph' => 'Masukkan NIK'],
            ['name' => 'nama', 'label' => 'Nama', 'ph' => 'Masukkan nama'],
            ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'ph' => 'Pilih jenis kelamin', 'options' => ['Laki-laki', 'Perempuan']],
            ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date'],
            ['name' => 'pekerjaan', 'label' => 'Pekerjaan', 'ph' => 'Masukkan pekerjaan'],
            ['name' => 'penyandang_disabilitas', 'label' => 'Penyandang Disabilitas', 'type' => 'select', 'ph' => 'Pilih', 'options' => ['Ya', 'Tidak']],
        ],
        'Akun & Kontak' => [
            ['name' => 'username', 'label' => 'Username', 'ph' => 'Masukkan username'],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'ph' => 'Masukkan email'],
            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'ph' => 'Masukkan password'],
            ['name' => 'no_hp', 'label' => 'No HP', 'ph' => 'Masukkan nomor HP'],
            ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'ph' => 'Masukkan alamat', 'required' => false, 'full' => true],
        ],
    ];
@endphp

<div class="p-6">
    <div class="max-w-5xl mx-auto">

        <form action="{{ route('super_admin.user.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-2xl shadow p-6 mb-5 overflow-hidden">

                {{-- Banner + profil ringkas (nama & role ikut berubah saat diisi) --}}
                <div class="-mx-6 -mt-6 h-24 bg-gradient-to-r from-cyan-6 to-cyan-4"></div>

                <div class="-mt-12 mb-6 flex flex-wrap items-start gap-5">
                    <div class="w-24 h-24 shrink-0">
                        <div id="placeholderFoto"
                             class="w-24 h-24 rounded-full border-4 border-white bg-cyan-3 text-white flex items-center justify-center">
                            <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                        </div>
                        <img id="previewFoto" src="" alt="Pratinjau foto"
                             class="hidden w-24 h-24 rounded-full object-cover border-4 border-white bg-white">
                    </div>

                    <div class="sm:pt-14">
                        <p id="heroNama" class="text-h3">Pengguna baru</p>
                        <div class="mt-1">
                            <span id="heroRole" class="hidden inline-block px-3 py-1 rounded-full text-xs font-semibold bg-cyan-3/20 text-cyan-6"></span>
                        </div>
                    </div>
                </div>

                {{-- Foto Profil --}}
                <div class="pb-6">
                    <h2 class="text-h2 mb-3">Foto Profil</h2>
                    <label for="foto" class="sr-only">Foto</label>
                    <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-cyan-3/20 file:text-cyan-6 file:font-semibold">
                    <p class="text-xs text-gray-500 mt-2">Opsional. JPG atau PNG, maksimal 2 MB.</p>
                    @error('foto') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Role & Status --}}
                <div class="py-6 border-t border-gray-200">
                    <h2 class="text-h2 mb-4">Role &amp; Status</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="role" class="{{ $label }}">Role <span class="text-merah">*</span></label>
                            <select id="role" name="role" class="{{ $field }}" required>
                                <option value="">Pilih role</option>
                                @foreach (['super_admin' => 'Super Admin', 'warga' => 'Warga', 'instansi' => 'Instansi', 'petugas' => 'Petugas'] as $nilai => $teks)
                                    <option value="{{ $nilai }}" @selected(old('role') === $nilai)>{{ $teks }}</option>
                                @endforeach
                            </select>
                            @error('role') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="status" class="{{ $label }}">Status <span class="text-merah">*</span></label>
                            <select id="status" name="status" class="{{ $field }}" required>
                                @foreach (['Aktif', 'Nonaktif'] as $s)
                                    <option value="{{ $s }}" @selected(old('status', 'Aktif') === $s)>{{ $s }}</option>
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
                                    <option value="{{ $i->id }}" @selected(old('instansi_id') == $i->id)>{{ $i->nama_instansi }}</option>
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
                                @endphp
                                <div class="{{ ($f['full'] ?? false) ? 'sm:col-span-2' : '' }}">
                                    <label for="{{ $n }}" class="{{ $label }}">{{ $f['label'] }} @if ($wajib)<span class="text-merah">*</span>@endif</label>

                                    @if ($tipe === 'select')
                                        <select id="{{ $n }}" name="{{ $n }}" class="{{ $field }}" @required($wajib)>
                                            <option value="">{{ $f['ph'] }}</option>
                                            @foreach ($f['options'] as $o)
                                                <option value="{{ $o }}" @selected(old($n) === $o)>{{ $o }}</option>
                                            @endforeach
                                        </select>
                                    @elseif ($tipe === 'textarea')
                                        <textarea id="{{ $n }}" name="{{ $n }}" rows="3" placeholder="{{ $f['ph'] }}"
                                                  class="w-full px-4 py-3 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">{{ old($n) }}</textarea>
                                    @else
                                        <input type="{{ $tipe }}" id="{{ $n }}" name="{{ $n }}"
                                               @if ($tipe !== 'password') value="{{ old($n) }}" @endif
                                               @isset($f['ph']) placeholder="{{ $f['ph'] }}" @endisset
                                               class="{{ $field }}" @required($wajib)>
                                    @endif

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
                        class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const roleEl = document.getElementById('role');
    const namaEl = document.getElementById('nama');
    const boxInstansi = document.getElementById('boxInstansi');
    const instansiEl = document.getElementById('instansi_id');
    const fotoEl = document.getElementById('foto');
    const previewEl = document.getElementById('previewFoto');
    const placeholderEl = document.getElementById('placeholderFoto');
    const heroNama = document.getElementById('heroNama');
    const heroRole = document.getElementById('heroRole');

    // Role yang wajib punya instansi
    const roleButuhInstansi = ['instansi', 'petugas'];

    function syncInstansi() {
        const tampil = roleButuhInstansi.includes(roleEl.value);
        boxInstansi.classList.toggle('hidden', !tampil);
        instansiEl.required = tampil;
        if (!tampil) instansiEl.value = '';
    }

    // Nama & role di header ikut berubah saat form diisi
    function syncHero() {
        heroNama.textContent = namaEl.value.trim() || 'Pengguna baru';
        const ada = roleEl.value !== '';
        heroRole.textContent = ada ? roleEl.options[roleEl.selectedIndex].text : '';
        heroRole.classList.toggle('hidden', !ada);
    }

    roleEl.addEventListener('change', () => { syncInstansi(); syncHero(); });
    namaEl.addEventListener('input', syncHero);
    syncInstansi();
    syncHero();

    // Pratinjau foto profil
    fotoEl.addEventListener('change', function () {
        const file = fotoEl.files[0];
        if (!file) {
            previewEl.classList.add('hidden');
            placeholderEl.classList.remove('hidden');
            return;
        }
        previewEl.src = URL.createObjectURL(file);
        previewEl.classList.remove('hidden');
        placeholderEl.classList.add('hidden');
    });
</script>
@endsection
