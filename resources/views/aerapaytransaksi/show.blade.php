<h2>Detail Transaksi Aera Pay</h2>

<table border="1" cellpadding="10">
    <tr>
        <td>ID</td>
        <td>{{ $aeraPayTransaksi->id }}</td>
    </tr>

    <tr>
        <td>User</td>
        <td>
            {{ $aeraPayTransaksi->user->name ?? $aeraPayTransaksi->user_id }}
        </td>
    </tr>

    <tr>
        <td>Laporan</td>
        <td>
            {{ $aeraPayTransaksi->laporan->judul ?? $aeraPayTransaksi->laporan_id }}
        </td>
    </tr>

    <tr>
        <td>Jenis Transaksi</td>
        <td>{{ $aeraPayTransaksi->jenis_transaksi }}</td>
    </tr>

    <tr>
        <td>Nominal</td>
        <td>{{ $aeraPayTransaksi->nominal }}</td>
    </tr>

    <tr>
        <td>Saldo Sebelum</td>
        <td>{{ $aeraPayTransaksi->saldo_sebelum }}</td>
    </tr>

    <tr>
        <td>Saldo Sesudah</td>
        <td>{{ $aeraPayTransaksi->saldo_sesudah }}</td>
    </tr>

    <tr>
        <td>Status</td>
        <td>{{ $aeraPayTransaksi->status }}</td>
    </tr>

    <tr>
        <td>Dibuat Pada</td>
        <td>{{ $aeraPayTransaksi->created_at }}</td>
    </tr>
</table>

<br>

<a href="{{ route('aera_pay_transaksi.index') }}">Kembali</a>