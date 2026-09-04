<table border="1">
    <tr>
        <th>No</th>
        <th>User</th>
        <th>Kategori</th>
        <th>Instansi</th>
        <th>Judul</th>
        <th>Deskripsi</th>
        <th>Foto</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Alamat</th>
        <th>Prioritas</th>
        <th>Status</th>
        <th>Verifikasi Oleh</th>
        <th>Aksi</th>
    </tr>

    @foreach ($laporan as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->user->name ?? '-' }}</td>
        <td>{{ $v->kategori->nama_kategori ?? '-' }}</td>
        <td>{{ $v->instansi->nama_instansi ?? '-' }}</td>
        <td>{{ $v->judul }}</td>
        <td>{{ $v->deskripsi }}</td>

        <td>
            @if ($v->foto)
                <img src="{{ asset('storage/' . $v->foto) }}" width="100">
            @else
                Tidak ada foto
            @endif
        </td>

        <td>{{ $v->latitude }}</td>
        <td>{{ $v->longitude }}</td>
        <td>{{ $v->alamat }}</td>
        <td>{{ $v->tingkat_prioritas }}</td>
        <td>{{ $v->status }}</td>
        <td>{{ $v->diverifikasiOleh->name ?? '-' }}</td>

        <td>
            <a href="{{ route('Laporan.show', $v->id) }}">Show</a>

            <a href="{{ route('Laporan.edit', $v->id) }}">Edit</a>

            <form action="{{ route('Laporan.destroy', $v->id) }}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')

                <button type="submit" onclick="return confirm('Are you sure you want to delete this report?')">
                    Delete
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

<br>

<a href="{{ route('Laporan.create') }}">Tambah Laporan</a>