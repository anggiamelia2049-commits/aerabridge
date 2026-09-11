<form
    action="{{ route('laporan.store') }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf

    <label>Kategori:</label>
    <select name="kategori_id" required>
        <option value="">Pilih Kategori</option>

        @foreach ($kategoris as $v)
            <option
                value="{{ $v->id }}"
                {{ old('kategori_id') == $v->id ? 'selected' : '' }}
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
        <option value="">Pilih Instansi</option>

        @foreach ($instansis as $v)
            <option
                value="{{ $v->id }}"
                {{ old('instansi_id') == $v->id ? 'selected' : '' }}
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
        value="{{ old('judul') }}"
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
    >{{ old('deskripsi') }}</textarea>

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

    @error('foto')
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <label>Latitude:</label>
    <input
        type="text"
        name="latitude"
        value="{{ old('latitude') }}"
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
        value="{{ old('longitude') }}"
        required
    >

    @error('longitude')
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <label>Alamat:</label>
    <textarea name="alamat">{{ old('alamat') }}</textarea>

    @error('alamat')
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <label>Tingkat Prioritas:</label>
    <select name="tingkat_prioritas">
        <option
            value="Krisis"
            {{ old('tingkat_prioritas', 'Sedang') == 'Krisis' ? 'selected' : '' }}
        >
            Krisis
        </option>

        <option
            value="Sedang"
            {{ old('tingkat_prioritas', 'Sedang') == 'Sedang' ? 'selected' : '' }}
        >
            Sedang
        </option>

        <option
            value="Rendah"
            {{ old('tingkat_prioritas', 'Sedang') == 'Rendah' ? 'selected' : '' }}
        >
            Rendah
        </option>
    </select>

    @error('tingkat_prioritas')
        <span>{{ $message }}</span>
    @enderror

    <br><br>

    <button type="submit">
        Simpan Laporan
    </button>

    <a href="{{ route('laporan.index') }}">
        Kembali
    </a>
</form>