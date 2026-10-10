@extends('template.layout')

@section('title', 'Detail Notifikasi')

@section('content')
<h2>{{ $notifikasi->judul }}</h2>

<p>
    <small>{{ $notifikasi->tipe }}, {{ $notifikasi->created_at->format('d-m-Y H:i') }}</small>
</p>

<p>{{ $notifikasi->isi }}</p>

@if ($notifikasi->laporan)
    <p>Laporan terkait: <strong>{{ $notifikasi->laporan->judul }}</strong></p>
@endif

@if ($penugasan)
    <p>
        <a href="{{ route('petugas.penugasan.show', $penugasan->id) }}">Buka Tugas</a>
    </p>
@endif

<form action="{{ route('petugas.notifikasi.destroy', $notifikasi->id) }}" method="POST"
      onsubmit="return confirm('Hapus notifikasi ini?')">
    @csrf
    @method('DELETE')
    <button type="submit">Hapus Notifikasi</button>
</form>

<br>

<a href="{{ route('petugas.notifikasi.index') }}">Kembali</a>
@endsection