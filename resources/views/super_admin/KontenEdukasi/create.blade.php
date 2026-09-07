<form action="{{ route('KontenEdukasi.store') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}

    Judul :
    <input type="text" name="judul" value="{{ old('judul') }}">
    @if ($errors->has('judul'))
    <span>{{ $errors->first('judul') }}</span>
    @endif

    <br>

    Thumbnail :
    <input type="file" name="thumbnail">
    @if ($errors->has('thumbnail'))
    <span>{{ $errors->first('thumbnail') }}</span>
    @endif

    <br>

    Isi :
    <textarea name="isi">{{ old('isi') }}</textarea>
    @if ($errors->has('isi'))
    <span>{{ $errors->first('isi') }}</span>
    @endif

    <br>

    Kategori :
    <input type="text" name="kategori" value="{{ old('kategori') }}">
    @if ($errors->has('kategori'))
    <span>{{ $errors->first('kategori') }}</span>
    @endif

    <br>

    Status :
    <select name="status">
        <option value="draft">Draft</option>
        <option value="publish">Publish</option>
        <option value="nonaktif">Nonaktif</option>
    </select>

    <br>

    <button type="submit">Save</button>
    <a href="{{ route('KontenEdukasi.index') }}">Back</a>
</form>