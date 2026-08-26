<table border="1">
    <tr>
        <th>No</th>
        <th>Nama Kategori</th>
        <th>Icon</th>
        <th>Deskripsi</th>
        <th>Warna Marker</th>
        <th>Status</th>
        <th>
            <a href="{{ route('KategoriKerusakan.create') }}">Tambah Kategori</a>
        </th>
    </tr>

    @foreach ($kategoris as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->nama_kategori }}</td>
        <td>{{ $v->icon }}</td>
        <td>{{ $v->deskripsi }}</td>
        <td>{{ $v->warna_marker }}</td>
        <td>{{ $v->status }}</td>
        <td>
            <form action="{{ route('KategoriKerusakan.destroy', $v->id) }}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')

                <a href="{{ route('KategoriKerusakan.edit', $v->id) }}">Edit</a>

                <button type="submit" onclick="return confirm('Are you sure you want to delete this category?')">
                    Delete
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>