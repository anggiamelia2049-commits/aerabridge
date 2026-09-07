<table border="1">
    <tr>
        <th>No</th>
        <th>Judul</th>
        <th>Thumbnail</th>
        <th>Isi</th>
        <th>Kategori</th>
        <th>Penulis</th>
        <th>Status</th>
        <th>Aksi</th>
        <th>
            <a href="{{ route('KontenEdukasi.create') }}">Tambah Konten</a>
        </th>
    </tr>

    @foreach ($konten as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->judul }}</td>

        <td>
            @if ($v->thumbnail)
                <img src="{{ asset('storage/' . $v->thumbnail) }}" width="100">
            @else
                Tidak ada foto
            @endif
        </td>

        <td>{{ $v->isi }}</td>
        <td>{{ $v->kategori }}</td>
        <td>{{ $v->penulis->name ?? '-' }}</td>
        <td>{{ $v->status }}</td>

        <td>
            <a href="{{ route('KontenEdukasi.show', $v->id) }}">Show</a>

            <a href="{{ route('KontenEdukasi.edit', $v->id) }}">Edit</a>

            <form action="{{ route('KontenEdukasi.destroy', $v->id) }}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')

                <button type="submit" onclick="return confirm('Are you sure you want to delete this content?')">
                    Delete
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>