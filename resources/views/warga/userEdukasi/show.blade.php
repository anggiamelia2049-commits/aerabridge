<h2>{{ $konten->judul }}</h2>

<p><small>Kategori: {{ $konten->kategori }}</small></p>

@if ($konten->thumbnail)
    <img src="{{ asset('storage/' . $konten->thumbnail) }}" width="300">
@endif

<div>
    {{ $konten->isi }}
</div>

<hr>

@if ($progress->status === 'selesai')
    <p>
        Kamu sudah menyelesaikan konten ini pada
        {{ $progress->selesai_pada ? \Carbon\Carbon::parse($progress->selesai_pada)->format('d-m-Y H:i') : '-' }}.
    </p>
@else
    <form action="{{ route('warga.user-edukasi.selesai', $konten->id) }}" method="POST">
        {{ csrf_field() }}
        @method('PUT')

        <button type="submit">Tandai Selesai (+10 Poin)</button>
    </form>
@endif

<br>

<a href="{{ route('warga.user-edukasi.index') }}">Kembali</a>