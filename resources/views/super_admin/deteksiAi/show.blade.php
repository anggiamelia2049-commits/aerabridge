<h2>Detail Deteksi AI</h2>

<table border="1" cellpadding="10">
    <tr>
        <td>ID</td>
        <td>{{ $deteksiAi->id }}</td>
    </tr>

    <tr>
        <td>Laporan</td>
        <td>
            {{ $deteksiAi->laporan->judul ?? $deteksiAi->laporan_id }}
        </td>
    </tr>

    <tr>
        <td>Jenis Objek</td>
        <td>{{ $deteksiAi->jenis_objek }}</td>
    </tr>

    <tr>
        <td>Confidence</td>
        <td>{{ $deteksiAi->confidence }}</td>
    </tr>

    <tr>
        <td>Tingkat Kerusakan</td>
        <td>{{ $deteksiAi->tingkat_kerusakan }}</td>
    </tr>

    <tr>
        <td>Estimasi Prioritas</td>
        <td>{{ $deteksiAi->estimasi_prioritas }}</td>
    </tr>

    <tr>
        <td>Hasil Validasi</td>
        <td>{{ $deteksiAi->hasil_validasi }}</td>
    </tr>

    <tr>
        <td>Response LLM</td>
        <td>
            <textarea rows="8" cols="80" readonly>{{ $deteksiAi->response_llm }}</textarea>
        </td>
    </tr>

    <tr>
        <td>Dibuat Pada</td>
        <td>{{ $deteksiAi->created_at }}</td>
    </tr>
</table>

<br>

<a href="{{ route('deteksi_ai.index') }}">Kembali</a>