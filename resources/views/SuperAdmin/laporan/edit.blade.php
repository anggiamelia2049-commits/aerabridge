<x-app-layout>
    @php
        $inputClass = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-cyan-4 focus:outline-none focus:ring-2 focus:ring-cyan-3/30';
        $labelClass = 'mb-1 block text-sm font-medium text-gray-700';

        // Sama persis dengan validasi 'in:' di controller (update)
        $opsiStatus = ['Menunggu', 'Terverifikasi AI', 'Diverifikasi', 'Diproses', 'Selesai', 'Ditolak'];
        $opsiPrioritas = ['Kritis', 'Sedang', 'Rendah'];
    @endphp

    <div class="flex min-h-screen bg-abu-muda">
        <aside class="sticky top-0 h-screen shrink-0 self-start">@include('template.sidebar-superadmin')</aside>

        <main class="flex-1 p-6 min-w-0 space-y-6">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Edit Laporan</h1>
                <p class="text-sm text-gray-500">Perbarui data laporan. Kode lacak, pelapor, dan lampiran tidak dapat diubah.</p>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="mb-1 font-medium">Terdapat kesalahan pada isian:</p>
                    <ul class="list-disc space-y-0.5 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Info laporan (hanya baca) --}}
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4 font-bold text-gray-800">Informasi Pelapor</div>

                <dl class="divide-y divide-gray-100 text-sm">
                    <div class="grid grid-cols-1 gap-1 px-6 py-3 sm:grid-cols-3 sm:gap-4">
                        <dt class="font-medium text-gray-500">Kode Lacak</dt>
                        <dd class="font-mono text-gray-800 sm:col-span-2">{{ $laporan->kode_lacak ?? '-' }}</dd>
                    </div>

                    <div class="grid grid-cols-1 gap-1 px-6 py-3 sm:grid-cols-3 sm:gap-4">
                        <dt class="font-medium text-gray-500">Pelapor</dt>
                        <dd class="text-gray-800 sm:col-span-2">{{ $laporan->nama_pelapor ?? optional($laporan->user)->nama ?? '-' }}</dd>
                    </div>

                    @if ($laporan->kategori_lainnya)
                        <div class="grid grid-cols-1 gap-1 px-6 py-3 sm:grid-cols-3 sm:gap-4">
                            <dt class="font-medium text-gray-500">Kategori Lainnya</dt>
                            <dd class="text-gray-800 sm:col-span-2">{{ $laporan->kategori_lainnya }}</dd>
                        </div>
                    @endif

                    @if ($laporan->lampiran)
                        <div class="grid grid-cols-1 gap-1 px-6 py-3 sm:grid-cols-3 sm:gap-4">
                            <dt class="font-medium text-gray-500">Lampiran</dt>
                            <dd class="sm:col-span-2">
                                <img src="{{ asset('storage/' . $laporan->lampiran) }}" alt="Lampiran laporan"
                                     class="max-h-40 rounded border border-gray-200 object-contain">
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <form action="{{ route('super_admin.laporan.update', $laporan->id) }}"
                  method="POST" enctype="multipart/form-data"
                  class="bg-white rounded-2xl shadow overflow-hidden">
                @csrf
                @method('PUT')

                <div class="border-b border-gray-200 px-6 py-4 font-bold text-gray-800">Data Laporan</div>

                <div class="grid grid-cols-1 gap-5 px-6 py-5 md:grid-cols-2">

                    {{-- Kategori --}}
                    <div>
                        <label for="kategori_id" class="{{ $labelClass }}">Kategori</label>
                        <select id="kategori_id" name="kategori_id" required class="{{ $inputClass }}">
                            @foreach ($kategoris as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id', $laporan->kategori_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Instansi --}}
                    <div>
                        <label for="instansi_id" class="{{ $labelClass }}">Instansi</label>
                        <select id="instansi_id" name="instansi_id" required class="{{ $inputClass }}">
                            @foreach ($instansis as $i)
                                <option value="{{ $i->id }}" {{ old('instansi_id', $laporan->instansi_id) == $i->id ? 'selected' : '' }}>
                                    {{ $i->nama_instansi }}
                                </option>
                            @endforeach
                        </select>
                        @error('instansi_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jenis Laporan --}}
                    <div>
                        <label for="jenis_laporan" class="{{ $labelClass }}">Jenis Laporan</label>
                        <input type="text" id="jenis_laporan" name="jenis_laporan" maxlength="50"
                               value="{{ old('jenis_laporan', $laporan->jenis_laporan) }}" class="{{ $inputClass }}">
                        @error('jenis_laporan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal Kejadian --}}
                    <div>
                        <label for="tanggal_kejadian" class="{{ $labelClass }}">Tanggal Kejadian</label>
                        <input type="date" id="tanggal_kejadian" name="tanggal_kejadian"
                               value="{{ old('tanggal_kejadian', $laporan->tanggal_kejadian ? \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('Y-m-d') : '') }}"
                               class="{{ $inputClass }}">
                        @error('tanggal_kejadian') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Judul --}}
                    <div class="md:col-span-2">
                        <label for="judul" class="{{ $labelClass }}">Judul</label>
                        <input type="text" id="judul" name="judul" required maxlength="255"
                               value="{{ old('judul', $laporan->judul) }}" class="{{ $inputClass }}">
                        @error('judul') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="{{ $labelClass }}">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" required
                                  class="{{ $inputClass }}">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                        @error('deskripsi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kecamatan --}}
                    <div>
                        <label for="kecamatan" class="{{ $labelClass }}">Kecamatan</label>
                        <input type="text" id="kecamatan" name="kecamatan" maxlength="100"
                               value="{{ old('kecamatan', $laporan->kecamatan) }}" class="{{ $inputClass }}">
                        @error('kecamatan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="alamat" class="{{ $labelClass }}">Alamat</label>
                        <input type="text" id="alamat" name="alamat"
                               value="{{ old('alamat', $laporan->alamat) }}" class="{{ $inputClass }}">
                        @error('alamat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Foto --}}
                    <div class="md:col-span-2">
                        <label for="foto" class="{{ $labelClass }}">Foto</label>

                        @if ($laporan->foto)
                            <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto laporan"
                                 class="mb-3 max-h-40 rounded border border-gray-200 object-contain">
                        @endif

                        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png"
                               class="block w-full text-sm text-gray-600 file:mr-3 file:rounded file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200">
                        <p class="mt-1 text-xs text-gray-500">JPG atau PNG, maksimal 2 MB. Kosongkan jika tidak ingin mengganti foto.</p>
                        @error('foto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Latitude --}}
                    <div>
                        <label for="latitude" class="{{ $labelClass }}">Latitude</label>
                        <input type="number" step="any" min="-90" max="90" id="latitude" name="latitude" required
                               value="{{ old('latitude', $laporan->latitude) }}" class="{{ $inputClass }}">
                        @error('latitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Longitude --}}
                    <div>
                        <label for="longitude" class="{{ $labelClass }}">Longitude</label>
                        <input type="number" step="any" min="-180" max="180" id="longitude" name="longitude" required
                               value="{{ old('longitude', $laporan->longitude) }}" class="{{ $inputClass }}">
                        @error('longitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tingkat Prioritas --}}
                    <div>
                        <label for="tingkat_prioritas" class="{{ $labelClass }}">Tingkat Prioritas</label>
                        <select id="tingkat_prioritas" name="tingkat_prioritas" class="{{ $inputClass }}">
                            @foreach ($opsiPrioritas as $p)
                                <option value="{{ $p }}" {{ old('tingkat_prioritas', $laporan->tingkat_prioritas) == $p ? 'selected' : '' }}>
                                    {{ $p }}
                                </option>
                            @endforeach
                        </select>
                        @error('tingkat_prioritas') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="{{ $labelClass }}">Status</label>
                        <select id="status" name="status" class="{{ $inputClass }}">
                            @foreach ($opsiStatus as $s)
                                <option value="{{ $s }}" {{ old('status', $laporan->status) == $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Mengubah status ke Diverifikasi/Diproses/Selesai/Ditolak akan mencatat Anda sebagai pemverifikasi.</p>
                        @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4">
                    <a href="{{ route('super_admin.laporan.show', $laporan->id) }}"
                       class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-bold transition">
                        Kembali
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-cyan-4 hover:bg-cyan-6 text-white text-sm font-bold transition">
                        Update Laporan
                    </button>
                </div>
            </form>
        </main>
    </div>
</x-app-layout>