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

    @stack('styles')
</head>
<body class="font-sans antialiased bg-abu-muda text-abu-tua">

    <div class="flex min-h-screen">

        {{-- ==================== SIDEBAR ====================
             Nilai $role mengikuti middleware role:... di routes/web.php, yaitu:
             'super_admin', 'warga', 'petugas'. Belum ada grup role 'instansi'
             di routes saat ini — sidebar-instansi.blade.php disiapkan untuk
             pengembangan berikutnya dan belum terhubung ke route manapun. --}}
        @php
            $role = $role ?? (auth()->check() ? auth()->user()->role : 'super_admin');
        @endphp

        <aside class="hidden lg:flex lg:flex-shrink-0" x-data="{ sidebarOpen: true }">
            @switch($role)
                @case('warga')
                    @include('template.sidebar-warga')
                    @break

                @case('petugas')
                    @include('template.sidebar-petugas')
                    @break

                @case('instansi')
                    {{-- TODO: belum ada grup route 'instansi.*' di web.php --}}
                    @include('template.sidebar-instansi')
                    @break

                @default
                    @include('template.sidebar-superadmin')
            @endswitch
        </aside>

        {{-- Sidebar versi mobile (slide-over) --}}
        <div
            x-data="{ mobileSidebarOpen: false }"
            x-cloak
            class="lg:hidden"
        >
            <button
                @click="mobileSidebarOpen = true"
                class="fixed top-4 left-4 z-40 inline-flex items-center justify-center rounded-md bg-cyan-6 p-2 text-white shadow-md"
                aria-label="Buka menu"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div
                x-show="mobileSidebarOpen"
                class="fixed inset-0 z-50 bg-black/40"
                @click="mobileSidebarOpen = false"
                x-transition.opacity
            ></div>

            <div
                x-show="mobileSidebarOpen"
                class="fixed inset-y-0 left-0 z-50 w-72 overflow-y-auto"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
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