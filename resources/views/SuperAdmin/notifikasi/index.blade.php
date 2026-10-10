@extends('template.layout')

@section('title', 'Notifikasi')

@section('content')

<h2>Notifikasi (Semua User)</h2>

<a href="{{ route('super_admin.notifikasi.index') }}">Semua</a>
<a href="{{ route('super_admin.notifikasi.index', ['filter' => 'belum_dibaca']) }}">Belum Dibaca</a>

<hr>

@if ($notifikasis->isEmpty())
    <p>Belum ada notifikasi.</p>
@else
    <table border="1" cellpadding="8">
        <tr>
            <th>Status</th>
            <th>User Penerima</th>
            <th>Judul</th>
            <th>Tipe</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>

        @foreach ($notifikasis as $v)
            <tr>
                <td>{{ $v->dibaca ? 'Sudah dibaca' : 'Baru' }}</td>
                <td>{{ optional($v->user)->name ?? '-' }}</td>
                <td>{{ $v->judul }}</td>
                <td>{{ $v->tipe }}</td>
                <td>{{ $v->created_at->format('d-m-Y H:i') }}</td>
                <td>
                    <a href="{{ route('super_admin.notifikasi.show', $v->id) }}">Detail</a>
                </td>
            </tr>
        @endforeach
    </table>

    <div>
        {{ $notifikasis->links() }}
    </div>
@endif

@endsection