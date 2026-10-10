@extends('template.layout')

@section('title', 'Detail Notifikasi')

@section('content')

<h2>Detail Notifikasi</h2>

<table border="1" cellpadding="10">
    <tr>
        <td>User Penerima</td>
        <td>{{ optional($notifikasi->user)->name ?? '-' }}</td>
    </tr>

    <tr>
        <td>Judul</td>
        <td>{{ $notifikasi->judul }}</td>
    </tr>

    <tr>
        <td>Isi</td>
        <td>{{ $notifikasi->isi }}</td>
    </tr>

    <tr>
        <td>Tipe</td>
        <td>{{ $notifikasi->tipe }}</td>
    </tr>

    <tr>
        <td>Status</td>
        <td>{{ $notifikasi->dibaca ? 'Sudah dibaca' : 'Belum dibaca' }}</td>
    </tr>

    <tr>
        <td>Laporan Terkait</td>
        <td>
            @if ($notifikasi->laporan)
                <a href="{{ route('super_admin.laporan.show', $notifikasi->laporan->id) }}">
                    {{ $notifikasi->laporan->judul }}
                </a>
            @else
                -
            @endif
        </td>
    </tr>

    <tr>
        <td>Dibuat Pada</td>
        <td>{{ $notifikasi->created_at->format('d-m-Y H:i') }}</td>
    </tr>
</table>

<br>

<a href="{{ route('super_admin.notifikasi.index') }}">Kembali</a>

@endsection