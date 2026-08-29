<form action="{{ route('template-pesan.store') }}" method="POST">
    {{ csrf_field() }}

    Kode Template :
    <input type="text" name="kode_template" value="{{ old('kode_template') }}">
    @if ($errors->has('kode_template'))
    <span>{{ $errors->first('kode_template') }}</span>
    @endif

    <br>

    Judul :
    <input type="text" name="judul" value="{{ old('judul') }}">
    @if ($errors->has('judul'))
    <span>{{ $errors->first('judul') }}</span>
    @endif

    <br>

    Isi Pesan :
    <textarea name="isi_pesan">{{ old('isi_pesan') }}</textarea>
    @if ($errors->has('isi_pesan'))
    <span>{{ $errors->first('isi_pesan') }}</span>
    @endif

    <br>

    Kategori :
    <select name="kategori">
        <option value="">-- Pilih Kategori --</option>
        <option value="laporan" {{ old('kategori') == 'laporan' ? 'selected' : '' }}>Laporan</option>
        <option value="penugasan" {{ old('kategori') == 'penugasan' ? 'selected' : '' }}>Penugasan</option>
        <option value="sla" {{ old('kategori') == 'sla' ? 'selected' : '' }}>SLA</option>
        <option value="reward" {{ old('kategori') == 'reward' ? 'selected' : '' }}>Reward</option>
        <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
    </select>
    @if ($errors->has('kategori'))
    <span>{{ $errors->first('kategori') }}</span>
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
    <a href="{{ route('template-pesan.index') }}">Back</a>
</form>