<!DOCTYPE html>
<html>
<head>
    <title>Closing Report</title>
</head>
<body>

    <p><a href="{{ route('petugas.penugasan.show', $penugasan->id) }}">&larr; Kembali ke Detail Tugas</a></p>

    <h1>Kirim Closing Report</h1>

    <p>{{ $penugasan->laporan->kategori_kerusakan ?? '-' }} - {{ $penugasan->laporan->lokasi ?? '-' }}</p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('petugas.penugasan.update', $penugasan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="aksi" value="closing_report">

        <p>
            <label>Foto Hasil Perbaikan:</label><br>
            <input type="file" name="foto_hasil" accept="image/*" required>
        </p>

        <p>
            <label>Catatan Penyelesaian:</label><br>
            <textarea name="catatan_penyelesaian" rows="4" cols="40">{{ old('catatan_penyelesaian') }}</textarea>
        </p>

        <button type="submit">Kirim & Tandai Selesai</button>
    </form>

</body>
</html>