@extends('template.layout')

@section('title', 'Notifikasi')

@section('content')
<h2>Notifikasi</h2>

<p>Belum dibaca: <strong>{{ $jumlahBelumDibaca }}</strong></p>

<p>
    <a href="{{ route('petugas.notifikasi.index') }}"
       @if ($filter === 'semua') style="font-weight: bold" @endif>Semua</a>
    |
    <a href="{{ route('petugas.notifikasi.index', ['filter' => 'belum_dibaca']) }}"
       @if ($filter === 'belum_dibaca') style="font-weight: bold" @endif>Belum Dibaca</a>
</p>

@if ($jumlahBelumDibaca > 0)
    <form action="{{ route('petugas.notifikasi.update', 'semua') }}" method="POST">
        @csrf
        @method('PUT')
        <button type="submit">Tandai Semua Dibaca</button>
    </form>
    <br>
@endif

@if ($notifikasis->isEmpty())
    <p>{{ $filter === 'belum_dibaca' ? 'Semua notifikasi sudah dibaca.' : 'Belum ada notifikasi.' }}</p>
@else
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Judul</th>
                <th>Tipe</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notifikasis as $v)
                <tr>
                    <td>{{ $v->dibaca ? 'Sudah dibaca' : 'Baru' }}</td>
                    <td @if (! $v->dibaca) style="font-weight: bold" @endif>{{ $v->judul }}</td>
                    <td>{{ $v->tipe }}</td>
                    <td>{{ $v->created_at->format('d-m-Y H:i') }}</td>
                    <td>
                        <a href="{{ route('petugas.notifikasi.show', $v->id) }}">Buka</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection