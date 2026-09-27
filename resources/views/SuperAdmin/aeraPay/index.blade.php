<table border="1">
    <tr>
        <th>No</th>
        <th>User</th>
        <th>Laporan</th>
        <th>Jenis Transaksi</th>
        <th>Nominal</th>
        <th>Saldo Sebelum</th>
        <th>Saldo Sesudah</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    @foreach ($transaksis as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $v->user->nama ?? '-' }}</td>
            <td>{{ $v->laporan->judul ?? '-' }}</td>
            <td>{{ $v->jenis_transaksi }}</td>
            <td>{{ $v->nominal }}</td>
            <td>{{ $v->saldo_sebelum }}</td>
            <td>{{ $v->saldo_sesudah }}</td>
            <td>{{ $v->status }}</td>
            <td>
                    <a href="{{ route('super_admin.aeraPay.show', $v->id) }}">Detail</a>
            </td>
        </tr>
    @endforeach
</table>
