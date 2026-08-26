<form action="{{ route('KategoriKerusakan.store') }}" method="POST">
    {{ csrf_field() }}

    Nama Kategori :
    <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}">
    @if ($errors->has('nama_kategori'))
    <span>{{ $errors->first('nama_kategori') }}</span>
    @endif

    <br>

    Icon :
    <input type="text" name="icon" value="{{ old('icon') }}">
    @if ($errors->has('icon'))
    <span>{{ $errors->first('icon') }}</span>
    @endif

    <br>

    Deskripsi :
    <textarea name="deskripsi">{{ old('deskripsi') }}</textarea>
    @if ($errors->has('deskripsi'))
    <span>{{ $errors->first('deskripsi') }}</span>
    @endif

    <br>

    Warna Marker :
    <input type="text" name="warna_marker" value="{{ old('warna_marker') }}">
    @if ($errors->has('warna_marker'))
    <span>{{ $errors->first('warna_marker') }}</span>
    @endif

    <br>

    Status :
    <select name="status">
        <option value="Aktif">Aktif</option>
        <option value="Nonaktif">Nonaktif</option>
    </select>

    <br>

    <button type="submit">Save</button>
    <a href="{{ route('KategoriKerusakan.index') }}">Back</a>
</form>