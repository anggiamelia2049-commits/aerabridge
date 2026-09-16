{{-- resources/views/template/sidebar-instansi.blade.php --}}
{{-- CATATAN: routes/web.php saat ini belum punya grup Route::name('instansi.')
     dengan middleware role:instansi — hanya ada super_admin, warga, dan petugas.
     File ini disiapkan untuk pengembangan berikutnya; semua route() di bawah
     akan error sampai grup route 'instansi.*' ditambahkan di web.php. --}}
<div
    x-data="{ laporan: {{ request()->routeIs('instansi.laporan.*', 'instansi.verifikasi.*') ? 'true' : 'false' }} }"
    class="flex h-full w-64 flex-col bg-cyan-6 text-white"
>
    {{-- Logo --}}
    <div class="flex items-center gap-2 px-6 py-6">
        <img src="{{ asset('images/logo-aerabridge.svg') }}" alt="AERA Bridge" class="h-8 w-8">
        <div class="leading-tight">
            <p class="text-sm font-bold tracking-wide">AERA</p>
            <p class="text-xs font-medium text-cyan-muda">BRIDGE</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 pb-6">
        <ul class="space-y-1 text-sm">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('instansi.dashboard') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('instansi.dashboard') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75a3 3 0 013-3h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM13.5 6.75a3 3 0 013-3h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM3.75 16.5a3 3 0 013-3h1.5a3 3 0 013 3V18a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM13.5 16.5a3 3 0 013-3h1.5a3 3 0 013 3V18a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5z" />
                    </svg>
                    Dashboard
                </a>
            </li>

            {{-- Laporan (dropdown) --}}
            <li>
                <button
                    @click="laporan = !laporan"
                    class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 font-medium text-cyan-muda transition hover:bg-white/5 hover:text-white"
                >
                    <span class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-1.519-4.5H12m3 4.5h.008v.008H15v-.008zM3.75 6.75a3 3 0 013-3h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5z" />
                        </svg>
                        Laporan
                    </span>
                    <svg class="h-4 w-4 shrink-0 transition-transform" :class="laporan ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul x-show="laporan" x-collapse class="mt-1 space-y-1 pl-11 text-cyan-muda">
                    <li>
                        <a href="{{ route('instansi.laporan.index') }}"
                           class="block rounded-lg px-3 py-2 transition {{ request()->routeIs('instansi.laporan.index') ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Semua Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('instansi.verifikasi.index') }}"
                           class="block rounded-lg px-3 py-2 transition {{ request()->routeIs('instansi.verifikasi.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Verifikasi Laporan
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Penugasan --}}
            <li>
                <a href="{{ route('instansi.penugasan.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('instansi.penugasan.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    Penugasan
                </a>
            </li>

            {{-- Pekerja --}}
            <li>
                <a href="{{ route('instansi.pekerja.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('instansi.pekerja.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pekerja
                </a>
            </li>

            {{-- Data Kategori --}}
            <li>
                <a href="{{ route('instansi.kategori.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('instansi.kategori.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                    </svg>
                    Data Kategori
                </a>
            </li>

            {{-- Statistik --}}
            <li>
                <a href="{{ route('instansi.statistik') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('instansi.statistik') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    Statistik
                </a>
            </li>
        </ul>
    </nav>

    {{-- Footer sidebar: profil singkat, sesuai pengaturan profil di mockup --}}
    <div class="border-t border-white/10 px-4 py-4">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-cyan-muda transition hover:text-white">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-sm font-medium">Pengaturan Profil</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="flex items-center gap-2 text-sm font-medium text-cyan-muda transition hover:text-white">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0110.5 3h6a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0116.5 21h-6a2.25 2.25 0 01-2.25-2.25V15m-3 0l-3-3m0 0l3-3m-3 3H15" />
                </svg>
                Keluar
            </button>
        </form>
    </div>
</div>