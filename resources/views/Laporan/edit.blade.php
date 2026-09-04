<form action="{{ route('Laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    @method('PUT')

    Kategori :
    <select name="kategori_id">
        @foreach ($kategoris as $v)
            <option value="{{ $v->id }}" {{ $laporan->kategori_id == $v->id ? 'selected' : '' }}>
                {{ $v->nama_kategori }}
            </option>
        @endforeach
    </select>

    <br>

    Instansi :
    <select name="instansi_id">
        @foreach ($instansis as $v)
            <option value="{{ $v->id }}" {{ $laporan->instansi_id == $v->id ? 'selected' : '' }}>
                {{ $v->nama_instansi }}
            </option>
        @endforeach
    </select>

    <br>

    Judul :
    <input type="text" name="judul" value="{{ $laporan->judul }}" required>

    <br>

    Deskripsi :
    <textarea name="deskripsi" required>{{ $laporan->deskripsi }}</textarea>

    <br>

    Foto :
    <input type="file" name="foto">

    @if ($laporan->foto)
        <br>
        <img src="{{ asset('storage/' . $laporan->foto) }}" width="150">
    @endif

    <br>

    Latitude :
    <input type="text" name="latitude" value="{{ $laporan->latitude }}" required>

    <br>

    Longitude :
    <input type="text" name="longitude" value="{{ $laporan->longitude }}" required>

    <br>

    Alamat :
    <textarea name="alamat">{{ $laporan->alamat }}</textarea>

    <br>

    Tingkat Prioritas :
    <select name="tingkat_prioritas">
        <option value="Krisis" {{ $laporan->tingkat_prioritas == 'Krisis' ? 'selected' : '' }}>
            Krisis
        </option>

        <option value="Sedang" {{ $laporan->tingkat_prioritas == 'Sedang' ? 'selected' : '' }}>
            Sedang
        </option>

        <option value="Rendah" {{ $laporan->tingkat_prioritas == 'Rendah' ? 'selected' : '' }}>
            Rendah
        </option>
    </select>

    <br>

    Status :
    <select name="status">
        <option value="Menunggu" {{ $laporan->status == 'Menunggu' ? 'selected' : '' }}>
            Menunggu
        </option>

        <option value="Diverifikasi" {{ $laporan->status == 'Diverifikasi' ? 'selected' : '' }}>
            Diverifikasi
        </option>

        <option value="Diproses" {{ $laporan->status == 'Diproses' ? 'selected' : '' }}>
            Diproses
        </option>

        <option value="Selesai" {{ $laporan->status == 'Selesai' ? 'selected' : '' }}>
            Selesai
        </option>

        <option value="Ditolak" {{ $laporan->status == 'Ditolak' ? 'selected' : '' }}>
            Ditolak
        </option>
    </select>

    <br>

    <button type="submit">Update</button>

    <a href="{{ route('Laporan.index') }}">Back</a>
</form>