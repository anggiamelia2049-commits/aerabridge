@extends('template.layout')

@section('title', 'Detail Penugasan')

@section('content')
@php
    $laporan = $penugasan->laporan;
    $adaKoordinat = $laporan->latitude && $laporan->longitude;
@endphp

<h2>Detail Penugasan</h2>

<h3>{{ $laporan->judul }}</h3>

<table>
    <tr>
        <th>Kategori</th>
        <td>{{ $laporan->kategori->nama_kategori ?? '-' }}</td>
    </tr>
    <tr>
        <th>Instansi</th>
        <td>{{ $laporan->instansi->nama_instansi ?? '-' }}</td>
    </tr>
    <tr>
        <th>Prioritas</th>
        <td>{{ $laporan->tingkat_prioritas }}</td>
    </tr>
    <tr>
        <th>Status Tugas</th>
        <td>{{ $penugasan->labelStatus() }}</td>
    </tr>
    <tr>
        <th>Deskripsi</th>
        <td>{{ $laporan->deskripsi }}</td>
    </tr>
    <tr>
        <th>Alamat</th>
        <td>{{ $laporan->alamat ?: '-' }}</td>
    </tr>
    <tr>
        <th>Catatan dari Instansi</th>
        <td>{{ $penugasan->catatan ?: '-' }}</td>
    </tr>
    <tr>
        <th>Tanggal Penugasan</th>
        <td>{{ $penugasan->tanggal_penugasan->format('d-m-Y H:i') }}</td>
    </tr>
    <tr>
        <th>Batas SLA</th>
        <td>{{ $batasSla ? $batasSla->format('d-m-Y H:i') : '-' }}</td>
    </tr>
    <tr>
        <th>Sisa Waktu</th>
        <td @if ($overdue) style="color: red; font-weight: bold" @endif>{{ $penugasan->teksSla() }}</td>
    </tr>
</table>

<h4>Foto Laporan</h4>
@if ($laporan->foto)
    <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto laporan" width="300">
@else
    <p>Tidak ada foto.</p>
@endif

@if ($penugasan->status === 'selesai')
    <h4>Hasil Perbaikan</h4>
    @if ($penugasan->foto_hasil)
        <img src="{{ asset('storage/' . $penugasan->foto_hasil) }}" alt="Foto hasil perbaikan" width="300">
    @endif
    <p>{{ $penugasan->catatan_penyelesaian }}</p>
    @if ($penugasan->tanggal_selesai)
        <p>Dikirim pada {{ $penugasan->tanggal_selesai->format('d-m-Y H:i') }}</p>
    @endif
@endif

<br>

@if ($adaKoordinat)
    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $laporan->latitude }},{{ $laporan->longitude }}"
       target="_blank" rel="noopener">Buka Rute</a>
@endif

@if ($penugasan->status === 'ditugaskan')
    <form action="{{ route('petugas.penugasan.update', $penugasan->id) }}" method="POST"
          onsubmit="return confirm('Mulai tugas ini sekarang?')">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="dalam_proses">
        <button type="submit">Mulai Tugas</button>
    </form>
@endif

@if ($penugasan->status === 'dalam_proses')
    <a href="{{ route('petugas.penugasan.edit', $penugasan->id) }}">Isi Closing Report</a>
@endif

<br><br>

<a href="{{ route('petugas.penugasan.index') }}">Kembali</a>
@endsection