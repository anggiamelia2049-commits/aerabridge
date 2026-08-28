<form action="{{ route('sla-konfigurasi.update', $slaKonfigurasi->id) }}" method="POST">
    {{ csrf_field() }}
    @method('PUT')

    Prioritas :
    <select name="prioritas">
        <option value="">-- Pilih Prioritas --</option>
        <option value="kritis" {{ old('prioritas', $slaKonfigurasi->prioritas) == 'kritis' ? 'selected' : '' }}>Kritis</option>
        <option value="sedang" {{ old('prioritas', $slaKonfigurasi->prioritas) == 'sedang' ? 'selected' : '' }}>Sedang</option>
        <option value="rendah" {{ old('prioritas', $slaKonfigurasi->prioritas) == 'rendah' ? 'selected' : '' }}>Rendah</option>
    </select>
    @if ($errors->has('prioritas'))
    <span>{{ $errors->first('prioritas') }}</span>
    @endif

    <br>

    Waktu Respon (jam) :
    <input type="number" name="waktu_respon" min="0" value="{{ old('waktu_respon', $slaKonfigurasi->waktu_respon) }}">
    @if ($errors->has('waktu_respon'))
    <span>{{ $errors->first('waktu_respon') }}</span>
    @endif

    <br>

    Waktu Penyelesaian (jam) :
    <input type="number" name="waktu_penyelesaian" min="0" value="{{ old('waktu_penyelesaian', $slaKonfigurasi->waktu_penyelesaian) }}">
    @if ($errors->has('waktu_penyelesaian'))
    <span>{{ $errors->first('waktu_penyelesaian') }}</span>
    @endif

    <br>

    Deskripsi :
    <textarea name="deskripsi">{{ old('deskripsi', $slaKonfigurasi->deskripsi) }}</textarea>
    @if ($errors->has('deskripsi'))
    <span>{{ $errors->first('deskripsi') }}</span>
    @endif

    <br>

    Status :
    <select name="status">
        <option value="aktif" {{ old('status', $slaKonfigurasi->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ old('status', $slaKonfigurasi->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
    </select>
    @if ($errors->has('status'))
    <span>{{ $errors->first('status') }}</span>
    @endif

    <br>

    <button type="submit">Update</button>
    <a href="{{ route('sla-konfigurasi.index') }}">Back</a>
</form>