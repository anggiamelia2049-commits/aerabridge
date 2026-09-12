<!DOCTYPE html>
<html>
<head>
    <title>Daftar Tugas Saya</title>
</head>
<body>

    <h1>Daftar Tugas Saya</h1>

    @if (session('success'))
        <p><strong>{{ session('success') }}</strong></p>
    @endif
    @if (session('error'))
        <p><strong>{{ session('error') }}</strong></p>
    @endif

    <p>
        <a href="{{ route('petugas.penugasan.index') }}">Semua</a> |
        <a href="{{ route('petugas.penugasan.index', ['filter' => 'aktif']) }}">Aktif</a> |
        <a href="{{ route('petugas.penugasan.index', ['filter' => 'prioritas']) }}">Prioritas</a> |
        <a href="{{ route('petugas.penugasan.index', ['filter' => 'selesai']) }}">Selesai</a>
    </p>

    <table border="1" cellpadding="5">
        <tr>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Prioritas</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        @forelse ($penugasan as $tugas)
            <tr>
                <td>{{ $tugas->laporan->kategori_kerusakan ?? '-' }}</td>
                <td>{{ $tugas->laporan->lokasi ?? '-' }}</td>
                <td>{{ $tugas->laporan->prioritas ?? '-' }}</td>
                <td>{{ $tugas->status }}</td>
                <td><a href="{{ route('petugas.penugasan.show', $tugas->id) }}">Lihat Detail</a></td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Belum ada tugas.</td>
            </tr>
        @endforelse
    </table>

</body>
</html>