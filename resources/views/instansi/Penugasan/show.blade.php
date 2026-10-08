@extends('template.layout')

 @section('content')
<h2>Detail Penugasan</h2>

<p><strong>Laporan:</strong> {{ $penugasan->laporan->judul ?? '-' }}</p>
<p><strong>Kategori:</strong> {{ $penugasan->laporan->kategori->nama_kategori ?? '-' }}</p>
<p><strong>Tim Satgas:</strong> {{ $penugasan->timSatgas->nama_tim ?? '-' }}</p>
<p><strong>Petugas:</strong> {{ $penugasan->petugas->nama ?? '-' }}</p>
<p><strong>Status:</strong> {{ $penugasan->status }}</p>
<p><strong>Catatan:</strong> {{ $penugasan->catatan ?? '-' }}</p>
<p><strong>Tanggal Penugasan:</strong> {{ $penugasan->tanggal_penugasan }}</p>
<p><strong>Batas Waktu SLA:</strong> {{ $batasSla ?? '-' }}</p>
<p><strong>Sisa Waktu (menit):</strong> {{ $sisaMenit ?? '-' }}</p>
<p><strong>Status SLA:</strong> {{ $overdue ? 'Overdue' : 'Masih dalam batas waktu' }}</p>
<p><strong>Tanggal Selesai:</strong> {{ $penugasan->tanggal_selesai ?? '-' }}</p>

@if ($penugasan->status === 'selesai' && $penugasan->laporan->status === 'Diproses')
    <hr>
    <h3>Closing Report dari Petugas</h3>

    @if ($penugasan->foto_hasil)
        <img src="{{ asset('storage/' . $penugasan->foto_hasil) }}" width="300" alt="Foto hasil pekerjaan">
        <br><br>
    @endif

    <p><strong>Catatan Penyelesaian:</strong> {{ $penugasan->catatan_penyelesaian ?? '-' }}</p>

    <h4>Validasi Closing Report</h4>

    <form action="{{ route('instansi.penugasan.update', $penugasan->id) }}" method="POST" style="display:inline-block; margin-right: 10px;">
        @csrf
        @method('PUT')
        <input type="hidden" name="aksi" value="validasi">
        <button type="submit" onclick="return confirm('Yakin closing report ini valid dan laporan selesai ditangani?')">
            ✅ Validasi (Laporan Selesai)
        </button>
    </form>

    <form action="{{ route('instansi.penugasan.update', $penugasan->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('PUT')
        <input type="hidden" name="aksi" value="revisi">
        <br>
        <textarea name="catatan" placeholder="Alasan ditolak / perlu diperbaiki..." required rows="2" cols="40"></textarea>
        <br>
        <button type="submit">❌ Tolak, Kembalikan ke Petugas</button>
    </form>
@endif

<br><br>
<a href="{{ route('instansi.penugasan.index') }}">Kembali</a>
@endsection
