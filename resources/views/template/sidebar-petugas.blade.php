{{-- resources/views/template/sidebar-petugas.blade.php --}}
@php
    $menu = 'flex w-full items-center gap-3 rounded-lg px-3 py-2.5 font-semibold text-white no-underline transition hover:bg-white/10 hover:text-white';
    $menuAktif = 'bg-gradient-to-r from-white/25 via-white/10 to-transparent';
    $sub = 'block rounded-lg py-2 pl-11 pr-3 font-semibold text-white no-underline transition hover:bg-white/10 hover:text-white';
    $subAktif = 'bg-white/10';

    // Saat membuka detail tugas, pakai status tugas itu untuk menentukan menu aktif
    $tugasDibuka = isset($penugasan) && $penugasan instanceof \App\Models\Penugasan ? $penugasan : null;
    $selesaiDibuka = $tugasDibuka && $tugasDibuka->status === 'selesai';

    $aktifDashboard = request()->routeIs('petugas.dashboard');
    $aktifLaporan = request()->routeIs('petugas.laporan.*');
    $aktifPeta = request()->routeIs('petugas.penugasan.*')
        && request('filter') !== 'selesai'
        && ! $selesaiDibuka;
    $aktifRiwayat = $selesaiDibuka
        || (request()->routeIs('petugas.penugasan.index') && request('filter') === 'selesai');
    $aktifNotif = request()->routeIs('petugas.notifikasi.*');

    // Nilai kunci harus sama dengan isi kolom notifikasis.tipe di database
    $menuNotif = [
        'penugasan_baru' => 'Penugasan Baru',
        'prioritas_diubah' => 'Prioritas Diubah',
        'laporan_diverifikasi' => 'Laporan Diverifikasi',
        'pengingat_tugas' => 'Pengingat Tugas',
    ];
@endphp

<div class="flex h-full w-64 flex-col bg-cyan-6 font-sans text-white">

    {{-- Logo --}}
    <div class="px-4 pt-4 pb-4">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="AERA Bridge" class="h-16 w-auto">
        </div>
        <div class="mx-2 mt-4 border-b border-white"></div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 pb-6" aria-label="Menu petugas">
        <ul class="space-y-1 text-sm">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('petugas.dashboard') }}" class="{{ $menu }} {{ $aktifDashboard ? $menuAktif : '' }}"
                   @if ($aktifDashboard) aria-current="page" @endif>
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75a3 3 0 013-3h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM13.5 6.75a3 3 0 013-3h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM3.75 16.5a3 3 0 013-3h1.5a3 3 0 013 3V18a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM13.5 16.5a3 3 0 013-3h1.5a3 3 0 013 3V18a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5z" />
                    </svg>
                    Dashboard
                </a>
            </li>

            {{-- Laporan (dropdown) --}}
            <li>
                <details class="group" @if ($aktifLaporan) open @endif>
                    <summary class="{{ $menu }} cursor-pointer list-none [&::-webkit-details-marker]:hidden {{ $aktifLaporan ? $menuAktif : '' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                        <span class="flex-1">Laporan</span>
                        <svg class="h-4 w-4 shrink-0 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </summary>
                    <div class="mt-1 space-y-0.5">
                        <a href="{{ route('petugas.laporan.index', ['tab' => 'masuk']) }}"
                           class="{{ $sub }} {{ request()->routeIs('petugas.laporan.index') && request('tab', 'masuk') === 'masuk' ? $subAktif : '' }}">Laporan Masuk</a>
                        <a href="{{ route('petugas.laporan.index', ['tab' => 'selesai']) }}"
                           class="{{ $sub }} {{ request()->routeIs('petugas.laporan.index') && request('tab') === 'selesai' ? $subAktif : '' }}">Laporan Penyelesaian</a>
                    </div>
                </details>
            </li>

            {{-- Peta penugasan --}}
            <li>
                <a href="{{ route('petugas.penugasan.index') }}" class="{{ $menu }} {{ $aktifPeta ? $menuAktif : '' }}"
                   @if ($aktifPeta) aria-current="page" @endif>
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    Peta penugasan
                </a>
            </li>

            {{-- Riwayat Tugas --}}
            <li>
                <a href="{{ route('petugas.penugasan.index', ['filter' => 'selesai']) }}" class="{{ $menu }} {{ $aktifRiwayat ? $menuAktif : '' }}"
                   @if ($aktifRiwayat) aria-current="page" @endif>
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat Tugas
                </a>
            </li>

            {{-- Notifikasi Laporan (dropdown) --}}
            <li>
                <details class="group" @if ($aktifNotif) open @endif>
                    <summary class="{{ $menu }} cursor-pointer list-none [&::-webkit-details-marker]:hidden {{ $aktifNotif ? $menuAktif : '' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span class="flex-1">Notifikasi Laporan</span>
                        <svg class="h-4 w-4 shrink-0 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </summary>
                    <div class="mt-1 space-y-0.5">
                        @foreach ($menuNotif as $tipe => $teks)
                            <a href="{{ route('petugas.notifikasi.index', ['tipe' => $tipe]) }}"
                               class="{{ $sub }} {{ request()->routeIs('petugas.notifikasi.index') && request('tipe') === $tipe ? $subAktif : '' }}">{{ $teks }}</a>
                        @endforeach
                    </div>
                </details>
            </li>

        </ul>
    </nav>
</div>