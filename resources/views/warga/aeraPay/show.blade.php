<h2>Detail Transaksi AERA Pay</h2>

<table border="1" cellpadding="10">
    <tr>
        <td>Tanggal</td>
        <td>{{ $transaksi->created_at->format('d-m-Y H:i') }}</td>
    </tr>

    <tr>
        <td>Jenis Transaksi</td>
        <td>{{ $transaksi->jenis_transaksi }}</td>
    </tr>

    <tr>
        <td>Nominal</td>
        <td>Rp{{ number_format($transaksi->nominal) }}</td>
    </tr>

    <tr>
        <td>Saldo Sebelum</td>
        <td>Rp{{ number_format($transaksi->saldo_sebelum) }}</td>
    </tr>

    <tr>
        <td>Saldo Sesudah</td>
        <td>Rp{{ number_format($transaksi->saldo_sesudah) }}</td>
    </tr>

    <tr>
        <td>Status</td>
        <td>{{ $transaksi->status }}</td>
    </tr>

    @if ($transaksi->laporan)
        <tr>
            <td>Laporan Terkait</td>
            <td>{{ $transaksi->laporan->judul }}</td>
        </tr>
    @endif
</table>

<br>

<a href="{{ route('warga.aeraPay.index') }}">Kembali</a>