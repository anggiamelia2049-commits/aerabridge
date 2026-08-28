<form action="{{ route('sla-konfigurasi.store') }}" method="POST">
    {{ csrf_field() }}

    Prioritas :
    <select name="prioritas">
        <option value="">-- Pilih Prioritas --</option>
        <option value="kritis" {{ old('prioritas') == 'kritis' ? 'selected' : '' }}>Kritis</option>
        <option value="sedang" {{ old('prioritas') == 'sedang' ? 'selected' : '' }}>Sedang</option>
        <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
    </select>
    @if ($errors->has('prioritas'))
    <span>{{ $errors->first('prioritas') }}</span>
    @endif

    <br>

    Waktu Respon (jam) :
    <input type="number" name="waktu_respon" min="0" value="{{ old('waktu_respon') }}">
    @if ($errors->has('waktu_respon'))
    <span>{{ $errors->first('waktu_respon') }}</span>
    @endif

    <br>

    Waktu Penyelesaian (jam) :
    <input type="number" name="waktu_penyelesaian" min="0" value="{{ old('waktu_penyelesaian') }}">
    @if ($errors->has('waktu_penyelesaian'))
    <span>{{ $errors->first('waktu_penyelesaian') }}</span>
    @endif

    <br>

    Deskripsi :
    <textarea name="deskripsi">{{ old('deskripsi') }}</textarea>
    @if ($errors->has('deskripsi'))
    <span>{{ $errors->first('deskripsi') }}</span>
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
    <a href="{{ route('sla-konfigurasi.index') }}">Back</a>
</form>