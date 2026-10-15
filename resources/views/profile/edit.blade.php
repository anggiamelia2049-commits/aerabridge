<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#45818E]">AERA BRIDGE</p>
                <h2 class="text-2xl font-semibold text-[#0C343D]">
                    Profil Saya
                </h2>
                <p class="mt-1 text-sm text-[#4A4A4A]">
                    Kelola informasi akun dan keamanan akun Anda.
                </p>
            </div>

            @php
                $dashboardRoute = match (auth()->user()->role) {
                    'super_admin' => 'super_admin.dashboard',
                    'warga' => 'warga.dashboard',
                    'petugas' => 'petugas.dashboard',
                    'instansi' => 'instansi.dashboard',
                    default => null,
                };
            @endphp

            @if ($dashboardRoute && Route::has($dashboardRoute))
                <a href="{{ route($dashboardRoute) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#45818E] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#0C343D]">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            @endif
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#F5F8F8] py-8 sm:py-12">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Informasi Profil --}}
            <div class="overflow-hidden rounded-2xl border border-[#D9D9D9] bg-white shadow-sm">
                <div class="h-2 bg-[#45818E]"></div>
                <div class="p-5 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Ubah Password --}}
            <div x-data="{ open: false }"
                 class="overflow-hidden rounded-2xl border border-[#D9D9D9] bg-white shadow-sm">

                <button type="button"
                        @click="open = !open"
                        class="flex w-full items-center justify-between gap-4 p-5 text-left sm:p-8">

                    <div>
                        <h3 class="text-lg font-semibold text-[#0C343D]">
                            Ubah Password
                        </h3>
                        <p class="mt-1 text-sm text-[#4A4A4A]">
                            Perbarui password untuk menjaga keamanan akun.
                        </p>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 shrink-0 text-[#45818E] transition-transform"
                         :class="{ 'rotate-180': open }"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="m19 9-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open"
                     x-cloak
                     class="border-t border-[#D9D9D9] p-5 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Hapus Akun --}}
            <div class="overflow-hidden rounded-2xl border border-[#D9D9D9] bg-white shadow-sm">
                <div class="p-5 sm:p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>