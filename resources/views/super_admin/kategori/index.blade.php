<table border="1">
    <tr>
        <th>No</th>
        <th>Nama Kategori</th>
        <th>Icon</th>
        <th>Deskripsi</th>
        <th>Warna Marker</th>
        <th>Status</th>
        <th>
            <a href="{{ route('kategori.create') }}">Tambah Data</a>
        </th>
    </tr>

    @foreach ($kategoris as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>

        <td>
            {{ $v->nama_kategori }}
        </td>

        <td>
            {{ $v->icon }}
        </td>

        <td>
            {{ $v->deskripsi }}
        </td>

        <td>
            {{ $v->warna_marker }}
        </td>

        <td>
            {{ $v->status }}
        </td>

        <td>
            <a href="{{ route('kategori.show', $v->id) }}">
                Detail
            </a>

            <a href="{{ route('kategori.edit', $v->id) }}">
                Edit
            </a>

            <form
                action="{{ route('kategori.destroy', $v->id) }}"
                method="POST"
                style="display: inline;"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                >
                    Delete
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>