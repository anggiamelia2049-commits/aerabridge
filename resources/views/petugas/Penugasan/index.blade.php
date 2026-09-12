<h1>Daftar Penugasan</h1>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

@if(session('error'))
    <p>{{ session('error') }}</p>
@endif

<a href="{{ url()->current() }}">Semua</a>
<a href="{{ url()->current() }}?filter=aktif">Aktif</a>
<a href="{{ url()->current() }}?filter=prioritas">Prioritas</a>
<a href="{{ url()->current() }}?filter=selesai">Selesai</a>

<hr>

@foreach($penugasan as $item)

    <h3>{{ $item->laporan->judul }}</h3>

    <p>Prioritas: {{ $item->laporan->tingkat_prioritas }}</p>

    <p>Status: {{ $item->status }}</p>

    <p>Tanggal: {{ $item->tanggal_penugasan }}</p>

    <a href="{{ route('petugas.penugasans.show', $item->id) }}">
        Lihat Detail
    </a>

    <hr>

@endforeach