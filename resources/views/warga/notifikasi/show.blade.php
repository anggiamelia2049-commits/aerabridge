<h2>{{ $notifikasi->judul }}</h2>

<p><small>{{ $notifikasi->tipe }} &middot; {{ $notifikasi->created_at->format('d-m-Y H:i') }}</small></p>

<p>{{ $notifikasi->isi }}</p>

@if ($notifikasi->laporan)
    <p>
        Terkait laporan:
        <strong>{{ $notifikasi->laporan->judul }}</strong>
    </p>
@endif

<br>

<a href="{{ route('warga.notifikasi.index') }}">Kembali</a>