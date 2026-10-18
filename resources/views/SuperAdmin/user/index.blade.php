@extends('template.layout')

@section('title', 'Manajemen Pengguna')

@section('content')
    @php
        // Key '' (bukan null) karena key null pada array PHP otomatis menjadi string kosong.
        $filters = [
            '' => 'Semua',
            'super_admin' => 'Super Admin',
            'warga' => 'Warga',
            'instansi' => 'Instansi',
            'petugas' => 'Petugas',
        ];
        $roleAktif = $role ?? '';
    @endphp

    {{-- Hilangkan garis bawah link (kemungkinan berasal dari CSS bawaan lain di layout) --}}
    <style>
        .user-page a,
        .user-page a:hover,
        .user-page a:focus {
            text-decoration: none;
        }

        /* Link aksi: garis bawah hanya muncul saat di-hover */
        .user-page .aksi-link:hover {
            text-decoration: underline;
        }
    </style>

    <div class="p-6 user-page">

        {{-- ===== Toolbar: filter role (kiri) + pencarian & tombol (kanan) ===== --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="inline-flex flex-wrap gap-1 bg-white rounded-xl p-1 shadow-sm">
                @foreach ($filters as $nilai => $teks)
                    @php
                        $aktif = $roleAktif === (string) $nilai;
                        $jumlah = $nilai === '' ? $jumlahRole->sum() : $jumlahRole[$nilai] ?? 0;
                    @endphp
                    <a href="{{ $nilai !== '' ? route('super_admin.user.index', ['role' => $nilai]) : route('super_admin.user.index') }}"
                        @class([
                            'inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition',
                            'bg-cyan-6 text-white hover:text-white' => $aktif,
                            'text-cyan-6/70 hover:bg-gray-100 hover:text-cyan-6' => !$aktif,
                        ])>
                        {{ $teks }}
                        <span @class([
                            'text-xs',
                            'text-white/70' => $aktif,
                            'text-cyan-6/50' => !$aktif,
                        ])>{{ $jumlah }}</span>
                    </a>
                @endforeach
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="relative w-full sm:w-72">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7" />
                            <path d="M21 21l-4.3-4.3" />
                        </svg>
                    </span>
                    <input type="text" id="cariUser" placeholder="Cari nama atau email..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">
                </div>
                <a href="{{ route('super_admin.user.create') }}"
                    class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white hover:text-white text-sm font-bold transition">
                    + Tambah User
                </a>
            </div>
        </div>

        {{-- ===== Tabel ===== --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-xs font-bold text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Pengguna</th>
                            <th class="px-4 py-3">No HP</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Instansi</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="w-40 px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50" data-cari="{{ strtolower($user->nama . ' ' . $user->email) }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($user->foto)
                                            <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->nama }}"
                                                class="w-11 h-11 shrink-0 object-cover rounded-full border border-abu-muda">
                                        @else
                                            <div
                                                class="w-11 h-11 shrink-0 rounded-full bg-cyan-3 flex items-center justify-center text-white font-bold">
                                                {{ mb_strtoupper(mb_substr($user->nama, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="font-semibold text-cyan-6 truncate">{{ $user->nama }}</div>
                                            <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->no_hp }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-cyan-3/20 text-cyan-6">
                                        {{ str_replace('_', ' ', ucfirst($user->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 max-w-[240px]">
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
                                <td class="px-6 py-4">
                                    <span @class([
                                        'inline-flex items-center gap-1.5 text-xs font-semibold',
                                        'text-hijau' => $user->status === 'Aktif',
                                        'text-abu-tua' => $user->status !== 'Aktif',
                                    ])>
                                        <span @class([
                                            'w-1.5 h-1.5 rounded-full',
                                            'bg-hijau' => $user->status === 'Aktif',
                                            'bg-abu-tua' => $user->status !== 'Aktif',
                                        ])></span>
                                        {{ $user->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2 text-sm font-normal">
                                        <a href="{{ route('super_admin.user.show', $user->id) }}"
                                            class="aksi-link text-cyan-4 hover:text-cyan-6 transition">Detail</a>
                                        <span class="w-px h-4 bg-cyan-3"></span>
                                        <a href="{{ route('super_admin.user.edit', $user->id) }}"
                                            class="aksi-link text-oranye hover:text-oranye transition">Edit</a>
                                        <span class="w-px h-4 bg-cyan-3"></span>
                                        <form method="POST" action="{{ route('super_admin.user.destroy', $user->id) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="aksi-link text-merah hover:text-merah transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">Belum ada data user.</td>
                            </tr>
                        @endforelse

                        {{-- Muncul saat pencarian tidak menemukan hasil --}}
                        <tr id="cariKosong" class="hidden">
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Tidak ada pengguna yang cocok.
                            </td>
                        </tr>
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

    <script>
        // Pencarian cepat untuk data yang sedang tampil di halaman ini
        const cariEl = document.getElementById('cariUser');
        const barisEl = document.querySelectorAll('tr[data-cari]');
        const kosongEl = document.getElementById('cariKosong');

        cariEl.addEventListener('input', function() {
            const kata = cariEl.value.trim().toLowerCase();
            let ada = 0;
            barisEl.forEach(tr => {
                const cocok = tr.dataset.cari.includes(kata);
                tr.classList.toggle('hidden', !cocok);
                if (cocok) ada++;
            });
            kosongEl.classList.toggle('hidden', ada > 0 || barisEl.length === 0);
        });
    </script>
@endsection
