<table border="1">
    <tr>
        <th>No</th>
        <th>Instansi</th>
        <th>Nama Tim Satuan Tugas</th>
        <th>Ketua</th>
        <th>Jumlah Anggota</th>
        <th>Kontak</th>
        <th>Status</th>
        <th>
            <a href="{{ route('tim-satgas.create') }}">Tambah</a>
        </th>
    </tr>

    @forelse ($timSatgas as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->instansi_id->nama_instansi ?? '-' }}</td>
        <td>{{ $v->nama_tim }}</td>
        <td>{{ $v->ketua }}</td>
        <td>{{ $v->jumlah_anggota }}</td>
        <td>{{ $v->kontak }}</td>
        <td>{{ $v->status }}</td>
        <td>
            <a href="{{ route('tim-satgas.edit', $v->id) }}">Edit</a>

            <form action="{{ route('tim-satgas.destroy', $v->id) }}" method="POST" style="display:inline">
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
        <td colspan="9">Belum ada data.</td>
    </tr>
    @endforelse
</table>