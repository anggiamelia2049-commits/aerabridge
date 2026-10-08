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
                <img src="{{ asset('images/logo-aera-bridge.png') }}" alt="AERA Bridge" class="h-10 w-auto brightness-0 invert">
                <a href="#tentang" class="underline underline-offset-4 hidden sm:inline">TENTANG AERABRIDGE</a>
            </div>
            <div class="flex items-center gap-5">
                @auth
                    <a href="{{ url('/dashboard') }}">DASHBOARD</a>
                @else
                    <a href="{{ route('login') }}">MASUK</a>
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
        <div class="bg-cyan-4 text-white text-center font-semibold rounded-md py-2 mb-6">Sampaikan Laporan Anda</div>

        @auth
            <div class="text-center py-6">
                <p class="text-sm mb-4">Kamu sudah masuk. Buat laporan lewat akunmu supaya mendapat Poin Kontribusi.</p>
                <a href="{{ route('warga.laporan.create') }}"
                   class="inline-block px-6 py-2.5 rounded-xl bg-cyan-4 hover:bg-cyan-6 text-white font-bold text-sm transition">
                    Buat Laporan
                </a>
            </div>
        @else
            <p class="text-xs text-gray-500 mb-4">* Pelaporan khusus pengguna rahasia (tanpa daftar)</p>

            <form id="formLaporan" method="POST" action="{{ route('laporan.publik.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Jenis kerusakan --}}
                <div>
                    @foreach ($kategoris as $kategori)
                        <label class="flex items-center gap-3 py-1.5 text-sm cursor-pointer">
                            <input type="radio" name="kategori_id" value="{{ $kategori->id }}" required
                                   @checked(old('kategori_id') == $kategori->id)
                                   class="text-cyan-4 focus:ring-cyan-4">
                            {{ $kategori->nama_kategori }}
                        </label>
                    @endforeach
                    @error('kategori_id') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Ketik Judul Laporan Anda *"
                           class="w-full h-11 px-4 rounded-lg border-abu-muda text-sm focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">
                    @error('judul') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <select name="instansi_id" required
                            class="w-full h-11 px-4 rounded-lg border-abu-muda text-sm focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">
                        <option value="">Ketik Instansi Tujuan *</option>
                        @foreach ($instansis as $instansi)
                            <option value="{{ $instansi->id }}" @selected(old('instansi_id') == $instansi->id)>{{ $instansi->nama_instansi }}</option>
                        @endforeach
                    </select>
                    @error('instansi_id') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <input type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Alamat / patokan lokasi kejadian (opsional)"
                           class="w-full h-11 px-4 rounded-lg border-abu-muda text-sm focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">
                    <p id="statusLokasi" class="text-xs text-gray-500 mt-1">Lokasi GPS akan dikunci saat kamu mengambil foto.</p>
                    @error('latitude') <p class="text-xs text-merah mt-1">Lokasi GPS belum terkunci.</p> @enderror
                </div>

                <div>
                    <textarea name="deskripsi" rows="5" required placeholder="Ketik Isi Laporan Anda *"
                              class="w-full px-4 py-3 rounded-lg border-abu-muda text-sm focus:border-cyan-4 focus:ring-1 focus:ring-cyan-4">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kamera --}}
                <div class="space-y-3">
                    <video id="video" class="hidden w-full rounded-lg bg-black" autoplay playsinline muted></video>
                    <img id="preview" class="hidden w-full rounded-lg" alt="Foto laporan">
                    <canvas id="canvas" class="hidden"></canvas>

                    <div class="flex flex-wrap gap-2">
                        <button type="button" id="btnBuka" class="px-4 py-2 rounded-lg border border-cyan-4 text-cyan-4 text-sm font-semibold hover:bg-cyan-4 hover:text-white transition">Ambil Foto</button>
                        <button type="button" id="btnJepret" class="hidden px-4 py-2 rounded-lg bg-cyan-4 text-white text-sm font-semibold">Jepret</button>
                        <button type="button" id="btnUlang" class="hidden px-4 py-2 rounded-lg border border-gray-400 text-sm font-semibold">Ulangi</button>
                    </div>

                    <div class="text-sm">
                        <label class="block text-gray-600 mb-1">Lampiran (Opsional, jpg/png/pdf)</label>
                        <input type="file" name="lampiran" accept=".jpg,.jpeg,.png,.pdf" class="text-xs">
                        @error('lampiran') <p class="text-xs text-merah mt-1">{{ $message }}</p> @enderror
                    </div>
                    @error('foto_base64') <p class="text-xs text-merah">{{ $message }}</p> @enderror
                </div>

                <input type="hidden" name="foto_base64" id="foto_base64">
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('lacak') }}" class="px-4 py-2 rounded-lg bg-cyan-6 text-white text-sm font-semibold hover:bg-cyan-4 transition">Lacak Progres</a>
                    <button type="submit" class="px-8 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">Kirim</button>
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