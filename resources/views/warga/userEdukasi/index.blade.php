<table border="1">
    <tr>
        <th>No</th>
        <th>User</th>
        <th>Konten</th>
        <th>Status</th>
        <th>Progress</th>
        <th>Selesai Pada</th>
        <th>
            <a href="{{ route('user-edukasi-progress.create') }}">Tambah</a>
        </th>
    </tr>

    @forelse ($progress as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->user_id->name ?? '-' }}</td>
        <td>{{ $v->konten_id->judul ?? '-' }}</td>
        <td>{{ $v->status }}</td>
        <td>{{ $v->progress }}%</td>
        <td>{{ $v->selesai_pada ?? '-' }}</td>
        <td>
            <a href="{{ route('user-edukasi-progress.edit', $v->id) }}">Edit</a>

            <form action="{{ route('user-edukasi-progress.destroy', $v->id) }}" method="POST" style="display:inline">
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