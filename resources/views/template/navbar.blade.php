{{-- resources/views/template/navbar.blade.php --}}
<header
    x-data="{ profileOpen: false, notifOpen: false }"
    class="sticky top-0 z-30 flex items-center justify-between gap-4 border-b border-abu-muda bg-white px-4 py-3 sm:px-6 lg:px-8"
>
    {{-- Kiri: hamburger + search bar --}}
    <div class="flex flex-1 items-center gap-6">

        {{-- Tombol garis tiga (toggle sidebar, state dari layout.blade.php) --}}
        <button
            type="button"
            @click="toggleSidebar()"
            class="group flex h-[42px] w-[42px] shrink-0 items-center justify-center self-center rounded-lg text-abu-tua transition hover:bg-abu-muda focus:outline-none focus:ring-2 focus:ring-cyan-4/30"
            aria-label="Buka atau tutup sidebar"
        >
            <svg class="block h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="4" y1="6" x2="20" y2="6" />
                <line x1="4" y1="12" x2="20" y2="12"
                      class="origin-left transition-transform duration-300 [transform-box:fill-box] group-hover:scale-x-100"
                      :class="sidebarOpen ? 'scale-x-100' : 'scale-x-[0.65]'" />
                <line x1="4" y1="18" x2="20" y2="18" />
            </svg>
        </button>

        {{-- Search bar --}}
        <label for="global-search" class="sr-only">Cari laporan, lokasi, atau kata kunci</label>
        <div class="relative w-full max-w-md">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-abu-tua/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </span>
            <input
                id="global-search"
                type="search"
                name="q"
                placeholder="Cari laporan, lokasi, atau kata kunci..."
                class="w-full rounded-lg border border-abu-muda bg-white py-2.5 pl-10 pr-4 text-sm text-abu-tua placeholder:text-abu-tua/50 focus:border-cyan-4 focus:outline-none focus:ring-2 focus:ring-cyan-4/30"
            >
        </div>
    </div>

    {{-- Kanan: notifikasi + profil --}}
    <div class="flex items-center gap-4">

        {{-- Notifikasi --}}
        <div class="relative">
            <button
                @click="notifOpen = !notifOpen"
                @click.outside="notifOpen = false"
                class="relative flex h-10 w-10 items-center justify-center rounded-full text-abu-tua transition hover:bg-abu-muda"
                aria-label="Notifikasi"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                @if(($jumlahNotifikasiBelumDibaca ?? 0) > 0)
                    <span class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-merah text-[10px] font-bold text-white">
                        {{ $jumlahNotifikasiBelumDibaca > 9 ? '9+' : $jumlahNotifikasiBelumDibaca }}
                    </span>
                @endif
            </button>

            <div
                x-show="notifOpen"
                x-cloak
                x-transition
                class="absolute right-0 mt-2 w-80 rounded-xl border border-abu-muda bg-white shadow-lg"
            >
                <div class="border-b border-abu-muda px-4 py-3">
                    <p class="text-sm font-semibold text-abu-tua">Notifikasi</p>
                </div>
                <ul class="max-h-80 divide-y divide-abu-muda overflow-y-auto">
                    @forelse(($notifikasiTerbaru ?? []) as $notif)
                        <li class="px-4 py-3 text-sm hover:bg-abu-muda/40">
                            <p class="font-medium text-abu-tua">{{ $notif->judul }}</p>
                            <p class="text-abu-tua/70">{{ $notif->pesan }}</p>
                            <p class="mt-1 text-xs text-abu-tua/50">{{ $notif->created_at->diffForHumans() }}</p>
                        </li>
                    @empty
                        <li class="px-4 py-6 text-center text-sm text-abu-tua/60">Belum ada notifikasi.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Profil --}}
        <div class="relative">
            <button
                @click="profileOpen = !profileOpen"
                @click.outside="profileOpen = false"
                class="flex items-center gap-2"
            >
                @php
                    // Ganti 'foto_profil' sesuai nama kolom foto di tabel users kamu
                    $fotoProfil = auth()->check() ? (auth()->user()->foto_profil ?? null) : null;
                @endphp

                @if ($fotoProfil)
                    <img src="{{ asset('storage/' . $fotoProfil) }}"
                         alt="Foto {{ auth()->user()->name }}"
                         class="h-10 w-10 rounded-full object-cover">
                @else
                    {{-- Avatar default: ikon user dalam lingkaran --}}
                    <svg class="h-10 w-10 shrink-0 text-abu-tua" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                @endif
                <span class="hidden text-left sm:block">
                    <span class="block text-sm font-semibold text-abu-tua">{{ auth()->user()->name ?? 'Pengguna' }}</span>
                    <span class="block text-xs text-abu-tua/60">{{ Str::title(str_replace('_', ' ', $role ?? 'super_admin')) }}</span>
                </span>
                <svg class="h-4 w-4 text-abu-tua/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div
                x-show="profileOpen"
                x-cloak
                x-transition
                class="absolute right-0 mt-2 w-52 rounded-xl border border-abu-muda bg-white py-1 shadow-lg"
            >
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-abu-tua hover:bg-abu-muda/50">
                    Pengaturan Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-merah hover:bg-abu-muda/50">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
