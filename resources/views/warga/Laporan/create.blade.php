<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-6 sm:p-8">

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-merah/10 border border-merah text-merah rounded-lg text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('warga.laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-abu-tua mb-1">Kategori Kerusakan</label>
                        <select name="kategori_id"
                                class="w-full border border-abu-muda rounded-lg p-2.5 text-sm text-abu-tua focus:outline-none focus:ring-2 focus:ring-cyan-4 focus:border-cyan-4"
                                required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-abu-tua mb-1">Instansi Tujuan</label>
                        <select name="instansi_id"
                                class="w-full border border-abu-muda rounded-lg p-2.5 text-sm text-abu-tua focus:outline-none focus:ring-2 focus:ring-cyan-4 focus:border-cyan-4"
                                required>
                            <option value="">-- Pilih Instansi --</option>
                            @foreach ($instansis as $instansi)
                                <option value="{{ $instansi->id }}" {{ old('instansi_id') == $instansi->id ? 'selected' : '' }}>
                                    {{ $instansi->nama_instansi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-abu-tua mb-1">Judul Laporan</label>
                        <input type="text" name="judul" value="{{ old('judul') }}"
                               class="w-full border border-abu-muda rounded-lg p-2.5 text-sm text-abu-tua focus:outline-none focus:ring-2 focus:ring-cyan-4 focus:border-cyan-4"
                               required>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-abu-tua mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="4"
                                  class="w-full border border-abu-muda rounded-lg p-2.5 text-sm text-abu-tua focus:outline-none focus:ring-2 focus:ring-cyan-4 focus:border-cyan-4"
                                  required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-abu-tua mb-1">
                            Foto Kerusakan (Live Camera)
                            <span class="text-abu-tua/60 font-normal">— gunakan jika kamera tersedia</span>
                        </label>

                        <div class="border border-abu-muda rounded-lg p-4 bg-cyan-muda/10">
                            <video id="video" autoplay playsinline class="w-full rounded-lg mb-3" style="display:none;"></video>
                            <canvas id="canvas" style="display:none;"></canvas>
                            <img id="hasilFoto" class="w-full rounded-lg mb-3" style="display:none;">

                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="nyalakanKamera()" id="btnNyalakan"
                                        class="bg-cyan-6 hover:bg-cyan-6/90 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                                    Nyalakan Kamera
                                </button>
                                <button type="button" onclick="ambilFoto()" id="btnAmbil"
                                        class="bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-medium px-4 py-2 rounded-lg transition" style="display:none;">
                                    Ambil Foto
                                </button>
                                <button type="button" onclick="ulangiFoto()" id="btnUlangi"
                                        class="bg-abu-muda hover:bg-abu-muda/80 text-abu-tua text-sm font-medium px-4 py-2 rounded-lg transition" style="display:none;">
                                    Ulangi
                                </button>
                            </div>
                            <p id="statusKamera" class="text-xs text-abu-tua/70 mt-2"></p>
                        </div>

                        <input type="hidden" name="foto_base64" id="foto_base64">
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-abu-tua mb-1">
                            Upload Foto/Lampiran
                            <span class="text-merah">*Wajib jika kamera tidak tersedia</span>
                        </label>
                        <input type="file" name="lampiran" accept="image/*,.pdf"
                               class="w-full border border-abu-muda rounded-lg p-2.5 text-sm text-abu-tua file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-cyan-3/20 file:text-cyan-6 file:text-sm focus:outline-none focus:ring-2 focus:ring-cyan-4">
                        <p class="text-xs text-abu-tua/70 mt-1">
                            Bisa berupa foto pendukung lain atau dokumen terkait (JPG, PNG, PDF, maks 5MB).
                        </p>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-abu-tua mb-1">Alamat / Patokan Lokasi</label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}"
                               class="w-full border border-abu-muda rounded-lg p-2.5 text-sm text-abu-tua focus:outline-none focus:ring-2 focus:ring-cyan-4 focus:border-cyan-4"
                               placeholder="Contoh: Depan Indomaret, Jl. Merdeka">
                    </div>

                    <div class="mb-5 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-abu-tua mb-1">Latitude</label>
                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                                   class="w-full border border-abu-muda rounded-lg p-2.5 text-sm text-abu-tua bg-abu-muda/30" readonly required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-abu-tua mb-1">Longitude</label>
                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                                   class="w-full border border-abu-muda rounded-lg p-2.5 text-sm text-abu-tua bg-abu-muda/30" readonly required>
                        </div>
                    </div>

                    <div class="mb-8">
                        <button type="button" onclick="ambilLokasi()"
                                class="inline-flex items-center gap-1 bg-abu-muda hover:bg-abu-muda/80 text-abu-tua text-sm font-medium px-4 py-2 rounded-lg transition">
                            📍 Ambil Lokasi Saat Ini
                        </button>
                        <span id="statusLokasi" class="text-sm text-abu-tua/70 ml-2"></span>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('warga.laporan.index') }}"
                           class="px-4 py-2 rounded-lg border border-abu-muda text-abu-tua text-sm font-medium hover:bg-abu-muda/20 transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function ambilLokasi() {
            const status = document.getElementById('statusLokasi');
            if (!navigator.geolocation) {
                status.textContent = 'Browser tidak mendukung Geolocation.';
                return;
            }
            status.textContent = 'Mengambil lokasi...';
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    status.textContent = 'Lokasi berhasil dikunci ✅';
                },
                (error) => {
                    status.textContent = 'Gagal mengambil lokasi: ' + error.message;
                }
            );
        }

        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const hasilFoto = document.getElementById('hasilFoto');
        const inputFotoBase64 = document.getElementById('foto_base64');
        const statusKamera = document.getElementById('statusKamera');

        let stream = null;

        function nyalakanKamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (mediaStream) {
                    stream = mediaStream;
                    video.srcObject = stream;
                    video.style.display = 'block';
                    document.getElementById('btnNyalakan').style.display = 'none';
                    document.getElementById('btnAmbil').style.display = 'inline-block';
                    statusKamera.textContent = 'Kamera aktif, arahkan ke objek kerusakan.';
                })
                .catch(function (error) {
                    statusKamera.textContent = 'Kamera tidak bisa digunakan: ' + error.message;
                });
        }

        function ambilFoto() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const foto = canvas.toDataURL('image/jpeg', 0.8);

            hasilFoto.src = foto;
            hasilFoto.style.display = 'block';
            video.style.display = 'none';

            inputFotoBase64.value = foto;

            document.getElementById('btnAmbil').style.display = 'none';
            document.getElementById('btnUlangi').style.display = 'inline-block';
            statusKamera.textContent = 'Foto berhasil diambil ✅';

            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        }

        function ulangiFoto() {
            hasilFoto.style.display = 'none';
            inputFotoBase64.value = '';
            document.getElementById('btnUlangi').style.display = 'none';
            document.getElementById('btnNyalakan').style.display = 'inline-block';
            statusKamera.textContent = '';
        }

        window.ambilLokasi = ambilLokasi;
        window.nyalakanKamera = nyalakanKamera;
        window.ambilFoto = ambilFoto;
        window.ulangiFoto = ulangiFoto;
    </script>
</x-app-layout>