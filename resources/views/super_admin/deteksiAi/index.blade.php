<table border="1">
    <tr>
        <th>No</th>
        <th>Laporan</th>
        <th>Jenis Objek</th>
        <th>Confidence</th>
        <th>Tingkat Kerusakan</th>
        <th>Estimasi Prioritas</th>
        <th>Hasil Validasi</th>
        <th>Respon LLM</th>
        <th>
            <a href="{{ route('deteksi_ai.create') }}">Tambah Data</a>
        </th>
    </tr>

    @foreach ($deteksiAIs as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->laporan_id }}</td>
        <td>{{ $v->jenis_objek }}</td>
        <td>{{ $v->confidence }}</td>
        <td>{{ $v->tingkat_kerusakan }}</td>
        <td>{{ $v->estimasi_prioritas }}</td>
        <td>{{ $v->hasil_validasi }}</td>
        <td>{{ $v->response_llm }}</td>
        <td>
            <form action="{{ route('deteksi_ai.destroy', $v->id) }}" method="POST">
                {{ csrf_field() }}
                @method('DELETE')

                 <a href="{{ route('deteksi_ai.show', $v->id) }}">Detail</a>
                <a href="{{ route('deteksi_ai.edit', $v->id) }}">Edit</a>

                <button type="submit" onclick="return confirm('Are you sure you want to delete this data?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
