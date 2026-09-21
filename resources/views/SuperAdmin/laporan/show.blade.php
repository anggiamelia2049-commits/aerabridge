<table border="1">
    <tr>
        <th>User</th>
        <td>{{ $laporan->user->nama ?? '-' }}</td>
    </tr>

    <tr>
        <th>Kategori</th>
        <td>{{ $laporan->kategori->nama_kategori ?? '-' }}</td>
    </tr>

    <tr>
        <th>Instansi</th>
        <td>{{ $laporan->instansi->nama_instansi ?? '-' }}</td>
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
        <th>Foto</th>
        <td>
            @if ($laporan->foto)
                <img src="{{ asset('storage/' . $laporan->foto) }}" width="200">
            @elseif ($laporan->lampiran)
                <img src="{{ asset('storage/' . $laporan->lampiran) }}" width="200">
            @else
                Tidak ada foto
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
        <td>{{ $laporan->alamat }}</td>
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
        <td>{{ $laporan->diverifikasiOleh->nama ?? '-' }}</td>
    </tr>
</table>

<br>

<a href="{{ route('super_admin.laporan.index') }}">Back</a>

<a href="{{ route('super_admin.laporan.edit', $laporan->id) }}">Edit</a>