@extends('template.layout')

@section('title', 'Manajemen Instansi')

@section('content')
    @php
        $total = $instansis->count();
        $aktif = $instansis->where('status', 'Aktif')->count();
        $nonaktif = $instansis->where('status', 'Nonaktif')->count();

        $filters = [
            'semua' => ['Semua', $total],
            'Aktif' => ['Aktif', $aktif],
            'Nonaktif' => ['Nonaktif', $nonaktif],
        ];
    @endphp

    <div class="p-6">

        {{-- ===== Ringkasan ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow px-5 py-4">
                <p class="m-0 text-[28px] font-bold leading-tight text-cyan-6">{{ $total }}</p>
                <p class="m-0 text-[13px] text-gray-500">Total instansi</p>
            </div>
            <div class="bg-white rounded-2xl shadow px-5 py-4">
                <p class="m-0 text-[28px] font-bold leading-tight text-hijau">{{ $aktif }}</p>
                <p class="m-0 text-[13px] text-gray-500">Aktif</p>
            </div>
            <div class="bg-white rounded-2xl shadow px-5 py-4">
                <p class="m-0 text-[28px] font-bold leading-tight text-abu-tua">{{ $nonaktif }}</p>
                <p class="m-0 text-[13px] text-gray-500">Nonaktif</p>
            </div>
        </div>

        {{-- ===== Toolbar: filter status (kiri) + pencarian & tombol (kanan) ===== --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="inline-flex flex-wrap gap-1 bg-white rounded-xl p-1 shadow-sm">
                @foreach ($filters as $nilai => [$teks, $jumlah])
                    <button type="button" data-filter="{{ $nilai }}" @class([
                        'inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition',
                        'bg-cyan-6 text-white' => $loop->first,
                        'text-cyan-6/70 hover:bg-gray-100' => !$loop->first,
                    ])>
                        {{ $teks }}
                        <span class="text-xs opacity-70">{{ $jumlah }}</span>
                    </button>
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
                    <input type="text" id="cariInstansi" placeholder="Cari nama instansi..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-abu-muda bg-white text-sm text-gray-900 placeholder:text-gray-400 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">
                </div>
                <a href="{{ route('super_admin.instansi.create') }}"
                    class="shrink-0 px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white hover:text-white text-sm font-bold no-underline hover:no-underline transition">
                    + Tambah Instansi
                </a>
            </div>
        </div>

        {{-- ===== Tabel ===== --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-700">
                        <tr>
                            <th class="px-6 py-3">Instansi</th>
                            <th class="px-6 py-3">No. Telp</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="w-56 px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($instansis as $item)
                            <tr class="hover:bg-gray-50" data-status="{{ $item->status }}"
                                data-cari="{{ strtolower($item->nama_instansi . ' ' . $item->email . ' ' . $item->no_telp) }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($item->logo)
                                            <img src="{{ asset('storage/' . $item->logo) }}"
                                                alt="{{ $item->nama_instansi }}"
                                                class="w-11 h-11 shrink-0 object-contain rounded-lg border border-abu-muda bg-white">
                                        @else
                                            <div
                                                class="w-11 h-11 shrink-0 rounded-lg bg-cyan-3 flex items-center justify-center text-white font-bold">
                                                {{ mb_strtoupper(mb_substr($item->nama_instansi, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0 max-w-[320px] font-semibold text-cyan-6">
                                            {{ $item->nama_instansi }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->no_telp }}</td>
                                <td class="px-6 py-4">{{ $item->email ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                        'bg-hijau/10 text-hijau' => $item->status === 'Aktif',
                                        'bg-abu-muda text-abu-tua' => $item->status !== 'Aktif',
                                    ])>{{ $item->status }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-sm font-normal">
                                        <a href="{{ route('super_admin.instansi.show', $item->id) }}"
                                            class="text-cyan-4 no-underline hover:no-underline hover:opacity-80 transition">Detail</a>
                                        <span class="w-px h-4 bg-cyan-3"></span>
                                        <a href="{{ route('super_admin.instansi.edit', $item->id) }}"
                                            class="text-oranye no-underline hover:no-underline hover:opacity-80 transition">Edit</a>
                                        <span class="w-px h-4 bg-cyan-3"></span>
                                        <form method="POST"
                                            action="{{ route('super_admin.instansi.destroy', $item->id) }}"
                                            onsubmit="return confirm('Hapus instansi ini? Semua laporan yang terhubung ke instansi ini juga akan ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-merah hover:opacity-80 transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400">Belum ada instansi.</td>
                            </tr>
                        @endforelse

                        {{-- Muncul saat filter / pencarian tidak menemukan hasil --}}
                        <tr id="cariKosong" class="hidden">
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">Tidak ada instansi yang cocok.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Filter status + pencarian untuk data yang sedang tampil di halaman ini
        const filterBtns = document.querySelectorAll('[data-filter]');
        const rows = document.querySelectorAll('tr[data-status]');
        const kosongEl = document.getElementById('cariKosong');
        const cariEl = document.getElementById('cariInstansi');
        let filterAktif = 'semua';

        function terapkan() {
            const kata = cariEl.value.trim().toLowerCase();
            let ada = 0;
            rows.forEach(tr => {
                const cocokStatus = filterAktif === 'semua' || tr.dataset.status === filterAktif;
                const cocok = cocokStatus && tr.dataset.cari.includes(kata);
                tr.classList.toggle('hidden', !cocok);
                if (cocok) ada++;
            });
            kosongEl.classList.toggle('hidden', ada > 0 || rows.length === 0);
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterAktif = btn.dataset.filter;
                filterBtns.forEach(b => {
                    const aktif = b === btn;
                    b.classList.toggle('bg-cyan-6', aktif);
                    b.classList.toggle('text-white', aktif);
                    b.classList.toggle('text-cyan-6/70', !aktif);
                    b.classList.toggle('hover:bg-gray-100', !aktif);
                });
                terapkan();
            });
        });

        cariEl.addEventListener('input', terapkan);
    </script>
@endsection
