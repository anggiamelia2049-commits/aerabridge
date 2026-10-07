<x-guest-layout>
    <h2 class="font-extrabold text-2xl text-cyan-6 text-center mb-6">Lacak Progres</h2>

    <form method="GET" action="{{ route('lacak') }}" class="mb-6">
        <label for="kode" class="block font-bold text-sm text-cyan-6 mb-2">Kode Lacak</label>
        <div class="flex gap-2">
            <input id="kode" name="kode" value="{{ request('kode') }}" placeholder="AEB-XXXXXXXX"
                   class="flex-1 h-11 px-4 rounded-xl border border-black bg-transparent text-sm uppercase focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">
            <button class="px-5 h-11 rounded-xl bg-cyan-4 hover:bg-cyan-6 text-white font-bold text-sm transition">Cari</button>
        </div>
    </form>

    @if (request()->filled('kode') && ! $laporan)
        <p class="text-sm text-merah text-center">Kode lacak tidak ditemukan. Periksa lagi penulisannya.</p>
    @endif

    @if ($laporan)
        @php
            $langkah = ['Menunggu', 'Diverifikasi', 'Diproses', 'Selesai'];
            $idx = array_search($laporan->status, $langkah);
        @endphp

        <div class="rounded-xl bg-white border border-abu-muda p-4 mb-5 text-sm">
            <p class="font-bold text-cyan-6">{{ $laporan->judul }}</p>
            <p class="text-gray-600 mt-1">{{ $laporan->kategori->nama_kategori ?? '-' }} · {{ $laporan->instansi->nama_instansi ?? '-' }}</p>
            <p class="text-xs text-gray-500 mt-1">Dikirim {{ $laporan->created_at->translatedFormat('d F Y, H:i') }}</p>
        </div>

        @if ($laporan->status === 'Ditolak')
            <p class="text-center text-sm font-semibold text-merah">Laporan ini ditolak oleh petugas verifikasi.</p>
        @else
            <ol class="space-y-4">
                @foreach ($langkah as $i => $nama)
                    <li class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $i <= $idx ? 'bg-cyan-4 text-white' : 'bg-abu-muda text-abu-tua' }}">{{ $i + 1 }}</span>
                        <span class="text-sm {{ $i <= $idx ? 'font-semibold text-cyan-6' : 'text-gray-400' }}">{{ $nama }}</span>
                    </li>
                @endforeach
            </ol>
        @endif
    @endif

    <a href="{{ route('home') }}" class="block text-center text-sm text-gray-500 mt-6">Kembali ke Beranda</a>
</x-guest-layout>