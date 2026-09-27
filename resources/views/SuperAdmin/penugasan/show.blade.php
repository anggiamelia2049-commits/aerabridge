<table border="1" cellpadding="10">
    <tr>
        <th>Laporan</th>
        <td>{{ $penugasan->laporan->judul ?? '-' }}</td>
    </tr>

    <tr>
        <th>Kategori Laporan</th>
        <td>{{ $penugasan->laporan->kategori->nama_kategori ?? '-' }}</td>
    </tr>

    <tr>
        <th>Tim Satgas</th>
        <td>{{ $penugasan->timSatgas->nama_tim ?? '-' }}</td>
    </tr>

    <tr>
        <th>Petugas</th>
        <td>{{ $penugasan->petugas->nama ?? '-' }}</td>
    </tr>

    <tr>
        <th>Status</th>
        <td>{{ $penugasan->status }}</td>
    </tr>

    <tr>
        <th>Tanggal Penugasan</th>
        <td>{{ $penugasan->tanggal_penugasan }}</td>
    </tr>

    <tr>
        <th>Tanggal Selesai</th>
        <td>{{ $penugasan->tanggal_selesai ?? '-' }}</td>
    </tr>

    <tr>
        <th>Catatan</th>
        <td>{{ $penugasan->catatan ?? '-' }}</td>
    </tr>
</table>

<br>

<a href="{{ route('super_admin.penugasan.index') }}">Kembali</a>