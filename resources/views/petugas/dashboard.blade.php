@extends('template.layout')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
@endpush

@section('content')
@php
    $filter = $filter ?? 'semua';

    $kartu = [
        [
            'label' => 'Tugas Aktif',
            'nilai' => $jumlahAktif,
            'lingkaran' => '#BFDDD4',
            'badge' => null,
            'ikon' => '<svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="#3E8E7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>',
        ],
        [
            'label' => 'Prioritas Tinggi',
            'nilai' => $jumlahPrioritas,
            'lingkaran' => '#F7AEAC',
            'badge' => 'Tinggi',
            'ikon' => '<svg class="h-7 w-7" viewBox="0 0 24 24" fill="none"><path d="M12 3 2.5 20h19L12 3z" fill="#E53935" stroke="#E53935" stroke-width="2" stroke-linejoin="round"/><path d="M12 9v5" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="17" r="1.1" fill="#FFFFFF"/></svg>',
        ],
        [
            'label' => 'Selesai',
            'nilai' => $jumlahSelesai,
            'lingkaran' => '#CFE8CB',
            'badge' => null,
            'ikon' => '<svg class="h-7 w-7" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" fill="#4CAF50"/><path d="m7.8 12.5 2.8 2.8 5.8-5.8" stroke="#FFFFFF" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        ],
        [
            'label' => 'Tugas Harian',
            'nilai' => $jumlahHarian,
            'lingkaran' => '#BFDDD4',
            'badge' => null,
            'ikon' => '<svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="#3E8E7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>',
        ],
    ];

    $tab = [
        'semua' => 'Semua Tugas',
        'aktif' => 'Aktif',
        'prioritas' => 'Prioritas',
        'selesai' => 'Selesai',
    ];
@endphp

<div>
    {{-- Sapaan --}}
    <h1 class="text-h1 leading-tight">
        Selamat Datang, {{ auth()->user()->nama ?? 'Petugas' }} 👋
    </h1>
    <p class="text-subtitle mb-6 mt-1">Pantau penugasan dan laporkan hasil perbaikan infrastruktur.</p>

    {{-- Empat kartu angka --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($kartu as $k)
            <div class="flex items-center gap-4 rounded-2xl bg-white px-5 py-4 shadow-sm">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full"
                     style="background-color: {{ $k['lingkaran'] }}">
                    {!! $k['ikon'] !!}
                </div>
                <div>
                    <p class="text-sm text-[#4A4A4A]">{{ $k['label'] }}</p>
                    <p class="flex items-center gap-2 text-lg font-bold text-black">
                        ({{ $k['nilai'] }})
                        @if ($k['badge'])
                            <span class="rounded bg-[#E53935] px-2 py-0.5 text-xs font-medium text-white">{{ $k['badge'] }}</span>
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[3fr_2fr]">

        {{-- Kiri: peta tugas --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="mb-4 flex flex-wrap items-center gap-3 text-[15px]">
                @foreach ($tab as $kunci => $label)
                    <button type="button"
                            data-tab="{{ $kunci }}"
                            data-aktif="{{ $kunci === $filter ? 'true' : 'false' }}"
                            class="font-semibold text-gray-400 transition hover:text-gray-600 data-[aktif=true]:font-bold data-[aktif=true]:text-black">
                        {{ $label }}
                    </button>
                    @if (! $loop->last)
                        <span class="h-5 w-px bg-gray-300"></span>
                    @endif
                @endforeach
            </div>

            <div class="relative isolate">
                <div id="peta-tugas" class="h-[320px] w-full rounded-xl sm:h-[460px]"></div>
                <div id="peta-kosong" class="pointer-events-none absolute inset-0 z-[500] hidden items-center justify-center">
                    <span class="rounded-lg bg-white/90 px-4 py-2 text-sm text-[#4A4A4A] shadow">Tidak ada tugas di tab ini.</span>
                </div>
            </div>
        </div>

        {{-- Kanan: tugas baru + detail --}}
        <div class="flex flex-col gap-6">

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
                <h3 class="px-5 pb-2 pt-5 text-lg font-bold text-black">Tugas Baru</h3>

                <div class="max-h-[216px] overflow-y-auto">
                    @forelse ($tugasBaru as $t)
                        <button type="button"
                                data-tugas="{{ $t['id'] }}"
                                data-aktif="{{ ($terpilih['id'] ?? null) === $t['id'] ? 'true' : 'false' }}"
                                class="flex w-full items-center gap-4 border-l-4 border-t border-l-transparent border-t-gray-200 px-5 py-3 text-left transition hover:bg-gray-50 data-[aktif=true]:border-l-[#45818E] data-[aktif=true]:bg-[#CCDADC]">
                            <span class="h-5 w-5 shrink-0 rounded-full" style="background-color: {{ $t['warna'] }}"></span>
                            <span class="min-w-0">
                                <span class="block truncate font-bold text-black">{{ $t['judul'] }}</span>
                                <span class="block truncate text-sm text-black">{{ $t['alamat'] }}</span>
                            </span>
                        </button>
                    @empty
                        <p class="border-t border-gray-200 px-5 py-6 text-center text-sm text-[#4A4A4A]">Belum ada tugas baru.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <h3 class="mb-3 text-lg font-bold text-black">Detail Tugas Terpilih</h3>

                <p id="detail-kosong" class="hidden py-4 text-center text-sm text-[#4A4A4A]">Belum ada tugas yang dipilih.</p>

                <div id="detail-isi" class="hidden">
                    <img id="detail-foto" src="" alt="" class="hidden h-[100px] w-[190px] rounded-lg object-cover">
                    <div id="detail-tanpa-foto" class="hidden">
                        <div class="flex h-[100px] w-[190px] items-center justify-center rounded-lg bg-gray-200 text-xs text-gray-500">Tidak ada foto</div>
                    </div>

                    <p class="mt-2 text-sm text-black">Kategori: <span id="detail-kategori"></span></p>
                    <p class="mt-1 flex items-center gap-2 text-sm text-black">
                        Prioritas:
                        <span id="detail-prioritas" class="rounded px-2.5 py-0.5 text-xs font-semibold"></span>
                    </p>

                    <div class="mt-4 flex flex-wrap items-center justify-center gap-4">
                        <a id="detail-navigasi" href="#" target="_blank" rel="noopener"
                           class="rounded border border-[#2A7F8E] px-5 py-1.5 text-sm font-semibold text-[#2A7F8E] shadow transition hover:bg-[#2A7F8E] hover:text-white">
                            Mulai Navigasi
                        </a>
                        <a id="detail-rute" href="#" target="_blank" rel="noopener"
                           class="rounded border border-[#2A7F8E] px-5 py-1.5 text-sm font-semibold text-[#2A7F8E] shadow transition hover:bg-[#2A7F8E] hover:text-white">
                            Lihat Rute
                        </a>
                    </div>

                    <p class="mt-3 text-center">
                        <a id="detail-buka" href="#" class="text-sm text-[#45818E] hover:underline">Buka detail penugasan</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
(function () {
    const tugas = @json($dataTugas);
    const idAwal = @json($terpilih['id'] ?? null);

    const elPeta = document.getElementById('peta-tugas');
    const adaPeta = (typeof L !== 'undefined') && elPeta;

    let peta = null;
    let kelompok = null;
    let penanda = {};
    let tabAktif = @json($filter);

    // ---------- Peta ----------
    if (adaPeta) {
        peta = L.map(elPeta).setView([-6.2, 106.816666], 11);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(peta);

        kelompok = L.featureGroup().addTo(peta);

        // Ukuran peta ikut menyesuaikan saat sidebar dibuka/ditutup
        new ResizeObserver(function () { peta.invalidateSize(); }).observe(elPeta);
    }

    // Ikon pin berwarna sesuai prioritas
    function ikon(warna) {
        const svg = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="42" viewBox="0 0 30 40">'
            + '<path d="M15 0C6.7 0 0 6.7 0 15c0 11.3 15 25 15 25s15-13.7 15-25C30 6.7 23.3 0 15 0z" fill="' + warna + '" stroke="rgba(0,0,0,0.25)"/>'
            + '<circle cx="15" cy="15" r="5.5" fill="#FFFFFF"/></svg>';

        return L.divIcon({
            className: 'pin-tugas',
            html: svg,
            iconSize: [32, 42],
            iconAnchor: [16, 42]
        });
    }

    // Apakah tugas cocok dengan tab yang sedang aktif
    function cocok(t, tab) {
        if (tab === 'aktif') { return t.grup === 'aktif'; }
        if (tab === 'prioritas') { return t.grup === 'aktif' && t.kritis; }
        if (tab === 'selesai') { return t.grup === 'selesai'; }
        return true;
    }

    function cariTugas(id) {
        for (let i = 0; i < tugas.length; i++) {
            if (tugas[i].id === id) { return tugas[i]; }
        }
        return null;
    }

    function gambarPenanda() {
        if (!peta) { return; }

        kelompok.clearLayers();
        penanda = {};

        const terlihat = tugas.filter(function (t) {
            return cocok(t, tabAktif) && t.lat !== null && t.lng !== null;
        });

        terlihat.forEach(function (t) {
            const m = L.marker([t.lat, t.lng], { icon: ikon(t.warna) });

            // Tooltip dibuat lewat elemen (textContent) supaya judul laporan aman dari HTML
            const label = document.createElement('span');
            label.textContent = t.judul;
            m.bindTooltip(label, { direction: 'top', offset: [0, -36] });

            m.on('click', function () { pilih(t.id, false); });
            m.addTo(kelompok);
            penanda[t.id] = m;
        });

        document.getElementById('peta-kosong').classList.toggle('hidden', terlihat.length > 0);
        document.getElementById('peta-kosong').classList.toggle('flex', terlihat.length === 0);

        if (terlihat.length > 0) {
            peta.fitBounds(kelompok.getBounds().pad(0.3), { maxZoom: 16 });
        }
    }

    function setTab(tab) {
        tabAktif = tab;
        document.querySelectorAll('[data-tab]').forEach(function (b) {
            b.dataset.aktif = (b.dataset.tab === tab) ? 'true' : 'false';
        });
        gambarPenanda();
    }

    // ---------- Panel detail ----------
    function aturTombol(id, url) {
        const a = document.getElementById(id);
        if (url) {
            a.setAttribute('href', url);
            a.classList.remove('opacity-40', 'pointer-events-none');
        } else {
            a.setAttribute('href', '#');
            a.classList.add('opacity-40', 'pointer-events-none');
        }
    }

    function tampilkanDetail(t) {
        const kosong = (t === null);

        document.getElementById('detail-kosong').classList.toggle('hidden', !kosong);
        document.getElementById('detail-isi').classList.toggle('hidden', kosong);

        if (kosong) { return; }

        const foto = document.getElementById('detail-foto');
        const tanpaFoto = document.getElementById('detail-tanpa-foto');

        if (t.foto) {
            foto.setAttribute('src', t.foto);
            foto.setAttribute('alt', 'Foto ' + t.judul);
            foto.classList.remove('hidden');
            tanpaFoto.classList.add('hidden');
        } else {
            foto.removeAttribute('src');
            foto.classList.add('hidden');
            tanpaFoto.classList.remove('hidden');
        }

        document.getElementById('detail-kategori').textContent = t.kategori;

        const badge = document.getElementById('detail-prioritas');
        badge.textContent = t.prioritas;
        badge.style.backgroundColor = t.warna;
        badge.style.color = t.teks;

        aturTombol('detail-navigasi', t.navigasi);
        aturTombol('detail-rute', t.rute);
        document.getElementById('detail-buka').setAttribute('href', t.detail);
    }

    // Pilih satu tugas: sorot di daftar, tampilkan detail, geser peta
    function pilih(id, geser) {
        const t = cariTugas(id);
        if (!t) { return; }

        if (!cocok(t, tabAktif)) { setTab('semua'); }

        document.querySelectorAll('[data-tugas]').forEach(function (el) {
            el.dataset.aktif = (Number(el.dataset.tugas) === id) ? 'true' : 'false';
        });

        tampilkanDetail(t);

        if (geser && peta && t.lat !== null && t.lng !== null) {
            peta.flyTo([t.lat, t.lng], Math.max(peta.getZoom(), 15), { duration: 0.6 });
        }
    }

    // ---------- Pasang aksi ----------
    document.querySelectorAll('[data-tab]').forEach(function (b) {
        b.addEventListener('click', function () { setTab(b.dataset.tab); });
    });

    document.querySelectorAll('[data-tugas]').forEach(function (el) {
        el.addEventListener('click', function () { pilih(Number(el.dataset.tugas), true); });
    });

    setTab(tabAktif);

    if (idAwal !== null) {
        pilih(idAwal, false);
    } else {
        tampilkanDetail(null);
    }
})();
</script>
@endpush
