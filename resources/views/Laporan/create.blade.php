<form action="{{ route('Laporan.store') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}

    Kategori :
    <select name="kategori_id">
        <option value="">Pilih Kategori</option>

        @foreach ($kategoris as $v)
            <option value="{{ $v->id }}">{{ $v->nama_kategori }}</option>
        @endforeach
    </select>

    @if ($errors->has('kategori_id'))
    <span>{{ $errors->first('kategori_id') }}</span>
    @endif

    <br>

    Instansi :
    <select name="instansi_id">
        <option value="">Pilih Instansi</option>

        @foreach ($instansis as $v)
            <option value="{{ $v->id }}">{{ $v->nama_instansi }}</option>
        @endforeach
    </select>

    @if ($errors->has('instansi_id'))
    <span>{{ $errors->first('instansi_id') }}</span>
    @endif

    <br>

    Judul :
    <input type="text" name="judul" value="{{ old('judul') }}">

    @if ($errors->has('judul'))
    <span>{{ $errors->first('judul') }}</span>
    @endif

    <br>

    Deskripsi :
    <textarea name="deskripsi">{{ old('deskripsi') }}</textarea>

    @if ($errors->has('deskripsi'))
    <span>{{ $errors->first('deskripsi') }}</span>
    @endif

    <br>

    Foto :
    <input type="file" name="foto">

    @if ($errors->has('foto'))
    <span>{{ $errors->first('foto') }}</span>
    @endif

    <br>

    Latitude :
    <input type="text" name="latitude" value="{{ old('latitude') }}">

    @if ($errors->has('latitude'))
    <span>{{ $errors->first('latitude') }}</span>
    @endif

    <br>

    Longitude :
    <input type="text" name="longitude" value="{{ old('longitude') }}">

    @if ($errors->has('longitude'))
    <span>{{ $errors->first('longitude') }}</span>
    @endif

    <br>

    Alamat :
    <textarea name="alamat">{{ old('alamat') }}</textarea>

    @if ($errors->has('alamat'))
    <span>{{ $errors->first('alamat') }}</span>
    @endif

    <br>

    Tingkat Prioritas :
    <select name="tingkat_prioritas">
        <option value="Krisis">Krisis</option>
        <option value="Sedang">Sedang</option>
        <option value="Rendah">Rendah</option>
    </select>

    <br>

    Status :
    <select name="status">
        <option value="Menunggu">Menunggu</option>
        <option value="Diverifikasi">Diverifikasi</option>
        <option value="Diproses">Diproses</option>
        <option value="Selesai">Selesai</option>
        <option value="Ditolak">Ditolak</option>
    </select>

    <br>

    <button type="submit">Save</button>

    <a href="{{ route('Laporan.index') }}">Back</a>
</form>