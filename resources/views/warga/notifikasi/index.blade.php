<h2>Notifikasi</h2>

<p>Belum dibaca: <strong>{{ $jumlahBelumDibaca }}</strong></p>

<a href="{{ url()->current() }}">Semua</a>
<a href="{{ url()->current() }}?filter=belum_dibaca">Belum Dibaca</a>

@if ($jumlahBelumDibaca > 0)
    <form action="{{ route('warga.notifikasi.update', 'semua') }}" method="POST" style="display:inline">
        {{ csrf_field() }}
        @method('PUT')
        <button type="submit">Tandai Semua Dibaca</button>
    </form>
@endif

<hr>

@if ($notifikasis->isEmpty())
    <p>Belum ada notifikasi.</p>
@else
    <table border="1" cellpadding="8">
        <tr>
            <th>Status</th>
            <th>Judul</th>
            <th>Tipe</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>

        @foreach ($notifikasis as $v)
            <tr>
                <td>{{ $v->dibaca ? 'Sudah dibaca' : 'Baru' }}</td>
                <td>{{ $v->judul }}</td>
                <td>{{ $v->tipe }}</td>
                <td>{{ $v->created_at->format('d-m-Y H:i') }}</td>
                <td>
                    <a href="{{ route('warga.notifikasi.show', $v->id) }}">Buka</a>
                </td>
            </tr>
        @endforeach
    </table>
@endif