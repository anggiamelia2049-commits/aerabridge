<form action="{{ route('KategoriKerusakan.update', $kategori->id) }}" method="POST">
    {{ csrf_field() }}
    @method('PUT')

    Nama Kategori :
    <input type="text" name="nama_kategori" value="{{ $kategori->nama_kategori }}" required>

    <br>

    Icon :
    <input type="text" name="icon" value="{{ $kategori->icon }}">

    <br>

    Deskripsi :
    <textarea name="deskripsi">{{ $kategori->deskripsi }}</textarea>

    <br>

    Warna Marker :
    <input type="text" name="warna_marker" value="{{ $kategori->warna_marker }}">

    <br>

    Status :
    <select name="status">
        <option value="Aktif" {{ $kategori->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="Nonaktif" {{ $kategori->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <br>

    <button type="submit">Update</button>
    <a href="{{ route('KategoriKerusakan.index') }}">Back</a>
</form>