<h2>Detail Penugasan</h2>

<p><strong>Laporan:</strong> {{ $penugasan->laporan->judul ?? '-' }}</p>
<p><strong>Kategori:</strong> {{ $penugasan->laporan->kategoriKerusakan->nama ?? '-' }}</p>
<p><strong>Tim Satgas:</strong> {{ $penugasan->timSatgas->nama_tim ?? '-' }}</p>
<p><strong>Petugas:</strong> {{ $penugasan->petugas->nama ?? '-' }}</p>
<p><strong>Status:</strong> {{ $penugasan->status }}</p>
<p><strong>Catatan:</strong> {{ $penugasan->catatan ?? '-' }}</p>

<p><strong>Tanggal Penugasan:</strong> {{ $penugasan->tanggal_penugasan }}</p>
<p><strong>Batas Waktu SLA:</strong> {{ $batasSla }}</p>
<p><strong>Sisa Waktu (menit):</strong> {{ $sisaMenit }}</p>
<p><strong>Status SLA:</strong> {{ $overdue ? 'OVERDUE' : 'Masih dalam batas waktu' }}</p>

<p><strong>Tanggal Selesai:</strong> {{ $penugasan->tanggal_selesai ?? 'Belum selesai' }}</p>

<a href="{{ route('instansi.penugasan.index') }}">Kembali</a>