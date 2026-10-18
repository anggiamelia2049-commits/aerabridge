@extends('template.layout')

@section('title', 'Tambah User')

@section('content')
    @php
        $field =
            'w-full h-11 px-4 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
        $label = 'block text-sm font-semibold text-cyan-6 mb-1.5';
    @endphp

    <div class="p-6">
        <div class="max-w-5xl mx-auto">

            <form action="{{ route('super_admin.user.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- ===== Foto Profil ===== --}}
                <div class="bg-white rounded-2xl shadow p-6">
                    <h2 class="text-h2 mb-4">Foto Profil</h2>

                    <div class="w-28 h-28 mx-auto mb-4">
                        <div id="placeholderFoto"
                            class="w-28 h-28 rounded-full bg-cyan-3/20 text-cyan-6 flex items-center justify-center">
                            <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                            </svg>
                        </div>
                        <img id="previewFoto" src="" alt="Pratinjau foto"
                            class="hidden w-28 h-28 rounded-full object-cover border border-abu-muda">
                    </div>

                    <label for="foto" class="sr-only">Foto</label>
                    <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png"
                        class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-cyan-3/20 file:text-cyan-6 file:font-semibold">
                    <p class="text-xs text-gray-500 mt-2">JPG atau PNG, maksimal 2 MB.</p>
                    @error('foto')
                        <p class="text-xs text-merah mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ===== Role & Status ===== --}}
                <div class="bg-white rounded-2xl shadow p-6">
                    <h2 class="text-h2 mb-4">Role &amp; Status</h2>

                    <div class="space-y-5">
                        <div>
                            <label for="role" class="{{ $label }}">Role <span class="text-merah">*</span></label>
                            <select id="role" name="role" class="{{ $field }}" required>
                                <option value="">Pilih role</option>
                                @foreach (['super_admin' => 'Super Admin', 'warga' => 'Warga', 'instansi' => 'Instansi', 'petugas' => 'Petugas'] as $nilai => $teks)
                                    <option value="{{ $nilai }}" @selected(old('role') === $nilai)>{{ $teks }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-xs text-merah mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="{{ $label }}">Status <span
                                    class="text-merah">*</span></label>
                            <select id="status" name="status" class="{{ $field }}" required>
                                @foreach (['Aktif', 'Nonaktif'] as $s)
                                    <option value="{{ $s }}" @selected(old('status', 'Aktif') === $s)>{{ $s }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-xs text-merah mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Instansi: hanya untuk role instansi / petugas --}}
                        <div id="boxInstansi" class="hidden">
                            <label for="instansi_id" class="{{ $label }}">Instansi <span
                                    class="text-merah">*</span></label>
                            <select id="instansi_id" name="instansi_id" class="{{ $field }}">
                                <option value="">Pilih instansi</option>
                                @foreach ($instansis as $i)
                                    <option value="{{ $i->id }}" @selected(old('instansi_id') == $i->id)>
                                        {{ $i->nama_instansi }}</option>
                                @endforeach
                            </select>
                            @error('instansi_id')
                                <p class="text-xs text-merah mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ===== Data Pengguna ===== --}}
                <div class="bg-white rounded-2xl shadow p-6">
                    <h2 class="text-h2 mb-5">Data Pengguna</h2>

                    <div class="space-y-5">
                        {{-- Identitas --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="nik" class="{{ $label }}">NIK <span
                                        class="text-merah">*</span></label>
                                <input type="text" id="nik" name="nik" value="{{ old('nik') }}"
                                    placeholder="Masukkan NIK" class="{{ $field }}" required>
                                @error('nik')
                                    <p class="text-xs text-merah mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nama" class="{{ $label }}">Nama <span
                                        class="text-merah">*</span></label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                                    placeholder="Masukkan nama" class="{{ $field }}" required>
                                @error('nama')
                                    <p class="text-xs text-merah mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jenis_kelamin" class="{{ $label }}">Jenis Kelamin <span
                                        class="text-merah">*</span></label>
                                <select id="jenis_kelamin" name="jenis_kelamin" class="{{ $field }}" required>
                                    <option value="">Pilih jenis kelamin</option>
                                    @foreach (['Laki-laki', 'Perempuan'] as $jk)
                                        <option value="{{ $jk }}" @selected(old('jenis_kelamin') === $jk)>
                                            {{ $jk }}</option>
                                    @endforeach
                                </select>
                                @error('jenis_kelamin')
                                    <p class="text-xs text-merah mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_lahir" class="{{ $label }}">Tanggal Lahir <span
                                        class="text-merah">*</span></label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir') }}" class="{{ $field }}" required>
                                @error('tanggal_lahir')
                                    <p class="text-xs text-merah mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pekerjaan" class="{{ $label }}">Pekerjaan <span
                                        class="text-merah">*</span></label>
                                <input type="text" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}"
                                    placeholder="Masukkan pekerjaan" class="{{ $field }}" required>
                                @error('pekerjaan')
                                    <p class="text-xs text-merah mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="penyandang_disabilitas" class="{{ $label }}">Penyandang Disabilitas
                                    <span class="text-merah">*</span></label>
                                <select id="penyandang_disabilitas" name="penyandang_disabilitas"
                                    class="{{ $field }}" required>
                                    <option value="">Pilih</option>
                                    @foreach (['Ya', 'Tidak'] as $d)
                                        <option value="{{ $d }}" @selected(old('penyandang_disabilitas') === $d)>
                                            {{ $d }}</option>
                                    @endforeach
                                </select>
                                @error('penyandang_disabilitas')
                                    <p class="text-xs text-merah mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Akun --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="username" class="{{ $label }}">Username <span
                                        class="text-merah">*</span></label>
                                <input type="text" id="username" name="username" value="{{ old('username') }}"
                                    placeholder="Masukkan username" class="{{ $field }}" required>
                                @error('username')
                                    <p class="text-xs text-merah mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="{{ $label }}">Email <span
                                        class="text-merah">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Masukkan email" class="{{ $field }}" required>
                                @error('email')
                                    <p class="text-xs text-merah mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="{{ $label }}">Password <span
                                        class="text-merah">*</span></label>
                                <input type="password" id="password" name="password" placeholder="Masukkan password"
                                    class="{{ $field }}" required>
                                @error('password')
                                    <p class="text-xs text-merah mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="no_hp" class="{{ $label }}">No HP <span
                                        class="text-merah">*</span></label>
                                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                                    placeholder="Masukkan nomor HP" class="{{ $field }}" required>
                                @error('no_hp')
                                    <p class="text-xs text-merah mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <label for="alamat" class="{{ $label }}">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat"
                                class="w-full px-4 py-3 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-xs text-merah mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ===== Tombol aksi (di bawah card Data Pengguna) ===== --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('super_admin.user.index') }}" <a href="{{ route('super_admin.user.index') }}"
                        class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-semibold text-gray-700 !no-underline hover:!no-underline hover:bg-gray-50 transition">Batal</a>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Simpan</button>
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

        // Pratinjau foto profil
        fotoEl.addEventListener('change', function() {
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
