<form action="{{ route('hadiah.update', $hadiah->id) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    Nama Hadiah :
    <input type="text" name="nama_hadiah"
        value="{{ old('nama_hadiah', $hadiah->nama_hadiah) }}">
    @if ($errors->has('nama_hadiah'))
        <span>{{ $errors->first('nama_hadiah') }}</span>
    @endif

    <br>

    Deskripsi :
    <textarea name="deskripsi">{{ old('deskripsi', $hadiah->deskripsi) }}</textarea>
    @if ($errors->has('deskripsi'))
        <span>{{ $errors->first('deskripsi') }}</span>
    @endif

    <br>

    Poin Dibutuhkan :
    <input type="number" name="poin_dibutuhkan"
        value="{{ old('poin_dibutuhkan', $hadiah->poin_dibutuhkan) }}">
    @if ($errors->has('poin_dibutuhkan'))
        <span>{{ $errors->first('poin_dibutuhkan') }}</span>
    @endif

    <br>

    Stok :
    <input type="number" name="stok"
        value="{{ old('stok', $hadiah->stok) }}">
    @if ($errors->has('stok'))
        <span>{{ $errors->first('stok') }}</span>
    @endif

    <br>

    Gambar Saat Ini :
    @if($hadiah->gambar)
        <br>
        <img src="{{ asset('storage/' . $hadiah->gambar) }}" width="120">
        <br>
        {{ $hadiah->gambar }}
    @else
        Belum ada gambar
    @endif

    <br>

    Gambar Baru :
    <input type="file" name="gambar">
    @if ($errors->has('gambar'))
        <span>{{ $errors->first('gambar') }}</span>
    @endif

    <br>

    Status :
    <select name="status">
        <option value="tersedia"
            {{ old('status', $hadiah->status) == 'tersedia' ? 'selected' : '' }}>
            Tersedia
        </option>

        <option value="habis"
            {{ old('status', $hadiah->status) == 'habis' ? 'selected' : '' }}>
            Habis
        </option>

        <option value="nonaktif"
            {{ old('status', $hadiah->status) == 'nonaktif' ? 'selected' : '' }}>
            Nonaktif
        </option>
    </select>
    @if ($errors->has('status'))
        <span>{{ $errors->first('status') }}</span>
    @endif

    <br>

    <button type="submit">Update</button>
    <a href="{{ route('hadiah.index') }}">Back</a>
</form>
