@extends('template.layout')

 @section('content')
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
        <th>Aksi</th>
    </tr>

    @foreach ($deteksiAIs as $v)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $v->laporan->judul ?? '-' }}</td>
        <td>{{ $v->jenis_objek }}</td>
        <td>{{ $v->confidence }}</td>
        <td>{{ $v->tingkat_kerusakan }}</td>
        <td>{{ $v->estimasi_prioritas }}</td>
        <td>{{ $v->hasil_validasi }}</td>
        <td>{{ $v->response_llm }}</td>
        <td>
            <a href="{{ route('instansi.deteksi-ai.show', $v->id) }}">Detail</a>
        </td>
    </tr>
    @endforeach
</table>
@endsection
