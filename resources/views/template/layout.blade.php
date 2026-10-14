<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AERA Bridge')</title>

    {{-- Font utama --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS + Alpine (lewat Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Mencegah elemen x-cloak berkedip sebelum Alpine siap --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>

@php
    $role = $role ?? (auth()->check() ? auth()->user()->role : 'super_admin');

    $sidebarView = in_array($role, ['warga', 'petugas', 'instansi'], true)
        ? "template.sidebar-{$role}"
        : 'template.sidebar-superadmin';
@endphp

<body class="font-sans antialiased bg-abu-muda text-abu-tua"
    x-data="{
        sidebarOpen: true,
        mobileSidebarOpen: false,
        init() {
            try {
                this.sidebarOpen = localStorage.getItem('sidebarOpen') !== 'false';
            } catch (e) {}

            this.$watch('sidebarOpen', v => {
                try { localStorage.setItem('sidebarOpen', v); } catch (e) {}
            });
        },
        toggleSidebar() {
            if (window.matchMedia('(min-width: 1024px)').matches) {
                this.sidebarOpen = !this.sidebarOpen;
            } else {
                this.mobileSidebarOpen = !this.mobileSidebarOpen;
            }
        }
    }"
    :class="{ 'overflow-hidden': mobileSidebarOpen }"
    @keydown.escape.window="mobileSidebarOpen = false"
    @resize.window="if (window.innerWidth >= 1024) mobileSidebarOpen = false">

    <div class="flex min-h-screen">

        {{-- SIDEBAR (satu saja, dipakai untuk desktop & mobile) --}}
        <aside
            class="lg:sticky lg:top-0 lg:h-screen lg:shrink-0 lg:self-start lg:overflow-hidden lg:transition-[width,visibility] lg:duration-300 lg:ease-in-out"
            :class="sidebarOpen ? 'lg:w-64' : 'lg:w-0 lg:invisible'">

            <div
                class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full overflow-y-auto shadow-xl transition-transform duration-300 ease-in-out lg:static lg:h-full lg:translate-x-0 lg:shadow-none"
                :class="mobileSidebarOpen ? '!translate-x-0' : ''">
                @include($sidebarView)
            </div>
        </aside>

        {{-- Overlay (mobile saja) --}}
        <div
            x-cloak
            x-show="mobileSidebarOpen"
            x-transition.opacity.duration.300ms
            @click="mobileSidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/40 lg:hidden"
            aria-hidden="true">
        </div>

        {{-- MAIN CONTENT --}}
        <div class="flex min-w-0 flex-1 flex-col">

            @include('template.navbar')

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">

                @if (session('success'))
                    <div class="mb-4 rounded-lg border border-hijau/30 bg-hijau/10 px-4 py-3 text-sm font-medium text-hijau">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-lg border border-merah/30 bg-merah/10 px-4 py-3 text-sm font-medium text-merah">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </main>

            <footer class="bg-white px-4 py-2 text-xs text-gray-500 sm:px-5">
                <div class="flex flex-col items-center justify-between gap-1 sm:flex-row">
                    <p>© {{ date('Y') }} AERA Bridge — Smart Reporting Masalah Perkotaan.</p>
                    <p>
                        Dikembangkan oleh
                        <span class="font-semibold text-gray-700">The Code Waves</span>
                        untuk Kota Depok.
                    </p>
                </div>
            </footer>

        </div>
    </div>

    @stack('scripts')
</body>

</html>