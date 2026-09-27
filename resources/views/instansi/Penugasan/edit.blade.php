<form action="{{ route('instansi.penugasan.update', $penugasan->id) }}" method="POST">
    {{ csrf_field() }}
    @method('PUT')

    <input type="hidden" name="aksi" value="redisposisi">

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
            {{ $item->nama }}
        </option>
        @endforeach
    </select>
    @if ($errors->has('petugas_id'))
    <span>{{ $errors->first('petugas_id') }}</span>
    @endif

    <br>

    Catatan :
    <textarea name="catatan">{{ old('catatan', $penugasan->catatan) }}</textarea>

    <br>

    <button type="submit">Alihkan Tugas</button>
    <a href="{{ route('instansi.penugasan.index') }}">Back</a>
</form>