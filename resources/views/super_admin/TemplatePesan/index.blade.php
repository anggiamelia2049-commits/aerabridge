<table border="1">
    <tr>
        <th>No</th>
        <th>Kode Template</th>
        <th>Judul</th>
        <th>Isi Pesan</th>
        <th>Kategori</th>
        <th>Status</th>
        <th>
            <a href="{{ route('template-pesan.create') }}">Tambah</a>
        </th>
    </tr>

    @forelse ($templatePesan as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->kode_template }}</td>
        <td>{{ $v->judul }}</td>
        <td>{{ $v->isi_pesan }}</td>
        <td>{{ $v->kategori }}</td>
        <td>{{ $v->status }}</td>
        <td>
            <a href="{{ route('template-pesan.edit', $v->id) }}">Edit</a>

            <form action="{{ route('template-pesan.destroy', $v->id) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to delete this?')">
                    Delete
                </button>
            </form>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7">Belum ada data.</td>
    </tr>
    @endforelse
</table>