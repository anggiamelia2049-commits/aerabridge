@extends('template.layout')

@section('title', 'Detail User')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow overflow-hidden">
        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">Detail akun pengguna.</p>
            <a href="{{ route('super_admin.user.edit', $user->id) }}"
               class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Edit</a>
        </div>

        <div class="px-6 pb-6 flex flex-col sm:flex-row gap-6 text-sm text-gray-700">

            <div class="shrink-0">
                @if ($user->foto)
                    <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->nama }}"
                         class="w-36 h-36 rounded-xl object-cover border border-abu-muda">
                @else
                    <div class="w-36 h-36 rounded-xl bg-gray-100 border border-abu-muda flex items-center justify-center text-xs text-gray-400 text-center px-2">Tidak ada foto</div>
                @endif
            </div>

            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div><p class="text-xs text-gray-500">NIK</p><p class="font-semibold">{{ $user->nik }}</p></div>
                <div><p class="text-xs text-gray-500">Nama</p><p class="font-semibold text-cyan-6">{{ $user->nama }}</p></div>
                <div><p class="text-xs text-gray-500">Username</p><p class="font-semibold">{{ $user->username }}</p></div>
                <div><p class="text-xs text-gray-500">Email</p><p class="font-semibold">{{ $user->email }}</p></div>
                <div><p class="text-xs text-gray-500">No HP</p><p class="font-semibold">{{ $user->no_hp }}</p></div>
                <div><p class="text-xs text-gray-500">Jenis Kelamin</p><p class="font-semibold">{{ $user->jenis_kelamin }}</p></div>
                <div><p class="text-xs text-gray-500">Pekerjaan</p><p class="font-semibold">{{ $user->pekerjaan }}</p></div>
                <div><p class="text-xs text-gray-500">Tanggal Lahir</p><p class="font-semibold">{{ $user->tanggal_lahir?->translatedFormat('d F Y') }}</p></div>
                <div><p class="text-xs text-gray-500">Penyandang Disabilitas</p><p class="font-semibold">{{ $user->penyandang_disabilitas }}</p></div>

                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Role</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-cyan-3/20 text-cyan-6">
                        {{ str_replace('_', ' ', ucfirst($user->role)) }}
                    </span>
                </div>

                @if (in_array($user->role, ['instansi', 'petugas'], true))
                    <div>
                        <p class="text-xs text-gray-500">Instansi</p>
                        @if ($user->instansi)
                            <p class="font-semibold">{{ $user->instansi->nama_instansi }}</p>
                        @else
                            <p class="font-semibold text-merah">Belum terhubung</p>
                        @endif
                    </div>
                @endif

                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Status</p>
                    <span @class([
                        'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                        'bg-hijau/10 text-hijau' => $user->status === 'Aktif',
                        'bg-abu-muda text-abu-tua' => $user->status !== 'Aktif',
                    ])>{{ $user->status }}</span>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-xs text-gray-500 mb-1">Alamat</p>
                    <div class="rounded-lg border border-abu-muda bg-gray-50 p-4 whitespace-pre-line">{{ $user->alamat ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="px-6 pb-6">
            <a href="{{ route('super_admin.user.index') }}" class="text-sm text-gray-500 hover:underline">← Kembali</a>
        </div>
    </div>
</div>
@endsection