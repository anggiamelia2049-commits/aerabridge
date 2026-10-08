@extends('template.layout')

 @section('content')
<form action="{{ route('instansi.penugasan.store') }}" method="POST">
    {{ csrf_field() }}

    Laporan :
    <select name="laporan_id">
        <option value="">-- Pilih Laporan --</option>
        @foreach ($laporan as $item)
        <option value="{{ $item->id }}" {{ old('laporan_id') == $item->id ? 'selected' : '' }}>
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
        <option value="{{ $item->id }}" {{ old('tim_satgas_id') == $item->id ? 'selected' : '' }}>
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
        <option value="{{ $item->id }}" {{ old('petugas_id') == $item->id ? 'selected' : '' }}>
            {{ $item->nama }}
        </option>
        @endforeach
    </select>
    @if ($errors->has('petugas_id'))
    <span>{{ $errors->first('petugas_id') }}</span>
    @endif

    <br>

    Catatan :
    <textarea name="catatan">{{ old('catatan') }}</textarea>
    @if ($errors->has('catatan'))
    <span>{{ $errors->first('catatan') }}</span>
    @endif

    <br>

    <button type="submit">Disposisikan Tugas</button>
    <a href="{{ route('instansi.penugasan.index') }}">Back</a>
</form>
@endsection
