@extends('template.layout')

@section('title', 'Edit Laporan')

@section('content')

@if ($errors->any())
    <ul style="color: red;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

{{-- Info laporan (hanya baca, tidak diubah lewat form ini) --}}
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
        <th>Tanggal Kejadian</th>
        <td>
            {{ $laporan->tanggal_kejadian ? \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d-m-Y') : '-' }}
        </td>
    </tr>
    <tr>
        <th>Kecamatan</th>
        <td>{{ $laporan->kecamatan ?? '-' }}</td>
    </tr>
    @if ($laporan->kategori_lainnya)
        <tr>
            <th>Kategori Lainnya</th>
            <td>{{ $laporan->kategori_lainnya }}</td>
        </tr>
    @endif
</table>

<br>

<form
    action="{{ route('super_admin.laporan.update', $laporan->id) }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf
    @method('PUT')

    <label>Kategori:</label>
    <select name="kategori_id" required>
        @foreach ($kategoris as $v)
            <option
                value="{{ $v->id }}"
                {{ old('kategori_id', $laporan->kategori_id) == $v->id ? 'selected' : '' }}
            >
                {{ $v->nama_kategori }}
            </option>
        @endforeach
    </select>

    @error('kategori_id')
        <span style="color: red;">{{ $message }}</span>
    @enderror

    <br><br>

    <label>Instansi:</label>
    <select name="instansi_id" required>
        @foreach ($instansis as $v)
            <option
                value="{{ $v->id }}"
                {{ old('instansi_id', $laporan->instansi_id) == $v->id ? 'selected' : '' }}
            >
                {{ $v->nama_instansi }}
            </option>
        @endforeach
    </select>

    @error('instansi_id')
        <span style="color: red;">{{ $message }}</span>
    @enderror

    <br><br>

    <label>Judul:</label>
    <input
        type="text"
        name="judul"
        value="{{ old('judul', $laporan->judul) }}"
        required
    >

    @error('judul')
        <span style="color: red;">{{ $message }}</span>
    @enderror

    <br><br>

    <label>Deskripsi:</label>
    <textarea name="deskripsi" required>{{ old('deskripsi', $laporan->deskripsi) }}</textarea>

    @error('deskripsi')
        <span style="color: red;">{{ $message }}</span>
    @enderror

    <br><br>

    <label>Foto:</label>
    <input
        type="file"
        name="foto"
        accept="image/jpeg,image/png"
    >

    @if ($laporan->foto)
        <br><br>
        <img
            src="{{ asset('storage/' . $laporan->foto) }}"
            width="150"
            alt="Foto laporan"
        >
    @endif

    @error('foto')
        <br>
        <span style="color: red;">{{ $message }}</span>
    @enderror

    @if ($laporan->lampiran)
        <br><br>
        <label>Lampiran (hanya lihat):</label>
        <br>
        <img
            src="{{ asset('storage/' . $laporan->lampiran) }}"
            width="150"
            alt="Lampiran laporan"
        >
    @endif

    <br><br>

    <label>Latitude:</label>
    <input
        type="number"
        step="any"
        min="-90"
        max="90"
        name="latitude"
        value="{{ old('latitude', $laporan->latitude) }}"
        required
    >

    @error('latitude')
        <span style="color: red;">{{ $message }}</span>
    @enderror

    <br><br>

    <label>Longitude:</label>
    <input
        type="number"
        step="any"
        min="-180"
        max="180"
        name="longitude"
        value="{{ old('longitude', $laporan->longitude) }}"
        required
    >

    @error('longitude')
        <span style="color: red;">{{ $message }}</span>
    @enderror

    <br><br>

    <label>Alamat:</label>
    <textarea name="alamat">{{ old('alamat', $laporan->alamat) }}</textarea>

    @error('alamat')
        <span style="color: red;">{{ $message }}</span>
    @enderror

    <br><br>

    <label>Tingkat Prioritas:</label>
    <select name="tingkat_prioritas" required>
        @foreach (['Krisis', 'Sedang', 'Rendah'] as $p)
            <option
                value="{{ $p }}"
                {{ old('tingkat_prioritas', $laporan->tingkat_prioritas) == $p ? 'selected' : '' }}
            >
                {{ $p }}
            </option>
        @endforeach
    </select>

    @error('tingkat_prioritas')
        <span style="color: red;">{{ $message }}</span>
    @enderror

    <br><br>

    <label>Status:</label>
    <select name="status" required>
        @foreach (['Menunggu', 'Diverifikasi', 'Diproses', 'Selesai', 'Ditolak'] as $s)
            <option
                value="{{ $s }}"
                {{ old('status', $laporan->status) == $s ? 'selected' : '' }}
            >
                {{ $s }}
            </option>
        @endforeach
    </select>

    @error('status')
        <span style="color: red;">{{ $message }}</span>
    @enderror

    <br><br>

    <button type="submit">
        Update Laporan
    </button>

    <a href="{{ route('super_admin.laporan.index') }}">
        Kembali
    </a>
</form>

@endsection