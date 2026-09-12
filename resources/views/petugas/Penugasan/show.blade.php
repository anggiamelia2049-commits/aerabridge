<h1>Detail Penugasan</h1>

<h3>{{ $penugasan->laporan->judul }}</h3>

<p>
    Deskripsi:
    {{ $penugasan->laporan->deskripsi }}
</p>

<p>
    Prioritas:
    {{ $penugasan->laporan->tingkat_prioritas }}
</p>

<p>
    Alamat:
    {{ $penugasan->laporan->alamat }}
</p>

<p>
    Status:
    {{ $penugasan->status }}
</p>

<p>
    Tanggal Penugasan:
    {{ $penugasan->tanggal_penugasan }}
</p>

<p>
    Batas SLA:
    {{ $slaDeadline }}
</p>

@if($penugasan->catatan)
    <p>
        Catatan:
        {{ $penugasan->catatan }}
    </p>
@endif

@if($penugasan->status == 'ditugaskan')

    <form action="{{ route('petugas.penugasan.update', $penugasan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="status" value="dalam_proses">

        <button type="submit">Mulai Tugas</button>
    </form>

@endif

@if($penugasan->status == 'dalam_proses')

    <a href="{{ route('petugas.penugasan.edit', $penugasan->id) }}">
        Closing Report
    </a>

@endif

<br>

<a href="{{ route('petugas.penugasan.index') }}">
    Kembali
</a>