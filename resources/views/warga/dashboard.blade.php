<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-cyan-6 leading-tight">
            Profil & Kontribusi Saya
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Kartu Profil --}}
            <div class="bg-white shadow rounded-xl p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-cyan-6 text-white flex items-center justify-center text-2xl font-semibold shrink-0">
                    {{ strtoupper(substr($user->nama ?? '?', 0, 1)) }}
                </div>

                <div class="flex-1">
                    <p class="text-lg font-semibold text-abu-tua">{{ $user->nama }}</p>
                    <p class="text-sm text-abu-tua/70">{{ $user->email }}</p>

                    <span @class([
                        'inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold',
                        'bg-merah/10 text-merah' => $badge['label'] === 'Pahlawan Kota',
                        'bg-oranye/10 text-oranye' => $badge['label'] === 'Warga Aktif',
                        'bg-cyan-4/10 text-cyan-6' => $badge['label'] === 'Warga Pemula',
                    ])>
                        🏅 {{ $badge['label'] }}
                    </span>
                </div>

                <a href="{{ route('profile.edit') }}"
                   class="self-start sm:self-center px-4 py-2 rounded-lg border border-abu-muda text-abu-tua text-sm font-medium hover:bg-abu-muda/20 transition">
                    Edit Profil / Ganti Password
                </a>
                <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="px-4 py-2 rounded-lg border border-merah/30 text-merah text-sm font-medium hover:bg-merah/10 transition">
                    Keluar
                </button>
            </form>
            </div>

            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white shadow rounded-xl p-6">
                    <p class="text-sm text-abu-tua/70">Total Poin Kontribusi</p>
                    <p class="text-3xl font-bold text-cyan-6 mt-1">{{ number_format($totalPoin) }} <span class="text-base font-normal text-abu-tua">Poin</span></p>
                    <a href="{{ route('warga.poin.index') }}"
                       class="inline-block mt-3 text-sm text-cyan-4 hover:text-cyan-6 hover:underline">
                        Lihat Riwayat Lengkap →
                    </a>
                </div>

                <div class="bg-white shadow rounded-xl p-6">
                    <p class="text-sm text-abu-tua/70">Saldo AERA Pay (Simulasi)</p>
                    <p class="text-3xl font-bold text-cyan-6 mt-1">Rp{{ number_format($saldoSaatIni) }}</p>
                    <div class="flex gap-4 mt-3 text-sm">
                        <a href="{{ route('warga.aeraPay.index') }}"
                           class="text-cyan-4 hover:text-cyan-6 hover:underline">
                            Lihat Riwayat
                        </a>
                        <a href="{{ route('warga.aeraPay.create') }}"
                           class="text-cyan-4 hover:text-cyan-6 hover:underline font-medium">
                            Tukar Poin ke Saldo →
                        </a>
                    </div>
                </div>
            </div>

            {{-- Aktivitas Terbaru --}}
            <div class="bg-white shadow rounded-xl p-6">
                <h3 class="font-semibold text-abu-tua mb-4">Aktivitas Terbaru</h3>

                @if ($riwayatPoinTerbaru->isEmpty())
                    <p class="text-sm text-abu-tua/70">Belum ada aktivitas poin.</p>
                @else
                    <ul class="divide-y divide-abu-muda">
                        @foreach ($riwayatPoinTerbaru as $v)
                            <li class="py-3 flex items-center justify-between text-sm">
                                <div>
                                    <p class="text-abu-tua font-medium capitalize">{{ str_replace('_', ' ', $v->jenis_aktivitas) }}</p>
                                    <p class="text-abu-tua/60 text-xs">{{ $v->created_at->format('d M Y') }}</p>
                                </div>
                                <span @class([
                                    'font-semibold',
                                    'text-hijau' => $v->poin > 0,
                                    'text-merah' => $v->poin < 0,
                                ])>
                                    {{ $v->poin > 0 ? '+' . $v->poin : $v->poin }} poin
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>