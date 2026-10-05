<h2>{{ $notifikasi->judul }}</h2>

<p><small>{{ $notifikasi->tipe }} &middot; {{ $notifikasi->created_at->format('d-m-Y H:i') }}</small></p>

<p>{{ $notifikasi->isi }}</p>

@if ($notifikasi->laporan)
    <p>
        Terkait laporan:
        <strong>{{ $notifikasi->laporan->judul }}</strong>
    </p>
    <p>
        <a href="{{ route('instansi.laporan.show', $notifikasi->laporan->id) }}">Lihat Laporan</a>
    </p>
@endif

<br>

<a href="{{ route('instansi.notifikasi.index') }}">Kembali</a>