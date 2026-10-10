@extends('template.layout')

@section('title', 'Closing Report')

@section('content')
<h2>Closing Report</h2>

<p>{{ $penugasan->laporan->judul }}</p>

<form action="{{ route('petugas.penugasan.update', $penugasan->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="status" value="selesai">

    <div>
        <label for="foto_hasil">Foto Hasil Perbaikan</label><br>
        <input type="file" id="foto_hasil" name="foto_hasil" accept="image/png,image/jpeg" required>
        @error('foto_hasil')
            <p style="color: red">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <div>
        <label for="catatan_penyelesaian">Catatan Penyelesaian</label><br>
        <textarea id="catatan_penyelesaian" name="catatan_penyelesaian" rows="5" cols="50" required>{{ old('catatan_penyelesaian') }}</textarea>
        @error('catatan_penyelesaian')
            <p style="color: red">{{ $message }}</p>
        @enderror
    </div>

    <br>

    <button type="submit">Kirim Closing Report</button>
</form>

<br>

<a href="{{ route('petugas.penugasan.show', $penugasan->id) }}">Kembali</a>
@endsection