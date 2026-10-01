@extends('template.layout')

@section('title', 'Edit User')

@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-[36px] font-bold text-gray-800">Edit User</h2>
        <a href="{{ route('super_admin.user.index') }}"
           class="text-sm text-cyan-700 hover:underline flex items-center gap-1">
            &larr; Kembali ke daftar
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('super_admin.user.update', $dataedituser->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col sm:flex-row gap-6">

                {{-- Foto --}}
                <div class="flex-shrink-0 flex flex-col items-center sm:items-start gap-3">
                    @if($dataedituser->foto)
                        <img src="{{ asset('storage/' . $dataedituser->foto) }}"
                             class="w-36 h-36 rounded-lg object-cover border border-gray-200">
                    @else
                        <div class="w-36 h-36 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-sm text-center px-2">
                            Tidak ada foto
                        </div>
                    @endif
                    <input type="file" name="foto"
                           class="text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                </div>

                {{-- Form fields --}}
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">NIK</label>
                        <input type="text" name="nik" value="{{ old('nik', $dataedituser->nik) }}"
                               class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama', $dataedituser->nama) }}"
                               class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" value="{{ old('username', $dataedituser->username) }}"
                               class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $dataedituser->email) }}"
                               class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password"
                               class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">No HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $dataedituser->no_hp) }}"
                               class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="Laki-laki" {{ $dataedituser->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $dataedituser->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pekerjaan</label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $dataedituser->pekerjaan) }}"
                               class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" rows="2"
                                  class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">{{ old('alamat', $dataedituser->alamat) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $dataedituser->tanggal_lahir) }}"
                               class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Penyandang Disabilitas</label>
                        <select name="penyandang_disabilitas"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="Ya" {{ $dataedituser->penyandang_disabilitas == 'Ya' ? 'selected' : '' }}>Ya</option>
                            <option value="Tidak" {{ $dataedituser->penyandang_disabilitas == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                        <select name="role"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="super_admin" {{ $dataedituser->role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="warga" {{ $dataedituser->role == 'warga' ? 'selected' : '' }}>Warga</option>
                            <option value="instansi" {{ $dataedituser->role == 'instansi' ? 'selected' : '' }}>Instansi</option>
                            <option value="petugas" {{ $dataedituser->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                        <select name="status"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="Aktif" {{ $dataedituser->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ $dataedituser->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- Tombol --}}
            <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('super_admin.user.index') }}"
                   class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    Kembali
                </a>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-cyan-700 text-white text-sm font-medium hover:bg-cyan-800 transition">
                    Simpan
                </button>
            </div>
        </div>
    </form>

</div>
@endsection
