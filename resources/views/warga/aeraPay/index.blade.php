<h2>AERA Pay</h2>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

@if (session('error'))
    <p>{{ session('error') }}</p>
@endif

<p>Total Poin Saya: <strong>{{ $totalPoin }}</strong> Poin</p>
<p>Saldo AERA Pay (Simulasi): <strong>Rp{{ number_format($saldoSaatIni) }}</strong></p>

<a href="{{ route('warga.aeraPay.create') }}">Tukar Poin ke Saldo</a>

<hr>

<h3>Riwayat Transaksi</h3>

@if ($transaksis->isEmpty())
    <p>Belum ada riwayat transaksi AERA Pay.</p>
@else
    <table border="1" cellpadding="8">
        <tr>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Nominal</th>
            <th>Saldo Sesudah</th>
            <th>Status</th>
            <th>Detail</th>
        </tr>

        @foreach ($transaksis as $v)
            <tr>
                <td>{{ $v->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ $v->jenis_transaksi }}</td>
                <td>Rp{{ number_format($v->nominal) }}</td>
                <td>Rp{{ number_format($v->saldo_sesudah) }}</td>
                <td>{{ $v->status }}</td>
                <td>
                    <a href="{{ route('warga.aeraPay.show', $v->id) }}">Lihat</a>
                </td>
            </tr>
        @endforeach
    </table>
@endif