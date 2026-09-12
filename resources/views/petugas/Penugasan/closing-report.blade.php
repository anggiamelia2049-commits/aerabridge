<h1>Closing Report</h1>

<p>{{ $penugasan->laporan->judul }}</p>

<form action="{{ route('petugas.penugasan.update', $penugasan->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="status" value="selesai">

    <label>Foto Hasil Perbaikan</label>
    <input type="file" name="foto_hasil">

    <br><br>

    <button type="submit">Kirim Closing Report</button>
</form>

<a href="{{ route('petugas.penugasan.show', $penugasan->id) }}">
    Kembali
</a>