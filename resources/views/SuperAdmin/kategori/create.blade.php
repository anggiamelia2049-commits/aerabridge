<form action="{{ route('kategori.store') }}" method="POST">

    @csrf

    Nama Kategori :
    <input
        type="text"
        name="nama_kategori"
        value="{{ old('nama_kategori') }}"
        required
    >

    @if ($errors->has('nama_kategori'))
        <span>{{ $errors->first('nama_kategori') }}</span>
    @endif

    <br><br>


    Icon :
    <input
        type="text"
        name="icon"
        value="{{ old('icon') }}"
        placeholder="Contoh: 🕳️"
        required
    >

    @if ($errors->has('icon'))
        <span>{{ $errors->first('icon') }}</span>
    @endif

    <br><br>


    Deskripsi :
    <textarea
        name="deskripsi"
        required
    >{{ old('deskripsi') }}</textarea>

    @if ($errors->has('deskripsi'))
        <span>{{ $errors->first('deskripsi') }}</span>
    @endif

    <br><br>


    Warna Marker :
    <input
        type="text"
        name="warna_marker"
        value="{{ old('warna_marker') }}"
        placeholder="Contoh: #FF0000"
        required
    >

    @if ($errors->has('warna_marker'))
        <span>{{ $errors->first('warna_marker') }}</span>
    @endif

    <br><br>


    Status :
    <select name="status" required>

        <option
            value="Aktif"
            {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}
        >
            Aktif
        </option>

        <option
            value="Nonaktif"
            {{ old('status') == 'Nonaktif' ? 'selected' : '' }}
        >
            Nonaktif
        </option>

    </select>

    @if ($errors->has('status'))
        <span>{{ $errors->first('status') }}</span>
    @endif

    <br><br>


    <button type="submit">
        Save
    </button>

    <a href="{{ route('kategori.index') }}">
        Back
    </a>

</form>