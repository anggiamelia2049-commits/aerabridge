<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AERA Bridge</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }</style>
</head>
<body class="bg-gray-100 text-abu-tua antialiased">

<header class="bg-cyan-6 px-4 pt-5 pb-44">
    <div class="max-w-3xl mx-auto">
        <nav class="flex items-center justify-between text-white text-sm font-semibold">
            <div class="flex items-center gap-6">
                <img src="{{ asset('images/logo.png') }}" alt="AERA Bridge" class="h-20 w-auto">
                <a href="#tentang" class="underline underline-offset-4 hidden sm:inline">TENTANG AERABRIDGE</a>
            </div>
            <div class="flex items-center gap-5">
                @auth
                    <a href="{{ url('/dashboard') }}">DASHBOARD</a>
                @else
                    <a href="{{ route('login') }}" class="underline underline-offset-4">MASUK</a>
                    <a href="{{ route('register') }}" class="underline underline-offset-4">DAFTAR</a>
                @endauth
            </div>
        </nav>

        <h1 class="mt-12 text-3xl sm:text-4xl font-bold text-white leading-tight">
            Laporkan kerusakan infrastruktur<br>kami hubungkan ke dinas yang tepat
        </h1>
        <p class="mt-4 text-sm text-white/90 max-w-sm">
            Sampaikan laporan anda langsung kepada instansi pemerintah berwenang
        </p>
    </div>
</header>

<main class="max-w-3xl mx-auto px-4 -mt-36 pb-16">
    <div class="bg-white rounded-3xl shadow-xl p-5 sm:p-8">
        <div class="bg-[#76A5AF] text-white text-center font-semibold rounded-md py-2 mb-6">Sampaikan Laporan Anda</div>

        @auth
            <div class="text-center py-6">
                <p class="text-sm mb-4">Kamu sudah masuk. Buat laporan lewat akunmu supaya mendapat Poin Kontribusi.</p>
                <a href="{{ route('warga.laporan.create') }}"
                   class="inline-block px-6 py-2.5 rounded-xl bg-cyan-4 hover:bg-cyan-6 text-white font-bold text-sm transition">
                    Buat Laporan
                </a>
            </div>
        @else
            @php
                $field = 'w-full h-11 px-4 rounded-lg border border-gray-500 bg-white text-xs font-medium text-gray-900 placeholder:text-gray-900 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4';
            @endphp

            <p class="text-xs text-blue-500 mb-4">* Pelaporan Khusus Pengguna Rahasia (tanpa daftar)</p>

            <form id="formLaporan" method="POST" action="{{ route('laporan.publik.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Jenis kerusakan --}}
                <div>
                    @foreach ($kategoris as $kategori)
                        @php
                            $isLainnya = str_contains(strtolower($kategori->nama_kategori), 'lainnya');
                        @endphp

                        <label class="flex items-start gap-3 py-1.5 text-sm cursor-pointer">
                            <input type="radio" name="kategori_id" value="{{ $kategori->id }}" required
                                   data-lainnya="{{ $isLainnya ? '1' : '0' }}"
                                   @checked(old('kategori_id') == $kategori->id)
                                   class="mt-0.5 text-cyan-4 focus:ring-cyan-4">
                            <span>
                                {{ $kategori->nama_kategori }}
                                @if ($isLainnya)
                                    <span class="block text-xs text-gray-400">Silahkan ketik kerusakan lainnya di bawah</span>
                                @endif
                            </span>
                        </label>

                        @if ($isLainnya)
                            <div id="boxKategoriLainnya" class="hidden ml-7 mb-2">
                                <input type="text" name="kategori_lainnya" id="kategori_lainnya"
                                       value="{{ old('kategori_lainnya') }}" maxlength="100"
                                       placeholder="Ketik jenis kerusakan lainnya *"
                                       class="{{ $field }}">
                                @error('kategori_lainnya') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif
                    @endforeach
                    @error('kategori_id') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Judul --}}
                <div>
                    <input type="text" name="judul" value="{{ old('judul') }}" required
                           placeholder="Ketik Judul Laporan Anda *" class="{{ $field }}">
                    @error('judul') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tanggal kejadian --}}
                <div class="relative">
                    <input type="text" name="tanggal_kejadian" value="{{ old('tanggal_kejadian') }}" required
                           max="{{ now()->toDateString() }}"
                           placeholder="Ketik Tanggal Kejadian *"
                           onfocus="this.type='date'" onblur="if(!this.value) this.type='text'"
                           class="{{ $field }} [&::-webkit-calendar-picker-indicator]:opacity-0 [&::-webkit-calendar-picker-indicator]:absolute [&::-webkit-calendar-picker-indicator]:inset-0 [&::-webkit-calendar-picker-indicator]:w-full [&::-webkit-calendar-picker-indicator]:cursor-pointer">
                    <svg class="w-4 h-4 absolute right-4 top-3.5 text-gray-900 pointer-events-none"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                    </svg>
                    @error('tanggal_kejadian') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Lokasi kejadian --}}
                <div>
                    <select name="kecamatan" required class="{{ $field }}">
                        <option value="">Ketik Lokasi Kejadian *</option>
                        @foreach ($kecamatans as $kec)
                            <option value="{{ $kec }}" @selected(old('kecamatan') == $kec)>Kecamatan {{ $kec }}</option>
                        @endforeach
                    </select>
                    @error('kecamatan') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                    @error('latitude') <p class="text-xs text-merah mt-1">Lokasi GPS belum terkunci.</p> @enderror
                </div>

                {{-- Instansi tujuan --}}
                <div>
                    <select name="instansi_id" required class="{{ $field }}">
                        <option value="">Ketik Instansi Tujuan *</option>
                        @foreach ($instansis as $instansi)
                            <option value="{{ $instansi->id }}" @selected(old('instansi_id') == $instansi->id)>{{ $instansi->nama_instansi }}</option>
                        @endforeach
                    </select>
                    @error('instansi_id') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori laporan --}}
                <div>
                    <select name="jenis_laporan" required class="{{ $field }}">
                        <option value="">Pilih kategori Laporan Anda *</option>
                        @foreach ($jenisLaporan as $jenis)
                            <option value="{{ $jenis }}" @selected(old('jenis_laporan') == $jenis)>{{ $jenis }}</option>
                        @endforeach
                    </select>
                    @error('jenis_laporan') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Isi laporan --}}
                <div>
                    <div class="relative">
                        <textarea name="deskripsi" rows="6" required placeholder="Ketik Isi Laporan Anda *"
                                  class="w-full px-4 py-3 pr-10 rounded-lg border border-gray-500 bg-white text-xs font-medium text-gray-900 placeholder:text-gray-900 focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">{{ old('deskripsi') }}</textarea>
                        <svg class="w-4 h-4 absolute right-4 top-4 text-gray-900 pointer-events-none"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    @error('deskripsi') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kamera & lampiran --}}
                <div>
                    <video id="video" class="hidden w-full rounded-lg bg-black mb-3" autoplay playsinline muted></video>
                    <img id="preview" class="hidden w-full rounded-lg mb-3" alt="Foto laporan">
                    <canvas id="canvas" class="hidden"></canvas>

                    {{-- Ambil Foto --}}
                    <button type="button" id="btnBuka" class="flex items-center gap-2 text-sky-400 hover:text-sky-600 transition">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8a2 2 0 012-2h1.5l1-1.5h9l1 1.5H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                            <circle cx="12" cy="12.5" r="3.5"/>
                        </svg>
                        <span class="text-xs font-medium">Ambil Foto</span>
                    </button>

                    <button type="button" id="btnJepret" class="hidden w-full sm:w-auto mb-2 px-5 py-2 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-semibold transition">Jepret</button>
                    <button type="button" id="btnUlang" class="hidden w-full sm:w-auto mb-2 px-5 py-2 rounded-lg border border-gray-400 text-sm font-semibold">Ulangi Foto</button>

                    {{-- Lampiran --}}
                    <label for="lampiran" class="flex items-center gap-2 mt-1 text-sky-400 hover:text-sky-600 cursor-pointer transition w-fit">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 11.5l-6.4 6.4a4.5 4.5 0 01-6.4-6.4l7.1-7.1a3 3 0 014.2 4.2l-7.1 7.1a1.5 1.5 0 01-2.1-2.1l6.4-6.4"/>
                        </svg>
                        <span class="text-xs font-medium">Lampiran (Opsional)</span>
                    </label>
                    <input type="file" id="lampiran" name="lampiran" accept=".jpg,.jpeg,.png,.pdf" class="hidden">

                    {{-- Kartu konfirmasi lampiran --}}
                    <div id="boxLampiran" class="hidden mt-2 ml-8 max-w-sm">
                        <div class="flex items-center gap-3 p-2 rounded-lg border border-green-300 bg-green-50">
                            <img id="previewLampiran" class="hidden w-14 h-14 object-cover rounded-md" alt="Pratinjau lampiran">
                            <div id="ikonPdf" class="hidden w-14 h-14 shrink-0 rounded-md bg-white border border-gray-300 items-center justify-center text-xs font-bold text-merah">PDF</div>

                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-hijau">✓ Lampiran berhasil ditambahkan</p>
                                <p id="namaLampiran" class="text-xs text-gray-600 truncate"></p>
                                <p id="ukuranLampiran" class="text-[11px] text-gray-400"></p>
                            </div>

                            <button type="button" id="hapusLampiran" class="text-xs font-semibold text-merah hover:underline">Hapus</button>
                        </div>
                    </div>

                    @error('lampiran') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                    @error('foto_base64') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <input type="hidden" name="foto_base64" id="foto_base64">
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                {{-- Rahasia + Kirim --}}
                <div class="flex items-center justify-end gap-6">
                    <label class="flex items-center gap-2 text-xs text-gray-900 select-none cursor-not-allowed"
                        title="Laporan tanpa akun otomatis dikirim secara rahasia">
                        <input type="checkbox" checked onclick="return false" tabindex="-1"
                            class="w-4 h-4 rounded text-sky-400 focus:ring-0 pointer-events-none">
                        Rahasia
                    </label>

                    <button type="submit"
                            class="px-8 py-2 rounded-lg bg-[#76A5AF] hover:bg-cyan-4 text-white text-sm font-bold transition">
                        Kirim
                    </button>
                </div>

                {{-- Lacak progres --}}
                <div>
                    <p class="text-xs text-red-500 mb-2">* Lacak Progres Khusus (Pengguna Rahasia)</p>
                    <a href="{{ route('lacak') }}" class="inline-block px-4 py-1.5 rounded-md bg-cyan-6 text-white text-xs font-semibold hover:bg-cyan-4 transition">Lacak Progres</a>
                </div>
            </form>
        @endauth
    </div>

    <section id="tentang" class="mt-10 text-sm text-gray-600 text-center max-w-xl mx-auto">
        <h2 class="font-bold text-cyan-6 mb-2">Tentang AERA Bridge</h2>
        <p>Platform pelaporan kerusakan infrastruktur publik yang menghubungkan masyarakat, instansi, dan petugas lapangan dengan prioritas penanganan berbasis AI.</p>
    </section>
</main>

@guest
<script>
    const $ = (id) => document.getElementById(id);
    const video = $('video'), preview = $('preview'), canvas = $('canvas');
    let stream = null;

    // ===== Lokasi GPS =====
    function kunciLokasi() {
        const status = $('statusLokasi');
        if (!navigator.geolocation) { status.textContent = 'Browser tidak mendukung geolokasi.'; return; }
        status.textContent = 'Mengunci lokasi...';
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                $('latitude').value = pos.coords.latitude.toFixed(8);
                $('longitude').value = pos.coords.longitude.toFixed(8);
                status.textContent = 'Lokasi GPS terkunci.';
            },
            () => { status.textContent = 'Lokasi gagal dikunci. Izinkan akses lokasi lalu coba lagi.'; },
            { enableHighAccuracy: true, timeout: 15000 }
        );
    }

    // ===== Kamera =====
    async function bukaKamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false });
            video.srcObject = stream;
            video.classList.remove('hidden');
            preview.classList.add('hidden');
            $('btnJepret').classList.remove('hidden');
            $('btnBuka').classList.add('hidden');
            $('btnUlang').classList.add('hidden');
            kunciLokasi();
        } catch (e) {
            alert('Kamera tidak bisa diakses. Izinkan akses kamera, atau gunakan Lampiran.');
        }
    }

    function jepret() {
        const skala = Math.min(1, 1280 / video.videoWidth);
        canvas.width = video.videoWidth * skala;
        canvas.height = video.videoHeight * skala;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        const data = canvas.toDataURL('image/jpeg', 0.8);
        $('foto_base64').value = data;
        preview.src = data;

        stream.getTracks().forEach((t) => t.stop());
        video.classList.add('hidden');
        preview.classList.remove('hidden');
        $('btnJepret').classList.add('hidden');
        $('btnUlang').classList.remove('hidden');
    }

    $('btnBuka').addEventListener('click', bukaKamera);
    $('btnUlang').addEventListener('click', bukaKamera);
    $('btnJepret').addEventListener('click', jepret);

    // ===== Lampiran: tampilkan konfirmasi setelah file dipilih =====
    let urlLampiran = null;

    function resetLampiran() {
        $('lampiran').value = '';
        $('boxLampiran').classList.add('hidden');
        $('previewLampiran').classList.add('hidden');
        $('ikonPdf').classList.add('hidden');
        $('ikonPdf').classList.remove('flex');
        if (urlLampiran) { URL.revokeObjectURL(urlLampiran); urlLampiran = null; }
    }

    $('lampiran').addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) { resetLampiran(); return; }

        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran lampiran maksimal 5 MB.');
            resetLampiran();
            return;
        }

        $('namaLampiran').textContent = file.name;
        $('ukuranLampiran').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        $('boxLampiran').classList.remove('hidden');

        if (urlLampiran) { URL.revokeObjectURL(urlLampiran); urlLampiran = null; }

        if (file.type.startsWith('image/')) {
            urlLampiran = URL.createObjectURL(file);
            $('previewLampiran').src = urlLampiran;
            $('previewLampiran').classList.remove('hidden');
            $('ikonPdf').classList.add('hidden');
            $('ikonPdf').classList.remove('flex');
        } else {
            $('previewLampiran').classList.add('hidden');
            $('ikonPdf').classList.remove('hidden');
            $('ikonPdf').classList.add('flex');
        }
    });

    $('hapusLampiran').addEventListener('click', resetLampiran);

    // ===== Kategori "Lainnya": kotak input muncul saat opsi itu dipilih =====
    const boxLainnya = $('boxKategoriLainnya');
    const inputLainnya = $('kategori_lainnya');

    function syncKategoriLainnya(fokus = false) {
        if (!boxLainnya) return;

        const terpilih = document.querySelector('input[name="kategori_id"]:checked');
        const tampil = !!terpilih && terpilih.dataset.lainnya === '1';

        boxLainnya.classList.toggle('hidden', !tampil);
        inputLainnya.required = tampil;

        if (tampil && fokus) inputLainnya.focus();
        if (!tampil) inputLainnya.value = '';
    }

    document.querySelectorAll('input[name="kategori_id"]').forEach((radio) => {
        radio.addEventListener('change', () => syncKategoriLainnya(true));
    });

    syncKategoriLainnya();

    // ===== Validasi sebelum kirim =====
    $('formLaporan').addEventListener('submit', (e) => {
        if (!$('latitude').value || !$('longitude').value) {
            e.preventDefault();
            alert('Lokasi GPS belum terkunci. Izinkan akses lokasi dulu.');
            kunciLokasi();
        }
    });
</script>
@endguest
</body>
</html>