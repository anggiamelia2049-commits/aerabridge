<form action="{{ route('penugasan.update', $penugasan->id) }}" method="POST">
    {{ csrf_field() }}
    @method('PUT')

    Laporan :
    <select name="laporan_id">
        <option value="">-- Pilih Laporan --</option>
        @foreach ($laporan as $item)
        <option value="{{ $item->id }}" {{ old('laporan_id', $penugasan->laporan_id) == $item->id ? 'selected' : '' }}>
            {{ $item->judul }}
        </option>
        @endforeach
    </select>
    @if ($errors->has('laporan_id'))
    <span>{{ $errors->first('laporan_id') }}</span>
    @endif

    <br>

    Tim Satgas :
    <select name="tim_satgas_id">
        <option value="">-- Pilih Tim Satgas --</option>
        @foreach ($timSatgas as $item)
        <option value="{{ $item->id }}" {{ old('tim_satgas_id', $penugasan->tim_satgas_id) == $item->id ? 'selected' : '' }}>
            {{ $item->nama_tim }}
        </option>
        @endforeach
    </select>
    @if ($errors->has('tim_satgas_id'))
    <span>{{ $errors->first('tim_satgas_id') }}</span>
    @endif

    <br>

    Petugas :
    <select name="petugas_id">
        <option value="">-- Pilih Petugas --</option>
        @foreach ($petugas as $item)
        <option value="{{ $item->id }}" {{ old('petugas_id', $penugasan->petugas_id) == $item->id ? 'selected' : '' }}>
            {{ $item->name }}
        </option>
        @endforeach
    </select>
    @if ($errors->has('petugas_id'))
    <span>{{ $errors->first('petugas_id') }}</span>
    @endif

    <br>

    Status :
    <select name="status">
        <option value="ditugaskan" {{ old('status', $penugasan->status) == 'ditugaskan' ? 'selected' : '' }}>Ditugaskan</option>
        <option value="dalam_proses" {{ old('status', $penugasan->status) == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
        <option value="selesai" {{ old('status', $penugasan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
        <option value="dibatalkan" {{ old('status', $penugasan->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
    </select>
    @if ($errors->has('status'))
    <span>{{ $errors->first('status') }}</span>
    @endif

    <br>

    Tanggal Penugasan :
    <input type="date" name="tanggal_penugasan" value="{{ old('tanggal_penugasan', $penugasan->tanggal_penugasan) }}">
    @if ($errors->has('tanggal_penugasan'))
    <span>{{ $errors->first('tanggal_penugasan') }}</span>
    @endif

    <br>

    Tanggal Selesai :
    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $penugasan->tanggal_selesai) }}">
    @if ($errors->has('tanggal_selesai'))
    <span>{{ $errors->first('tanggal_selesai') }}</span>
    @endif

    <br>

    Catatan :
    <textarea name="catatan">{{ old('catatan', $penugasan->catatan) }}</textarea>
    @if ($errors->has('catatan'))
    <span>{{ $errors->first('catatan') }}</span>
    @endif

    <br>

    <button type="submit">Update</button>
    <a href="{{ route('penugasan.index') }}">Back</a>
</form>