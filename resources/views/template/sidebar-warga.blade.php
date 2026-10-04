{{-- resources/views/template/sidebar-warga.blade.php --}}
<div class="flex h-full w-64 flex-col bg-cyan-6 text-white">

    {{-- Logo --}}
    <div class="px-4 pt-4 pb-4">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="AERA Bridge" class="h-16 w-auto">
        </div>
        <div class="mt-4 mx-2" style="border-bottom: 1px solid rgba(254, 253, 253, 0.355);"></div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 pb-6">
        <ul class="space-y-1 text-sm">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('warga.dashboard') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('warga.dashboard') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75a3 3 0 013-3h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM13.5 6.75a3 3 0 013-3h1.5a3 3 0 013 3v1.5a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM3.75 16.5a3 3 0 013-3h1.5a3 3 0 013 3V18a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5zM13.5 16.5a3 3 0 013-3h1.5a3 3 0 013 3V18a3 3 0 01-3 3h-1.5a3 3 0 01-3-3v-1.5z" />
                    </svg>
                    Dashboard
                </a>
            </li>

            {{-- Laporan --}}
            <li>
                <a href="{{ route('warga.laporan.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('warga.laporan.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h4m-7 5h10a2.25 2.25 0 002.25-2.25V7.5a2.25 2.25 0 00-.659-1.591l-3.5-3.5A2.25 2.25 0 0012.44 1.75H6A2.25 2.25 0 003.75 4v15A2.25 2.25 0 006 21.25z" />
                    </svg>
                    Laporan
                </a>
            </li>

            {{-- AeraPay --}}
            <li>
                <a href="{{ route('warga.aeraPay.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('warga.aeraPay.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0018.75 4.5H5.25A2.25 2.25 0 003 6.75v10.5A2.25 2.25 0 005.25 19.5z" />
                    </svg>
                    AeraPay
                </a>
            </li>

            {{-- Poin Kontribusi --}}
            <li>
                <a href="{{ route('warga.poin.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('warga.poin.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 21.07a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                    Poin Kontribusi
                </a>
            </li>

            {{-- Edukasi (sesuai proposal 7.4.E) --}}
            <li>
                <a href="{{ route('warga.user-edukasi.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition
                          {{ request()->routeIs('warga.user-edukasi.*') ? 'bg-white/10 text-white' : 'text-cyan-muda hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    Edukasi
                </a>
            </li>

        </ul>
    </nav>

    {{-- Footer sidebar: role badge --}}
    <div class="border-t border-white/10 px-4 py-4">
        <p class="text-xs text-cyan-muda">Masuk sebagai</p>
        <p class="text-sm font-semibold">{{ auth()->user()->nama ?? 'Warga' }}</p>
        <p class="text-xs text-cyan-muda">Warga</p>
    </div>
</div>