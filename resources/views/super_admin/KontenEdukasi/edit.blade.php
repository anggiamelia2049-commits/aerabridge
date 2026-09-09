<form action="{{ route('KontenEdukasi.update', $konten->id) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    @method('PUT')

    Judul :
    <input type="text" name="judul" value="{{ $konten->judul }}" required>

    <br>

    Thumbnail :
    <input type="file" name="thumbnail">

    @if ($konten->thumbnail)
        <br>
        <img src="{{ asset('storage/' . $konten->thumbnail) }}" width="150">
    @endif

    @if ($errors->has('thumbnail'))
    <span>{{ $errors->first('thumbnail') }}</span>
    @endif

    <br>

    Isi :
    <textarea name="isi" required>{{ $konten->isi }}</textarea>

    <br>

    Kategori :
    <input type="text" name="kategori" value="{{ $konten->kategori }}" required>

    <br>

    Status :
    <select name="status">
        <option value="draft" {{ $konten->status == 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="publish" {{ $konten->status == 'publish' ? 'selected' : '' }}>Publish</option>
        <option value="nonaktif" {{ $konten->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
    </select>

    <br>

    <button type="submit">Update</button>
    <a href="{{ route('KontenEdukasi.index') }}">Back</a>
</form>