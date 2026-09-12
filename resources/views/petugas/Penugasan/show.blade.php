<!DOCTYPE html>
<html>
<head>
    <title>Detail Tugas</title>
</head>
<body>

    <p><a href="{{ route('petugas.penugasan.index') }}">&larr; Kembali</a></p>

    <h1>Detail Tugas</h1>

    @if (session('success'))
        <p><strong>{{ session('success') }}</strong></p>
    @endif
    @if (session('error'))
        <p><strong>{{ session('error') }}</strong></p>
    @endif

    <table border="1" cellpadding="5">
        <tr>
            <th>Kategori Kerusakan</th>
            <td>{{ $penugasan->laporan->kategori_kerusakan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Lokasi</th>
            <td>{{ $penugasan->laporan->lokasi ?? '-' }}</td>
        </tr>
        <tr>
            <th>Prioritas</th>
            <td>{{ $penugasan->laporan->prioritas ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status Tugas</th>
            <td>{{ $penugasan->status }}</td>
        </tr>
        <tr>
            <th>Catatan Teknis</th>
            <td>{{ $penugasan->catatan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Batas Waktu SLA</th>
            <td>{{ $slaDeadline ? $slaDeadline->format('d-m-Y H:i') : '-' }}</td>
        </tr>
    </table>

    @if ($penugasan->laporan->foto ?? false)
        <p>
            <strong>Foto Laporan:</strong><br>
            <img src="{{ asset('storage/' . $penugasan->laporan->foto) }}" width="300">
        </p>
    @endif

    <p>
        @if ($penugasan->laporan->latitude ?? false)
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $penugasan->laporan->latitude }},{{ $penugasan->laporan->longitude }}" target="_blank">
                Buka Rute
            </a>
        @endif
    </p>

    @if ($penugasan->status === 'ditugaskan')
        <form action="{{ route('petugas.penugasan.update', $penugasan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="aksi" value="mulai_proses">
            <button type="submit">Mulai Proses</button>
        </form>
    @endif

    @if (in_array($penugasan->status, ['ditugaskan', 'dalam_proses']))
        <p>
            <a href="{{ route('petugas.penugasan.edit', $penugasan->id) }}">Kirim Closing Report</a>
        </p>
    @endif

</body>
</html>