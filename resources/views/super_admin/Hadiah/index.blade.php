<table border="1">
    <tr>
        <th>No</th>
        <th>Nama Hadiah</th>
        <th>Deskripsi</th>
        <th>Poin Dibutuhkan</th>
        <th>Stok</th>
        <th>Gambar</th>
        <th>status</th>
        <th>
            <a href="{{ route('hadiah.create') }}">Tambah Data</a>
        </th>
    </tr>

    @foreach ($hadiahs as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->nama_hadiah }}</td>
        <td>{{ $v->deskripsi}}</td>
        <td>{{ $v->poin_dibutuhkan }}</td>
        <td>{{ $v->stok }}</td>
        <td>
            @if($v->gambar)
                <img src="{{ asset('storage/' . $v->gambar) }}" width="100">
            @else
                Tidak ada gambar
            @endif
        </td>
        <td>{{ $v->status }}</td>
        <td>
            <form action="{{ route('hadiah.destroy', $v->id) }}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')

                <a href="{{ route('hadiah.edit', $v->id) }}">Edit</a>

                <button type="submit" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
