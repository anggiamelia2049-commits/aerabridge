@extends('template.layout')

@section('content')
@php
    $field = 'w-full h-11 px-4 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
    $label = 'block text-sm font-semibold text-cyan-6 mb-1.5';
@endphp

<div class="p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-5">
            <p class="text-sm text-gray-600">Tambah akun pengguna baru.</p>
        </div>

        <form action="{{ route('super_admin.user.store') }}" method="POST" enctype="multipart/form-data" class="px-6 pb-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label for="nik" class="{{ $label }}">NIK <span class="text-merah">*</span></label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}" placeholder="Masukkan NIK" class="{{ $field }}" required>
                    @error('nik') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nama" class="{{ $label }}">Nama <span class="text-merah">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama" class="{{ $field }}" required>
                    @error('nama') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="username" class="{{ $label }}">Username <span class="text-merah">*</span></label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" class="{{ $field }}" required>
                    @error('username') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="{{ $label }}">Email <span class="text-merah">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" class="{{ $field }}" required>
                    @error('email') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="{{ $label }}">Password <span class="text-merah">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" class="{{ $field }}" required>
                    @error('password') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="no_hp" class="{{ $label }}">No HP <span class="text-merah">*</span></label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Masukkan nomor HP" class="{{ $field }}" required>
                    @error('no_hp') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="{{ $label }}">Jenis Kelamin <span class="text-merah">*</span></label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="{{ $field }}" required>
                        <option value="">Pilih jenis kelamin</option>
                        @foreach (['Laki-laki', 'Perempuan'] as $jk)
                            <option value="{{ $jk }}" @selected(old('jenis_kelamin') === $jk)>{{ $jk }}</option>
                        @endforeach
                    </select>
                    @error('jenis_kelamin') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="pekerjaan" class="{{ $label }}">Pekerjaan <span class="text-merah">*</span></label>
                    <input type="text" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}" placeholder="Masukkan pekerjaan" class="{{ $field }}" required>
                    @error('pekerjaan') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="{{ $label }}">Tanggal Lahir <span class="text-merah">*</span></label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="{{ $field }}" required>
                    @error('tanggal_lahir') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="penyandang_disabilitas" class="{{ $label }}">Penyandang Disabilitas <span class="text-merah">*</span></label>
                    <select id="penyandang_disabilitas" name="penyandang_disabilitas" class="{{ $field }}" required>
                        <option value="">Pilih</option>
                        @foreach (['Ya', 'Tidak'] as $d)
                            <option value="{{ $d }}" @selected(old('penyandang_disabilitas') === $d)>{{ $d }}</option>
                        @endforeach
                    </select>
                    @error('penyandang_disabilitas') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

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

                {{-- Instansi: muncul di bawah Role & Status, hanya untuk role instansi / petugas --}}
                <div id="boxInstansi" class="hidden md:col-span-2">
                    <label for="instansi_id" class="{{ $label }}">Instansi <span class="text-merah">*</span></label>
                    <select id="instansi_id" name="instansi_id" class="{{ $field }}">
                        <option value="">Pilih instansi</option>
                        @foreach ($instansis as $i)
                            <option value="{{ $i->id }}" @selected(old('instansi_id') == $i->id)>{{ $i->nama_instansi }}</option>
                        @endforeach
                    </select>
                    @error('instansi_id') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="foto" class="{{ $label }}">Foto</label>
                    <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-cyan-3/20 file:text-cyan-6 file:font-semibold">
                    <p class="text-xs text-gray-500 mt-1">JPG atau PNG, maksimal 2 MB.</p>
                    @error('foto') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="alamat" class="{{ $label }}">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat"
                              class="w-full px-4 py-3 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <a href="{{ route('super_admin.user.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Batal</a>
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
</script>
@endsection