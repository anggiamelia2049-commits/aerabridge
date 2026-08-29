<form action="{{ route('tim-satgas.store') }}" method="POST">
    {{ csrf_field() }}

    Instansi :
    <select name="instansi_id">
        <option value="">-- Pilih Instansi --</option>
        @foreach ($instansi as $item)
        <option value="{{ $item->id }}" {{ old('instansi_id') == $item->id ? 'selected' : '' }}>
            {{ $item->nama }}
        </option>
        @endforeach
    </select>
    @if ($errors->has('instansi_id'))
    <span>{{ $errors->first('instansi_id') }}</span>
    @endif

    <br>

    Nama Tim :
    <input type="text" name="nama_tim" value="{{ old('nama_tim') }}">
    @if ($errors->has('nama_tim'))
    <span>{{ $errors->first('nama_tim') }}</span>
    @endif

    <br>

    Ketua :
    <input type="text" name="ketua" value="{{ old('ketua') }}">
    @if ($errors->has('ketua'))
    <span>{{ $errors->first('ketua') }}</span>
    @endif

    <br>

    Jumlah Anggota :
    <input type="number" name="jumlah_anggota" min="0" value="{{ old('jumlah_anggota') }}">
    @if ($errors->has('jumlah_anggota'))
    <span>{{ $errors->first('jumlah_anggota') }}</span>
    @endif

    <br>

    Kontak :
    <input type="text" name="kontak" value="{{ old('kontak') }}">
    @if ($errors->has('kontak'))
    <span>{{ $errors->first('kontak') }}</span>
    @endif

    <br>

    Status :
    <select name="status">
        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
    </select>
    @if ($errors->has('status'))
    <span>{{ $errors->first('status') }}</span>
    @endif

    <br>

    <button type="submit">Save</button>
    <a href="{{ route('tim-satgas.index') }}">Back</a>
</form>