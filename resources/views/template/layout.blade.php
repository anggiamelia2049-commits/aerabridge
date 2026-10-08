<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AERA Bridge')</title>

    {{-- Tailwind CSS (compiled lewat Vite, lihat tailwind.config.js untuk warna kustom) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font utama --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Mencegah elemen x-cloak berkedip sebelum Alpine siap --}}
    <style>[x-cloak] { display: none !important; }</style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-abu-muda text-abu-tua">

    <div class="flex min-h-screen"
         x-data="{
            sidebarOpen: true,
            mobileSidebarOpen: false,
            init() {
                try { this.sidebarOpen = localStorage.getItem('sidebarOpen') !== 'false'; } catch (e) {}
                this.$watch('sidebarOpen', v => { try { localStorage.setItem('sidebarOpen', v); } catch (e) {} });
            },
            toggleSidebar() {
                if (window.matchMedia('(min-width: 1024px)').matches) {
                    this.sidebarOpen = !this.sidebarOpen;
                } else {
                    this.mobileSidebarOpen = !this.mobileSidebarOpen;
                }
            }
         }"
         @keydown.escape.window="mobileSidebarOpen = false"
         @resize.window="if (window.innerWidth >= 1024) mobileSidebarOpen = false">

        {{-- ==================== SIDEBAR ====================
             Nilai $role mengikuti middleware role:... di routes/web.php:
             'super_admin', 'warga', 'petugas', 'instansi'. --}}
        @php
            $role = $role ?? (auth()->check() ? auth()->user()->role : 'super_admin');
        @endphp

        {{-- ===== SIDEBAR DESKTOP: lebar 16rem <-> 0, smooth, tetap diam saat halaman di-scroll ===== --}}
        <aside
            class="hidden overflow-hidden transition-[width,visibility] duration-300 ease-in-out lg:sticky lg:top-0 lg:block lg:h-screen lg:shrink-0 lg:self-start"
            :class="sidebarOpen ? 'lg:w-64' : 'lg:w-0 lg:invisible'"
            :aria-hidden="(!sidebarOpen).toString()"
        >
            {{-- wrapper w-64 supaya isi sidebar tidak ikut "gepeng" saat aside menyempit --}}
            <div class="h-full w-64">
                @switch($role)
                    @case('warga')
                        @include('template.sidebar-warga')
                        @break

                    @case('petugas')
                        @include('template.sidebar-petugas')
                        @break

                    @case('instansi')
                        @include('template.sidebar-instansi')
                        @break

                    @default
                        @include('template.sidebar-superadmin')
                @endswitch
            </div>
        </aside>

        {{-- ===== SIDEBAR MOBILE (slide-over) ===== --}}
        <div x-cloak class="lg:hidden">
            {{-- Overlay --}}
            <div
                x-show="mobileSidebarOpen"
                x-transition.opacity.duration.300ms
                @click="mobileSidebarOpen = false"
                class="fixed inset-0 z-40 bg-black/40"
            ></div>

            {{-- Panel sidebar --}}
            <div
                x-show="mobileSidebarOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="fixed inset-y-0 left-0 z-50 w-64 overflow-y-auto shadow-xl"
            >
                @switch($role)
                    @case('warga')
                        @include('template.sidebar-warga')
                        @break

                    @case('petugas')
                        @include('template.sidebar-petugas')
                        @break

                    @case('instansi')
                        @include('template.sidebar-instansi')
                        @break

                    @default
                        @include('template.sidebar-superadmin')
                @endswitch
            </div>
        </div>

        {{-- ==================== MAIN CONTENT ==================== --}}
        <div class="flex flex-1 flex-col min-w-0">

            @include('template.navbar')

            <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8">
                {{-- Flash messages --}}
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

            @include('template.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
