<table border="1">
    <tr>
        <th>No</th>
        <th>Nama Instansi</th>
        <th>Deskripsi</th>
        <th>Alamat</th>
        <th>No Telepon</th>
        <th>Email</th>
        <th>Logo</th>
        <th>Status</th>
        <th>
            <a href="{{ route('instansi.create') }}">Tambah Data</a>
        </th>
    </tr>

    @foreach ($instansis as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->nama_instansi }}</td>
        <td>{{ $v->deskripsi }}</td>
        <td>{{ $v->alamat }}</td>
        <td>{{ $v->no_telp }}</td>
        <td>{{ $v->email }}</td>
        <td>
            <img src="{{ asset('storage/' . $v->logo) }}" width="100"></td>
        <td>{{ $v->status }}</td>
        <td>
            <form action="{{ route('instansi.destroy', $v->id) }}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')

                <a href="{{ route('instansi.edit', $v->id) }}">Edit</a>

                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus instansi ini?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
