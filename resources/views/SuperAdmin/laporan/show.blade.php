@extends('template.layout')

@section('title', 'Detail Laporan')

@section('content')

@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<table border="1">
    <tr>
        <th>Kode Lacak</th>
        <td>{{ $laporan->kode_lacak ?? '-' }}</td>
    </tr>

    <tr>
        <th>Pelapor</th>
        <td>{{ $laporan->nama_pelapor }}</td>
    </tr>

    <tr>
        <th>Jenis Laporan</th>
        <td>{{ $laporan->jenis_laporan ?? '-' }}</td>
    </tr>

    <tr>
        <th>Kategori</th>
        <td>
            {{ optional($laporan->kategori)->nama_kategori ?? '-' }}
            @if ($laporan->kategori_lainnya)
                ({{ $laporan->kategori_lainnya }})
            @endif
        </td>
    </tr>

    <tr>
        <th>Instansi</th>
        <td>{{ optional($laporan->instansi)->nama_instansi ?? '-' }}</td>
    </tr>

    <tr>
        <th>Judul</th>
        <td>{{ $laporan->judul }}</td>
    </tr>

    <tr>
        <th>Deskripsi</th>
        <td>{{ $laporan->deskripsi }}</td>
    </tr>

    <tr>
        <th>Tanggal Kejadian</th>
        <td>
            {{ $laporan->tanggal_kejadian ? \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d-m-Y') : '-' }}
        </td>
    </tr>

    <tr>
        <th>Kecamatan</th>
        <td>{{ $laporan->kecamatan ?? '-' }}</td>
    </tr>

    <tr>
        <th>Foto</th>
        <td>
            @if ($laporan->foto)
                <img src="{{ asset('storage/' . $laporan->foto) }}" width="200" alt="Foto laporan">
            @else
                Tidak ada foto
            @endif
        </td>
    </tr>

    <tr>
        <th>Lampiran</th>
        <td>
            @if ($laporan->lampiran)
                <img src="{{ asset('storage/' . $laporan->lampiran) }}" width="200" alt="Lampiran laporan">
            @else
                Tidak ada lampiran
            @endif
        </td>
    </tr>

    <tr>
        <th>Latitude</th>
        <td>{{ $laporan->latitude }}</td>
    </tr>

    <tr>
        <th>Longitude</th>
        <td>{{ $laporan->longitude }}</td>
    </tr>

    <tr>
        <th>Alamat</th>
        <td>{{ $laporan->alamat ?? '-' }}</td>
    </tr>

    <tr>
        <th>Tingkat Prioritas</th>
        <td>{{ $laporan->tingkat_prioritas }}</td>
    </tr>

    <tr>
        <th>Status</th>
        <td>{{ $laporan->status }}</td>
    </tr>

    <tr>
        <th>Diverifikasi Oleh</th>
        <td>{{ optional($laporan->diverifikasiOleh)->name ?? '-' }}</td>
    </tr>
</table>

<br>

<a href="{{ route('super_admin.laporan.index') }}">Back</a>

<a href="{{ route('super_admin.laporan.edit', $laporan->id) }}">Edit</a>

@endsection