<table border="1">
    <tr>
        <th>No</th>
        <th>Prioritas</th>
        <th>Waktu Respon</th>
        <th>Waktu Penyelesaian</th>
        <th>Deskripsi</th>
        <th>Status</th>
        <th>
            <a href="{{ route('sla-konfigurasi.create') }}">Tambah</a>
        </th>
    </tr>

    @forelse ($slaKonfigurasi as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->prioritas }}</td>
        <td>{{ $v->waktu_respon }}</td>
        <td>{{ $v->waktu_penyelesaian }}</td>
        <td>{{ $v->deskripsi }}</td>
        <td>{{ $v->status }}</td>
        <td>
            <a href="{{ route('sla-konfigurasi.edit', $v->id) }}">Edit</a>

            <form action="{{ route('sla-konfigurasi.destroy', $v->id) }}" method="POST" style="display:inline">
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