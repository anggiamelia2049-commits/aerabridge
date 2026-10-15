@extends('template.layout')

@section('title', 'Peta Kerusakan')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

{{-- ==================== JUDUL + MODE PETA ==================== --}}
<div class="mb-5 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Peta Kerusakan</h1>
        <p class="mt-1 text-base text-gray-900">Pantau persebaran kerusakan infrastruktur di Kota Depok berdasarkan kategori, status, dan prioritas AI</p>
    </div>

    <div class="inline-flex rounded-xl bg-white p-1 shadow" role="group" aria-label="Mode tampilan peta">
        <button type="button" data-mode="titik" class="mode-btn rounded-lg px-4 py-2 text-sm font-medium">Titik Laporan</button>
        <button type="button" data-mode="heatmap" class="mode-btn rounded-lg px-4 py-2 text-sm font-medium">Heatmap</button>
    </div>
</div>

{{-- ==================== FILTER ==================== --}}
<div class="mb-5 grid grid-cols-1 gap-3 rounded-2xl bg-white p-4 shadow sm:grid-cols-2 lg:grid-cols-[1fr_1fr_1fr_auto]">
    <label class="text-xs text-abu-tua">Kategori
        <select id="filter-kategori" class="mt-1 w-full rounded-lg border border-gray-300 py-2 pl-3 pr-10 text-sm text-cyan-6">
            <option value="">Semua kategori</option>
        </select>
    </label>

    <label class="text-xs text-abu-tua">Status
        <select id="filter-status" class="mt-1 w-full rounded-lg border border-gray-300 py-2 pl-3 pr-10 text-sm text-cyan-6">
            <option value="">Semua status</option>
            <option value="menunggu">Menunggu Verifikasi</option>
            <option value="diverifikasi">Diverifikasi</option>
            <option value="diproses">Diproses</option>
            <option value="selesai">Selesai</option>
        </select>
    </label>

    <label class="text-xs text-abu-tua">Prioritas AI
        <select id="filter-prioritas" class="mt-1 w-full rounded-lg border border-gray-300 py-2 pl-3 pr-10 text-sm text-cyan-6">
            <option value="">Semua prioritas</option>
            <option value="kritis">Kritis</option>
            <option value="sedang">Sedang</option>
            <option value="rendah">Rendah</option>
        </select>
    </label>

</div>

{{-- ==================== RINGKASAN ==================== --}}
<div class="mb-5 grid grid-cols-2 gap-4 lg:grid-cols-4">
    <div class="rounded-2xl bg-white p-4 shadow">
        <p class="text-xs text-abu-tua">Laporan ditampilkan</p>
        <p id="cnt-total" class="text-xl font-bold text-cyan-6">0</p>
    </div>
    <div class="rounded-2xl bg-white p-4 shadow">
        <p class="flex items-center gap-2 text-xs text-abu-tua"><span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>Kritis</p>
        <p id="cnt-kritis" class="text-xl font-bold text-cyan-6">0</p>
    </div>
    <div class="rounded-2xl bg-white p-4 shadow">
        <p class="flex items-center gap-2 text-xs text-abu-tua"><span class="h-2.5 w-2.5 rounded-full bg-orange-400"></span>Sedang</p>
        <p id="cnt-sedang" class="text-xl font-bold text-cyan-6">0</p>
    </div>
    <div class="rounded-2xl bg-white p-4 shadow">
        <p class="flex items-center gap-2 text-xs text-abu-tua"><span class="h-2.5 w-2.5 rounded-full bg-yellow-400"></span>Rendah</p>
        <p id="cnt-rendah" class="text-xl font-bold text-cyan-6">0</p>
    </div>
</div>

{{-- ==================== PETA + DAFTAR PRIORITAS ==================== --}}
<div class="grid grid-cols-1 gap-5 lg:grid-cols-[2.4fr_1fr]">

    <div class="rounded-2xl bg-white p-5 shadow">
        <div class="relative isolate">
            <div id="peta-kerusakan" class="h-[520px] w-full rounded-xl"></div>

            {{-- Legenda mode titik --}}
            <div id="legend-titik" class="absolute bottom-3 left-3 z-[1000] rounded-lg bg-white p-3 text-xs text-cyan-6 shadow">
                <p class="mb-2 font-semibold">Prioritas</p>
                <p class="mb-1 flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-red-500"></span>Kritis</p>
                <p class="mb-1 flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-orange-400"></span>Sedang</p>
                <p class="mb-1 flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-yellow-400"></span>Rendah</p>
                <p class="text-[10px] text-abu-tua">Titik pudar = selesai</p>
            </div>

            {{-- Legenda mode heatmap --}}
            <div id="legend-heat" class="absolute bottom-3 left-3 z-[1000] hidden rounded-lg bg-white p-3 text-xs text-cyan-6 shadow">
                <p class="mb-2 font-semibold">Kepadatan kerusakan</p>
                <div class="h-2 w-32 rounded" style="background: linear-gradient(to right, #FFC107, #F97316, #E53935)"></div>
                <div class="mt-1 flex justify-between text-[10px] text-abu-tua"><span>Rendah</span><span>Padat</span></div>
            </div>

            {{-- Pesan bila tidak ada data --}}
            <div id="peta-kosong" class="pointer-events-none absolute inset-0 z-[900] hidden items-center justify-center rounded-xl bg-white/70 text-sm text-abu-tua">
                Belum ada laporan yang sesuai dengan filter.
            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-5 shadow">
        <h2 class="mb-1 text-sm font-bold text-cyan-6">Prioritas Tinggi Belum Selesai</h2>
        <p class="mb-3 text-xs text-abu-tua">Klik laporan untuk menuju lokasinya di peta.</p>
        <div id="list-kritis" class="max-h-[460px] space-y-2 overflow-y-auto"></div>
    </div>
</div>

{{-- ==================== SCRIPT ==================== --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ---------- Konstanta ----------
    const WARNA  = { kritis: '#E53935', sedang: '#F97316', rendah: '#FFC107' };
    const LABEL  = { kritis: 'Kritis', sedang: 'Sedang', rendah: 'Rendah' };
    const BOBOT  = { kritis: 1, sedang: 0.6, rendah: 0.3 };
    const STATUS = { menunggu: 'Menunggu Verifikasi', diverifikasi: 'Diverifikasi', diproses: 'Diproses', selesai: 'Selesai' };

    const normPrioritas = function (p) {
        p = String(p ?? '').toLowerCase();
        if (p === 'kritis' || p === 'tinggi') return 'kritis';
        if (p === 'sedang') return 'sedang';
        return 'rendah';
    };

    // Buat elemen DOM dengan teks aman (tanpa innerHTML)
    const el = function (tag, cls, text) {
        const e = document.createElement(tag);
        if (cls) e.className = cls;
        if (text != null) e.textContent = text;
        return e;
    };

    // ---------- Data dari Laravel ----------
    const semua = @json(collect($titikPeta ?? [])->values())
        .map(function (t) {
            return Object.assign({}, t, {
                lat: Number(t.latitude ?? t.lat),
                lng: Number(t.longitude ?? t.lng ?? t.long),
                prioritas: normPrioritas(t.tingkat_prioritas),
                status: String(t.status ?? '').toLowerCase(),
                kategori: t.kategori ?? 'Lainnya',
            });
        })
        .filter(function (t) {
            return Number.isFinite(t.lat) && Number.isFinite(t.lng) &&
                   Math.abs(t.lat) <= 90 && Math.abs(t.lng) <= 180;
        });

    // ---------- Peta ----------
    const peta = L.map('peta-kerusakan', { zoomControl: false }).setView([-6.4025, 106.7942], 13);
    L.control.zoom({ position: 'bottomright' }).addTo(peta);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(peta);

    const lapisMarker = L.layerGroup();
    const lapisHeat = L.heatLayer([], {
        radius: 25, blur: 18, maxZoom: 15,
        gradient: { 0.3: '#FFC107', 0.6: '#F97316', 1.0: '#E53935' }
    });
    const markerById = new Map();
    let mode = 'titik';

    // ---------- Popup ----------
    function sisaSla(t) {
        if (!t.sla_batas || t.status === 'selesai') return null;
        const selisih = new Date(t.sla_batas) - Date.now();
        if (Number.isNaN(selisih)) return null;
        if (selisih < 0) return { teks: 'SLA terlampaui (Overdue)', overdue: true };
        const hari = Math.floor(selisih / 864e5);
        const jam  = Math.floor((selisih % 864e5) / 36e5);
        return { teks: 'Sisa SLA: ' + (hari >= 1 ? hari + ' hari' : jam + ' jam'), overdue: false };
    }

    function buatPopup(t) {
        const box = el('div', '');
        box.style.minWidth = '190px';

        if (t.foto_url) {
            const img = el('img', '');
            img.src = t.foto_url;
            img.alt = '';
            img.style.cssText = 'width:100%;height:100px;object-fit:cover;border-radius:6px;margin-bottom:6px';
            box.appendChild(img);
        }

        box.appendChild(el('strong', '', t.judul ?? 'Laporan kerusakan'));

        const badge = el('span', '', LABEL[t.prioritas]);
        badge.style.cssText = 'display:inline-block;margin:4px 0;padding:1px 8px;border-radius:4px;font-size:11px;color:#fff;background:' + WARNA[t.prioritas];
        box.appendChild(el('div', '')).appendChild(badge);

        box.appendChild(el('p', '', t.kategori + ' · ' + (STATUS[t.status] ?? t.status ?? '-')));
        box.appendChild(el('p', '', t.alamat ?? '-'));
        if (t.dilaporkan) box.appendChild(el('p', '', 'Dilaporkan ' + t.dilaporkan));

        const sla = sisaSla(t);
        if (sla) {
            const p = el('p', '', sla.teks);
            p.style.cssText = 'font-weight:600;color:' + (sla.overdue ? '#E53935' : '#555');
            box.appendChild(p);
        }

        if (t.url_detail) {
            const a = el('a', '', 'Lihat detail laporan');
            a.href = t.url_detail;
            a.style.cssText = 'display:inline-block;margin-top:6px;color:#2563EB;text-decoration:underline';
            box.appendChild(a);
        }
        return box;
    }

    // ---------- Filter ----------
    const fKategori  = document.getElementById('filter-kategori');
    const fStatus    = document.getElementById('filter-status');
    const fPrioritas = document.getElementById('filter-prioritas');

    Array.from(new Set(semua.map(function (t) { return t.kategori; }))).sort().forEach(function (k) {
        const opt = el('option', '', k);
        opt.value = k;
        fKategori.appendChild(opt);
    });

    function terfilter() {
        return semua.filter(function (t) {
            return (!fKategori.value  || t.kategori  === fKategori.value) &&
                   (!fStatus.value    || t.status    === fStatus.value) &&
                   (!fPrioritas.value || t.prioritas === fPrioritas.value);
        });
    }

    // ---------- Render ----------
    function render(sesuaikanZoom) {
        const data = terfilter();

        // Ringkasan
        document.getElementById('cnt-total').textContent  = data.length;
        ['kritis', 'sedang', 'rendah'].forEach(function (p) {
            document.getElementById('cnt-' + p).textContent = data.filter(function (t) { return t.prioritas === p; }).length;
        });
        document.getElementById('peta-kosong').classList.toggle('hidden', data.length > 0);
        document.getElementById('peta-kosong').classList.toggle('flex', data.length === 0);

        // Marker
        lapisMarker.clearLayers();
        markerById.clear();
        data.forEach(function (t) {
            const m = L.circleMarker([t.lat, t.lng], {
                radius: 8, color: WARNA[t.prioritas], fillColor: WARNA[t.prioritas],
                fillOpacity: t.status === 'selesai' ? 0.35 : 0.85, weight: 2
            }).bindPopup(function () { return buatPopup(t); });
            lapisMarker.addLayer(m);
            if (t.id != null) markerById.set(t.id, m);
        });

        // Heatmap
        lapisHeat.setLatLngs(data.map(function (t) { return [t.lat, t.lng, BOBOT[t.prioritas]]; }));

        // Tampilkan lapisan sesuai mode
        peta.removeLayer(lapisMarker);
        peta.removeLayer(lapisHeat);
        if (mode === 'titik') {
            lapisMarker.addTo(peta);
        } else if (data.length > 0) {
            lapisHeat.addTo(peta);
        }

        // Daftar prioritas tinggi yang belum selesai
        const daftar = document.getElementById('list-kritis');
        daftar.replaceChildren();
        const kritis = data.filter(function (t) { return t.prioritas === 'kritis' && t.status !== 'selesai'; }).slice(0, 10);
        if (kritis.length === 0) {
            daftar.appendChild(el('p', 'py-6 text-center text-xs text-abu-tua', 'Tidak ada laporan prioritas tinggi.'));
        }
        kritis.forEach(function (t) {
            const btn = el('button', 'flex w-full items-start gap-2 rounded-xl bg-gray-50 p-2 text-left hover:bg-gray-100');
            btn.type = 'button';
            btn.appendChild(el('span', 'mt-1 h-2.5 w-2.5 flex-shrink-0 rounded-full bg-red-500'));
            const info = el('span', 'min-w-0 flex-1');
            info.appendChild(el('span', 'block truncate text-xs font-semibold text-cyan-6', t.judul ?? '-'));
            info.appendChild(el('span', 'block truncate text-[11px] text-abu-tua', t.alamat ?? '-'));
            info.appendChild(el('span', 'block text-[10px] text-abu-tua', t.kategori));
            btn.appendChild(info);
            btn.addEventListener('click', function () { keLokasi(t); });
            daftar.appendChild(btn);
        });

        // Sesuaikan zoom ke titik yang tampil
        if (sesuaikanZoom && data.length > 0) {
            peta.fitBounds(data.map(function (t) { return [t.lat, t.lng]; }), { padding: [30, 30], maxZoom: 15 });
        }
    }

    function keLokasi(t) {
        if (mode !== 'titik') setMode('titik');
        peta.flyTo([t.lat, t.lng], 16);
        const m = markerById.get(t.id);
        if (m) peta.once('moveend', function () { m.openPopup(); });
    }

    // ---------- Mode Titik / Heatmap ----------
    const tombolMode = document.querySelectorAll('.mode-btn');
    function setMode(m) {
        mode = m;
        tombolMode.forEach(function (b) {
            const aktif = b.dataset.mode === m;
            b.classList.toggle('bg-cyan-6', aktif);
            b.classList.toggle('text-white', aktif);
            b.classList.toggle('text-cyan-6', !aktif);
        });
        document.getElementById('legend-titik').classList.toggle('hidden', m !== 'titik');
        document.getElementById('legend-heat').classList.toggle('hidden', m !== 'heatmap');
        render(false);
    }
    tombolMode.forEach(function (b) {
        b.addEventListener('click', function () { setMode(b.dataset.mode); });
    });

    // ---------- Event ----------
    [fKategori, fStatus, fPrioritas].forEach(function (f) {
        f.addEventListener('change', function () { render(true); });
    });
    document.getElementById('filter-reset').addEventListener('click', function () {
        fKategori.value = fStatus.value = fPrioritas.value = '';
        render(true);
    });

    // ---------- Mulai ----------
    setMode('titik');
    render(true);
    setTimeout(function () { peta.invalidateSize(); }, 200);
});
</script>

@endsection