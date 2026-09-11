<table border="1">
    <tr>
        <th>No</th>
        <th>User</th>
        <th>Kategori</th>
        <th>Instansi</th>
        <th>Judul</th>
        <th>Deskripsi</th>
        <th>Foto</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Alamat</th>
        <th>Prioritas</th>
        <th>Status</th>
        <th>Verifikasi Oleh</th>
        <th><a href="{{ route('laporan.create') }}">
    Tambah Laporan
</a></th>
    </tr>

    @foreach ($laporan as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>

            <td>
                {{ optional($v->user)->name ?? '-' }}
            </td>

            <td>
                {{ optional($v->kategori)->nama_kategori ?? '-' }}
            </td>

            <td>
                {{ optional($v->instansi)->nama_instansi ?? '-' }}
            </td>

            <td>{{ $v->judul }}</td>

            <td>{{ $v->deskripsi }}</td>

            <td>
                @if ($v->foto)
                    <img
                        src="{{ asset('storage/' . $v->foto) }}"
                        width="100"
                        alt="Foto laporan"
                    >
                @else
                    Tidak ada foto
                @endif
            </td>

            <td>{{ $v->latitude }}</td>

            <td>{{ $v->longitude }}</td>

            <td>{{ $v->alamat ?? '-' }}</td>

            <td>{{ $v->tingkat_prioritas }}</td>

            <td>{{ $v->status }}</td>

            <td>
                {{ optional($v->diverifikasiOleh)->name ?? '-' }}
            </td>

            <td>
                <a href="{{ route('laporan.show', $v->id) }}">
                    Show
                </a>

                <a href="{{ route('laporan.edit', $v->id) }}">
                    Edit
                </a>

                <form
                    action="{{ route('laporan.destroy', $v->id) }}"
                    method="POST"
                    style="display: inline;"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Apakah kamu yakin ingin menghapus laporan ini?')"
                    >
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
