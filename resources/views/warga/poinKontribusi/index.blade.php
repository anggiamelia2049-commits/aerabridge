<h2>Poin Kontribusi</h2>

<p>Total Poin Saya: <strong>{{ $totalPoin }}</strong> Poin</p>

<hr>

<h3>Riwayat Poin</h3>

@if ($riwayatPoin->isEmpty())
    <p>Belum ada riwayat poin kontribusi.</p>
@else
    <table border="1" cellpadding="8">
        <tr>
            <th>Tanggal</th>
            <th>Jenis Aktivitas</th>
            <th>Laporan Terkait</th>
            <th>Poin</th>
            <th>Keterangan</th>
        </tr>

        @foreach ($riwayatPoin as $v)
            <tr>
                <td>{{ $v->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ $v->jenis_aktivitas }}</td>
                <td>{{ $v->laporan->judul ?? '-' }}</td>
                <td>{{ $v->poin > 0 ? '+' . $v->poin : $v->poin }}</td>
                <td>{{ $v->keterangan ?? '-' }}</td>
            </tr>
        @endforeach
    </table>
@endif