{{-- resources/views/template/sidebar-superadmin.blade.php --}}
<div
    x-data="{
        kelolaLaporan: {{ request()->routeIs('super_admin.laporan.*') ? 'true' : 'false' }},
        konfigurasi: {{ request()->routeIs('super_admin.kategori.*', 'super_admin.sla-konfigurasi.*', 'super_admin.template-pesan.*', 'super_admin.hadiah.*', 'super_admin.konten-edukasi.*') ? 'true' : 'false' }}
    }"
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
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75a3 3 0 013-3h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM13.5 6.75a3 3 0 013-3h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM3.75 16.5a3 3 0 013-3h1.5a3 3 0 013 3V18a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM13.5 16.5a3 3 0 013-3h1.5a3 3 0 013 3V18a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5z" />
                    </svg>
                    Dashboard
                </a>
            </li>

            {{-- Manajemen Pengguna → super_admin.user.* --}}
            <li>
                <a href="{{ route('super_admin.user.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('super_admin.user.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    Manajemen Pengguna
                </a>
            </li>

            {{-- Manajemen Instansi → super_admin.instansi.* --}}
            <li>
                <a href="{{ route('super_admin.instansi.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('super_admin.instansi.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    Manajemen Instansi
                </a>
            </li>

            {{-- Kelola Laporan (dropdown) → satu controller super_admin.laporan.*, sub-item pakai filter status --}}
            <li>
                <button
                    @click="kelolaLaporan = !kelolaLaporan"
                    class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 font-medium text-cyan-muda transition hover:bg-white/5 hover:text-white"
                >
                    <span class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                        Kelola Laporan
                    </span>
                    <svg class="h-4 w-4 shrink-0 transition-transform" :class="kelolaLaporan ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul x-show="kelolaLaporan" x-collapse class="mt-1 space-y-1 pl-11 text-cyan-muda">
                    <li>
                        <a href="{{ route('super_admin.laporan.index', ['status' => 'menunggu_verifikasi']) }}"
                           class="block rounded-lg px-3 py-2 transition {{ request('status') === 'menunggu_verifikasi' ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Verifikasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super_admin.laporan.index', ['status' => 'disposisi']) }}"
                           class="block rounded-lg px-3 py-2 transition {{ request('status') === 'disposisi' ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Disposisi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super_admin.laporan.index', ['status' => 'diproses']) }}"
                           class="block rounded-lg px-3 py-2 transition {{ request('status') === 'diproses' ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Tindak Lanjut
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super_admin.laporan.index', ['status' => 'selesai']) }}"
                           class="block rounded-lg px-3 py-2 transition {{ request('status') === 'selesai' ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Laporan Selesai
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super_admin.laporan.index', ['status' => 'ditolak']) }}"
                           class="block rounded-lg px-3 py-2 transition {{ request('status') === 'ditolak' ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Laporan Ditolak
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Konfigurasi (dropdown) → kategori, sla-konfigurasi, template-pesan, hadiah, konten-edukasi --}}
            <li>
                <button
                    @click="konfigurasi = !konfigurasi"
                    class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 font-medium text-cyan-muda transition hover:bg-white/5 hover:text-white"
                >
                    <span class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Konfigurasi
                    </span>
                    <svg class="h-4 w-4 shrink-0 transition-transform" :class="konfigurasi ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <ul x-show="konfigurasi" x-collapse class="mt-1 space-y-1 pl-11 text-cyan-muda">
                    <li>
                        <a href="{{ route('super_admin.kategori.index') }}"
                           class="block rounded-lg px-3 py-2 transition {{ request()->routeIs('super_admin.kategori.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Kategori Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super_admin.sla-konfigurasi.index') }}"
                           class="block rounded-lg px-3 py-2 transition {{ request()->routeIs('super_admin.sla-konfigurasi.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Manajemen Tanggapan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super_admin.template-pesan.index') }}"
                           class="block rounded-lg px-3 py-2 transition {{ request()->routeIs('super_admin.template-pesan.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Pesan Konfirmasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super_admin.hadiah.index') }}"
                           class="block rounded-lg px-3 py-2 transition {{ request()->routeIs('super_admin.hadiah.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Tenggat Perbaikan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super_admin.konten-edukasi.index') }}"
                           class="block rounded-lg px-3 py-2 transition {{ request()->routeIs('super_admin.konten-edukasi.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            Visibilitas
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    {{-- Footer sidebar: role badge --}}
    <div class="border-t border-white/10 px-4 py-4">
        <p class="text-xs text-cyan-muda">Masuk sebagai</p>
        <p class="text-sm font-semibold">Super Admin</p>
    </div>
</div>
