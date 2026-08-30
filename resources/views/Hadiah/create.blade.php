<form action="{{ route('hadiah.store') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}

    Nama Hadiah :
    <input type="text" name="nama_hadiah" value="{{ old('nama_hadiah') }}">
    @if ($errors->has('nama_hadiah'))
        <span>{{ $errors->first('nama_hadiah') }}</span>
    @endif

    <br>

    Deskripsi :
    <textarea name="deskripsi">{{ old('deskripsi') }}</textarea>
    @if ($errors->has('deskripsi'))
        <span>{{ $errors->first('deskripsi') }}</span>
    @endif

    <br>

    Poin Dibutuhkan :
    <input type="number" name="poin_dibutuhkan" value="{{ old('poin_dibutuhkan') }}">
    @if ($errors->has('poin_dibutuhkan'))
        <span>{{ $errors->first('poin_dibutuhkan') }}</span>
    @endif

    <br>

    Stok :
    <input type="number" name="stok" value="{{ old('stok', 0) }}">
    @if ($errors->has('stok'))
        <span>{{ $errors->first('stok') }}</span>
    @endif

    <br>

    Gambar :
    <input type="file" name="gambar">
    @if ($errors->has('gambar'))
        <span>{{ $errors->first('gambar') }}</span>
    @endif

    <br>

    Status :
    <select name="status">
        <option value="">-- Pilih Status --</option>

        <option value="tersedia"
            {{ old('status') == 'tersedia' ? 'selected' : '' }}>
            Tersedia
        </option>

        <option value="habis"
            {{ old('status') == 'habis' ? 'selected' : '' }}>
            Habis
        </option>

        <option value="nonaktif"
            {{ old('status') == 'nonaktif' ? 'selected' : '' }}>
            Nonaktif
        </option>
    </select>
    @if ($errors->has('status'))
        <span>{{ $errors->first('status') }}</span>
    @endif

    <br>

    <button type="submit">Save</button>
    <a href="{{ route('hadiah.index') }}">Back</a>
</form>
