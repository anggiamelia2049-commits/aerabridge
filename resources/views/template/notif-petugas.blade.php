{{-- Lonceng notifikasi khusus petugas.
     Data diambil dari route petugas.notifikasi.ringkas tiap 15 detik.
     Variabel notifOpen berasal dari x-data milik <header> di navbar.blade.php --}}
<div class="relative"
     x-data="{
        jumlah: 0,
        daftar: [],
        siap: false,
        async muat() {
            try {
                const res = await fetch('{{ route('petugas.notifikasi.ringkas') }}', {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                if (res.ok) {
                    const data = await res.json();
                    this.jumlah = data.jumlah;
                    this.daftar = data.daftar;
                    this.siap = true;
                }
            } catch (e) {
            }
        }
     }"
     x-init="muat(); setInterval(() => { if (!document.hidden) muat(); }, 15000)"
     @click.outside="notifOpen = false">

    <button
        type="button"
        @click="notifOpen = !notifOpen"
        class="relative flex h-10 w-10 items-center justify-center rounded-full text-abu-tua transition hover:bg-abu-muda"
        aria-label="Notifikasi"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        <span
            x-show="jumlah > 0"
            x-cloak
            x-text="jumlah > 9 ? '9+' : jumlah"
            class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-merah text-[10px] font-bold text-white"
        ></span>
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
            <template x-for="n in daftar" :key="n.id">
                <li>
                    <a :href="n.url" class="block px-4 py-3 text-sm hover:bg-abu-muda/40">
                        <p class="text-abu-tua" :class="n.dibaca ? 'font-medium' : 'font-bold'" x-text="n.judul"></p>
                        <p class="text-abu-tua/70" x-text="n.isi"></p>
                        <p class="mt-1 text-xs text-abu-tua/50" x-text="n.waktu"></p>
                    </a>
                </li>
            </template>

            <li x-show="!siap" class="px-4 py-6 text-center text-sm text-abu-tua/60">Memuat...</li>
            <li x-show="siap && daftar.length === 0" class="px-4 py-6 text-center text-sm text-abu-tua/60">Belum ada notifikasi.</li>
        </ul>

        <div class="border-t border-abu-muda px-4 py-2 text-center">
            <a href="{{ route('petugas.notifikasi.index') }}" class="text-sm text-cyan-4 hover:underline">Lihat semua notifikasi</a>
        </div>
    </div>
</div>