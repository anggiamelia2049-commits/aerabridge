<table border="1">
    <tr>
        <th>No</th>
        <th>Laporan</th>
        <th>Tim Petugas</th>
        <th>Petugas</th>
        <th>Status</th>
        <th>Tanggal Penugasan</th>
        <th>Tanggal Selesai</th>
        <th>Catatan</th>
        <th>
            <a href="{{ route('instansi.penugasan.create') }}">Tambah Penugasan</a>
        </th>
    </tr>

    @forelse ($penugasan as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->laporan->judul ?? '-' }}</td>
        <td>{{ $v->timSatgas->nama_tim ?? '-' }}</td>
        <td>{{ $v->petugas->nama ?? '-' }}</td>
        <td>{{ $v->status }}</td>
        <td>{{ $v->tanggal_penugasan }}</td>
        <td>{{ $v->tanggal_selesai }}</td>
        <td>{{ $v->catatan }}</td>
        <td>
            <a href="{{ route('instansi.penugasan.show', $v->id) }}">Detail</a>
            <a href="{{ route('instansi.penugasan.edit', $v->id) }}">Alihkan</a>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="9">Belum ada data penugasan.</td>
    </tr>
    @endforelse
</table>