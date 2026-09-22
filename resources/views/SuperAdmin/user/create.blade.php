@extends('template.layout')

@section('content')

@if($errors->any())
    <div class="mb-5 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
        <p class="font-medium mb-2">Terdapat kesalahan pada data:</p>

        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200">

    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-[#0C343D]">
            Tambah Pengguna
        </h2>
    </div>

    <form action="{{ route('super_admin.user.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="p-6">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- NIK --}}
            <div>
                <label for="nik" class="block mb-2 text-sm font-medium text-gray-700">
                    NIK
                </label>

                <input
                    type="text"
                    id="nik"
                    name="nik"
                    value="{{ old('nik') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                    placeholder="Masukkan NIK"
                >
            </div>

            {{-- Nama --}}
            <div>
                <label for="nama" class="block mb-2 text-sm font-medium text-gray-700">
                    Nama
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                    placeholder="Masukkan nama"
                >
            </div>

            {{-- Username --}}
            <div>
                <label for="username" class="block mb-2 text-sm font-medium text-gray-700">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                    placeholder="Masukkan username"
                >
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                    placeholder="Masukkan email"
                >
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                    placeholder="Masukkan password"
                >
            </div>

            {{-- No HP --}}
            <div>
                <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-700">
                    No HP
                </label>

                <input
                    type="text"
                    id="no_hp"
                    name="no_hp"
                    value="{{ old('no_hp') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                    placeholder="Masukkan nomor HP"
                >
            </div>

            {{-- Jenis Kelamin --}}
            <div>
                <label for="jenis_kelamin" class="block mb-2 text-sm font-medium text-gray-700">
                    Jenis Kelamin
                </label>

                <select
                    id="jenis_kelamin"
                    name="jenis_kelamin"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                >
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                        Laki-laki
                    </option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                        Perempuan
                    </option>
                </select>
            </div>

            {{-- Pekerjaan --}}
            <div>
                <label for="pekerjaan" class="block mb-2 text-sm font-medium text-gray-700">
                    Pekerjaan
                </label>

                <input
                    type="text"
                    id="pekerjaan"
                    name="pekerjaan"
                    value="{{ old('pekerjaan') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                    placeholder="Masukkan pekerjaan"
                >
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label for="tanggal_lahir" class="block mb-2 text-sm font-medium text-gray-700">
                    Tanggal Lahir
                </label>

                <input
                    type="date"
                    id="tanggal_lahir"
                    name="tanggal_lahir"
                    value="{{ old('tanggal_lahir') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                >
            </div>

            {{-- Disabilitas --}}
            <div>
                <label for="penyandang_disabilitas" class="block mb-2 text-sm font-medium text-gray-700">
                    Penyandang Disabilitas
                </label>

                <select
                    id="penyandang_disabilitas"
                    name="penyandang_disabilitas"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                >
                    <option value="">-- Pilih --</option>
                    <option value="Ya" {{ old('penyandang_disabilitas') == 'Ya' ? 'selected' : '' }}>
                        Ya
                    </option>
                    <option value="Tidak" {{ old('penyandang_disabilitas') == 'Tidak' ? 'selected' : '' }}>
                        Tidak
                    </option>
                </select>
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block mb-2 text-sm font-medium text-gray-700">
                    Role
                </label>

                <select
                    id="role"
                    name="role"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                >
                    <option value="">-- Pilih Role --</option>

                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>
                        Super Admin
                    </option>

                    <option value="warga" {{ old('role') == 'warga' ? 'selected' : '' }}>
                        Warga
                    </option>

                    <option value="instansi" {{ old('role') == 'instansi' ? 'selected' : '' }}>
                        Instansi
                    </option>

                    <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>
                        Petugas
                    </option>
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block mb-2 text-sm font-medium text-gray-700">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                >
                    <option value="">-- Pilih Status --</option>

                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>
            </div>

            {{-- Foto --}}
            <div class="md:col-span-2">
                <label for="foto" class="block mb-2 text-sm font-medium text-gray-700">
                    Foto
                </label>

                <input
                    type="file"
                    id="foto"
                    name="foto"
                    class="w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-[#0C343D] file:px-4 file:py-2.5 file:text-white hover:file:bg-[#45818E]"
                >
            </div>

            {{-- Alamat --}}
            <div class="md:col-span-2">
                <label for="alamat" class="block mb-2 text-sm font-medium text-gray-700">
                    Alamat
                </label>

                <textarea
                    id="alamat"
                    name="alamat"
                    rows="4"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-[#45818E] focus:ring-[#45818E]"
                    placeholder="Masukkan alamat"
                >{{ old('alamat') }}</textarea>
            </div>

        </div>

        {{-- Tombol --}}
        <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-200">

            <a
                href="{{ route('super_admin.user.index') }}"
                class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-100"
            >
                Kembali
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 rounded-lg bg-[#45818E] text-white text-sm font-medium hover:bg-[#0C343D]"
            >
                Simpan
            </button>

        </div>

    </form>

</div>

@endsection