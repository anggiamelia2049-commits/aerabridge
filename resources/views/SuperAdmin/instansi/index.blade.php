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
                @if ($v->logo)
                    <img
                        src="{{ asset('storage/' . $v->logo) }}"
                        alt="Logo {{ $v->nama_instansi }}"
                        width="100"
                        height="100"
                        style="object-fit: contain;"
                    >
                @else
                    Tidak ada logo
                @endif
            </td>

            <td>{{ $v->status }}</td>

            <td>
                <a href="{{ route('instansi.show', $v->id) }}">
                    Detail
                </a>

                <a href="{{ route('instansi.edit', $v->id) }}">
                    Edit
                </a>

                <form
                    action="{{ route('instansi.destroy', $v->id) }}"
                    method="POST"
                    style="display: inline;"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus instansi ini?')"
                    >
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</table>