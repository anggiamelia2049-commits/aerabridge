{{-- resources/views/template/sidebar-petugas.blade.php --}}
<div class="flex h-full w-64 flex-col bg-cyan-6 text-white">

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

            {{-- Laporan → petugas.laporan.* (daftar tugas yang masuk & detail perbaikan) --}}
            <li>
                <a href="{{ route('petugas.laporan.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('petugas.laporan.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                    </svg>
                    Laporan
                </a>
            </li>

            {{-- Penugasan → petugas.penugasan.* (detail tugas, rute, closing report) --}}
            <li>
                <a href="{{ route('petugas.penugasan.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('petugas.penugasan.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                    </svg>
                    Penugasan
                </a>
            </li>

            {{-- Notifikasi → petugas.notifikasi.* --}}
            <li>
                <a href="{{ route('petugas.notifikasi.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('petugas.notifikasi.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    Notifikasi
                </a>
            </li>
        </ul>
    </nav>

    {{-- Footer sidebar: pengaturan profil & keluar --}}
    <div class="border-t border-white/10 px-4 py-4 space-y-2">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-cyan-muda transition hover:text-white">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-sm font-medium">Pengaturan Profil</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
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