<x-guest-layout>
    <div class="text-center">
        <h2 class="text-2xl font-extrabold text-cyan-6 mb-2">Laporan Terkirim</h2>
        <p class="text-sm text-gray-600 mb-6">
            Simpan kode lacak ini. Kode hanya ditampilkan sekali dan menjadi satu-satunya cara memantau laporanmu.
        </p>

        <div class="rounded-xl border-2 border-dashed border-cyan-4 bg-white py-4 mb-4">
            <span id="kode" class="text-2xl font-bold tracking-widest text-cyan-6">{{ $kode }}</span>
        </div>

        <div class="flex flex-col gap-2">
            <button type="button" id="btnSalin" class="h-11 rounded-xl bg-cyan-4 hover:bg-cyan-6 text-white font-bold text-sm transition">Salin Kode</button>
            <a href="{{ route('lacak', ['kode' => $kode]) }}" class="h-11 leading-[2.75rem] rounded-xl border border-cyan-4 text-cyan-4 font-bold text-sm">Lacak Sekarang</a>
            <a href="{{ route('home') }}" class="text-sm text-gray-500 mt-2">Kembali ke Beranda</a>
        </div>
    </div>

    <script>
        document.getElementById('btnSalin').addEventListener('click', function () {
            navigator.clipboard.writeText(document.getElementById('kode').textContent.trim());
            this.textContent = 'Tersalin!';
        });
    </script>
</x-guest-layout>