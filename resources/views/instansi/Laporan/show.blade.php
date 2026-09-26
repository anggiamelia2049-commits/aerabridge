<table border="1">
    <tr>
        <th>User</th>
        <td>{{ $laporan->user->nama ?? '-' }}</td>
    </tr>

    <tr>
        <th>Kategori</th>
        <td>{{ $laporan->kategori->nama_kategori ?? '-' }}</td>
    </tr>

    <tr>
        <th>Instansi</th>
        <td>{{ $laporan->instansi->nama_instansi ?? '-' }}</td>
    </tr>

    <tr>
        <th>Judul</th>
        <td>{{ $laporan->judul }}</td>
    </tr>

    <tr>
        <th>Deskripsi</th>
        <td>{{ $laporan->deskripsi }}</td>
    </tr>

    <tr>
        <th>Foto</th>
        <td>
            @if ($laporan->foto)
                <img src="{{ asset('storage/' . $laporan->foto) }}" width="200" alt="Foto laporan">
            @elseif ($laporan->lampiran)
                <img src="{{ asset('storage/' . $laporan->lampiran) }}" width="200" alt="Lampiran laporan">
            @else
                Tidak ada foto
            @endif
        </td>
    </tr>

    <tr>
        <th>Latitude</th>
        <td>{{ $laporan->latitude }}</td>
    </tr>

    <tr>
        <th>Longitude</th>
        <td>{{ $laporan->longitude }}</td>
    </tr>

    <tr>
        <th>Alamat</th>
        <td>{{ $laporan->alamat }}</td>
    </tr>

    <tr>
        <th>Tingkat Prioritas</th>
        <td>{{ $laporan->tingkat_prioritas }}</td>
    </tr>

    <tr>
        <th>Status</th>
        <td>{{ $laporan->status }}</td>
    </tr>

    <tr>
        <th>Diverifikasi Oleh</th>
        <td>{{ $laporan->diverifikasiOleh->nama ?? '-' }}</td>
    </tr>
</table>

<br>

@if ($laporan->status === 'Menunggu')
    <form action="{{ route('instansi.laporan.verify', $laporan->id) }}" method="POST" style="display:inline;">
        {{ csrf_field() }}
        @method('PUT')
        <input type="hidden" name="status" value="Diverifikasi">
        <button type="submit">✅ Verifikasi (Valid)</button>
    </form>

    <form action="{{ route('instansi.laporan.verify', $laporan->id) }}" method="POST" style="display:inline;">
        {{ csrf_field() }}
        @method('PUT')
        <input type="hidden" name="status" value="Ditolak">
        <button type="submit" onclick="return confirm('Yakin ingin menolak laporan ini?')">❌ Tolak</button>
    </form>

    <br><br>
@endif

<a href="{{ route('instansi.laporan.index') }}">Back</a>