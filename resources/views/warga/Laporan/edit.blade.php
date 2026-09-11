<form
    action="{{ route('laporan.update', $laporan->id) }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf
    @method('PUT')

    <label>Kategori:</label>
    <select name="kategori_id" required>
        @foreach ($kategoris as $v)
            <option
                value="{{ $v->id }}"
                {{ old('kategori_id', $laporan->kategori_id) == $v->id ? 'selected' : '' }}
            >
                {{ $v->nama_kategori }}
            </option>
        @endforeach
    </select>

    @error('kategori_id')
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <label>Instansi:</label>
    <select name="instansi_id" required>
        @foreach ($instansis as $v)
            <option
                value="{{ $v->id }}"
                {{ old('instansi_id', $laporan->instansi_id) == $v->id ? 'selected' : '' }}
            >
                {{ $v->nama_instansi }}
            </option>
        @endforeach
    </select>

    @error('instansi_id')
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <label>Judul:</label>
    <input
        type="text"
        name="judul"
        value="{{ old('judul', $laporan->judul) }}"
        required
    >

    @error('judul')
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <label>Deskripsi:</label>
    <textarea
        name="deskripsi"
        required
    >{{ old('deskripsi', $laporan->deskripsi) }}</textarea>

    @error('deskripsi')
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <label>Foto:</label>
    <input
        type="file"
        name="foto"
        accept="image/jpeg,image/png"
    >

    @if ($laporan->foto)
        <br><br>

        <img
            src="{{ asset('storage/' . $laporan->foto) }}"
            width="150"
            alt="Foto laporan"
        >
    @endif

    @error('foto')
        <br>
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <label>Latitude:</label>
    <input
        type="text"
        name="latitude"
        value="{{ old('latitude', $laporan->latitude) }}"
        required
    >

    @error('latitude')
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <label>Longitude:</label>
    <input
        type="text"
        name="longitude"
        value="{{ old('longitude', $laporan->longitude) }}"
        required
    >

    @error('longitude')
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <label>Alamat:</label>
    <textarea name="alamat">{{ old('alamat', $laporan->alamat) }}</textarea>

    @error('alamat')
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <label>Tingkat Prioritas:</label>
    <select name="tingkat_prioritas" required>

        <option
            value="Krisis"
            {{ old('tingkat_prioritas', $laporan->tingkat_prioritas) == 'Krisis' ? 'selected' : '' }}
        >
            Krisis
        </option>

        <option
            value="Sedang"
            {{ old('tingkat_prioritas', $laporan->tingkat_prioritas) == 'Sedang' ? 'selected' : '' }}
        >
            Sedang
        </option>

        <option
            value="Rendah"
            {{ old('tingkat_prioritas', $laporan->tingkat_prioritas) == 'Rendah' ? 'selected' : '' }}
        >
            Rendah
        </option>

    </select>

    <br><br>

    <label>Status:</label>
    <select name="status" required>

        <option
            value="Menunggu"
            {{ old('status', $laporan->status) == 'Menunggu' ? 'selected' : '' }}
        >
            Menunggu
        </option>

        <option
            value="Diverifikasi"
            {{ old('status', $laporan->status) == 'Diverifikasi' ? 'selected' : '' }}
        >
            Diverifikasi
        </option>

        <option
            value="Diproses"
            {{ old('status', $laporan->status) == 'Diproses' ? 'selected' : '' }}
        >
            Diproses
        </option>

        <option
            value="Selesai"
            {{ old('status', $laporan->status) == 'Selesai' ? 'selected' : '' }}
        >
            Selesai
        </option>

        <option
            value="Ditolak"
            {{ old('status', $laporan->status) == 'Ditolak' ? 'selected' : '' }}
        >
            Ditolak
        </option>

    </select>

    <br><br>

    <button type="submit">
        Update Laporan
    </button>

    <a href="{{ route('laporan.index') }}">
        Kembali
    </a>
</form>