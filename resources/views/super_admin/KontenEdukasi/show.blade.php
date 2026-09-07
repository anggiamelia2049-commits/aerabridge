<table border="1">
    <tr>
        <th>Judul</th>
        <td>{{ $konten->judul }}</td>
    </tr>

    <tr>
        <th>Thumbnail</th>
        <td>
            @if ($konten->thumbnail)
                <img src="{{ asset('storage/' . $konten->thumbnail) }}" width="200">
            @else
                Tidak ada foto
            @endif
        </td>
    </tr>

    <tr>
        <th>Isi</th>
        <td>{{ $konten->isi }}</td>
    </tr>

    <tr>
        <th>Kategori</th>
        <td>{{ $konten->kategori }}</td>
    </tr>

    <tr>
        <th>Penulis</th>
        <td>{{ $konten->penulis->name ?? '-' }}</td>
    </tr>

    <tr>
        <th>Status</th>
        <td>{{ $konten->status }}</td>
    </tr>
</table>

<br>

<a href="{{ route('KontenEdukasi.index') }}">Back</a>
<a href="{{ route('KontenEdukasi.edit', $konten->id) }}">Edit</a>