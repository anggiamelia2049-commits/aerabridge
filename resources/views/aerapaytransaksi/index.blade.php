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
        <th>
            <a href="{{ route('aera_pay_transaksi.create') }}">Tambah Data</a>
        </th>
    </tr>

    @foreach ($transaksis as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->user_id }}</td>
        <td>{{ $v->laporan_id }}</td>
        <td>{{ $v->jenis_transaksi }}</td>
        <td>{{ $v->nominal }}</td>
        <td>{{ $v->saldo_sebelum }}</td>
        <td>{{ $v->saldo_sesudah }}</td>
        <td>{{ $v->status  }}</td>
        <td>
            <form action="{{ route('aera_pay_transaksi.destroy', $v->id) }}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')

                <a href="{{ route('aera_pay_transaksi.edit', $v->id) }}">Edit</a>

                <button type="submit" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
