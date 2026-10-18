@extends('template.layout')

@section('content')
@php
    $filters = [
        null          => 'Semua',
        'super_admin' => 'Super Admin',
        'warga'       => 'Warga',
        'instansi'    => 'Instansi',
        'petugas'     => 'Petugas',
    ];
@endphp

<div class="p-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="flex items-center justify-between gap-4 px-6 py-5">
            <p class="text-sm text-gray-600">Kelola akun pengguna: warga, petugas, instansi, dan super admin.</p>
            <a href="{{ route('super_admin.user.create') }}"
               class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                + Tambah User
            </a>
        </div>

        {{-- Filter role --}}
        <div class="flex flex-wrap items-center gap-2 px-6 pb-5">
            @foreach ($filters as $nilai => $teks)
                @php
                    $aktif = $role === $nilai;
                    $jumlah = $nilai === null ? $jumlahRole->sum() : ($jumlahRole[$nilai] ?? 0);
                @endphp
                <a href="{{ $nilai ? route('super_admin.user.index', ['role' => $nilai]) : route('super_admin.user.index') }}"
                   @class([
                       'inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold border transition',
                       'bg-cyan-4 text-white border-cyan-4' => $aktif,
                       'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' => ! $aktif,
                   ])>
                    {{ $teks }}
                    <span @class([
                        'px-2 py-0.5 rounded-full text-xs',
                        'bg-white/20 text-white' => $aktif,
                        'bg-gray-100 text-gray-600' => ! $aktif,
                    ])>{{ $jumlah }}</span>
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-cyan-6 text-white">
                    <tr>
                        <th class="px-4 py-3.5 font-semibold">No</th>
                        <th class="px-4 py-3.5 font-semibold">Foto</th>
                        <th class="px-4 py-3.5 font-semibold">Nama</th>
                        <th class="px-4 py-3.5 font-semibold">Email</th>
                        <th class="px-4 py-3.5 font-semibold">No HP</th>
                        <th class="px-4 py-3.5 font-semibold">Role</th>
                        <th class="px-4 py-3.5 font-semibold">Instansi</th>
                        <th class="px-4 py-3.5 font-semibold">Status</th>
                        <th class="px-4 py-3.5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3.5">{{ $users->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3.5">
                                @if ($user->foto)
                                    <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->nama }}"
                                         class="w-12 h-12 object-cover rounded-lg border border-abu-muda">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 border border-abu-muda flex items-center justify-center text-[10px] text-gray-400">Tidak ada</div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 font-semibold">{{ $user->nama }}</td>
                            <td class="px-4 py-3.5">{{ $user->email }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">{{ $user->no_hp }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-cyan-3/20 text-cyan-6">
                                    {{ str_replace('_', ' ', ucfirst($user->role)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                @if (in_array($user->role, ['instansi', 'petugas'], true))
                                    @if ($user->instansi)
                                        {{ $user->instansi->nama_instansi }}
                                    @else
                                        <span class="text-merah font-semibold">Belum terhubung</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <span @class([
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                    'bg-hijau/10 text-hijau' => $user->status === 'Aktif',
                                    'bg-abu-muda text-abu-tua' => $user->status !== 'Aktif',
                                ])>{{ $user->status }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-4 text-sm font-semibold">
                                    <a href="{{ route('super_admin.user.show', $user->id) }}" class="text-cyan-4 hover:underline">Detail</a>
                                    <a href="{{ route('super_admin.user.edit', $user->id) }}" class="text-oranye hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('super_admin.user.destroy', $user->id) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-merah hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-400">Belum ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination: 10 user per halaman --}}
        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection