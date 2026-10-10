@extends('template.layout')

@section('title', 'Penugasan')

@section('content')
<h2>Daftar Penugasan</h2>

@php
    $tabs = [
        'semua' => 'Semua',
        'aktif' => 'Aktif',
        'prioritas' => 'Prioritas',
        'selesai' => 'Selesai',
    ];
@endphp

<p>
    @foreach ($tabs as $key => $label)
        <a href="{{ route('petugas.penugasan.index', $key === 'semua' ? [] : ['filter' => $key]) }}"
           @if ($filter === $key) style="font-weight: bold" @endif>{{ $label }}</a>
        @if (! $loop->last) | @endif
    @endforeach
</p>

@if ($penugasan->isEmpty())
    <p>Belum ada penugasan.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Laporan</th>
                <th>Prioritas</th>
                <th>Status</th>
                <th>Tanggal Penugasan</th>
                <th>SLA</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penugasan as $item)
                <tr>
                    <td>{{ $item->laporan->judul }}</td>
                    <td>{{ $item->laporan->tingkat_prioritas }}</td>
                    <td>{{ $item->labelStatus() }}</td>
                    <td>{{ $item->tanggal_penugasan->format('d-m-Y H:i') }}</td>
                    <td @if ($item->isOverdue()) style="color: red" @endif>{{ $item->teksSla() }}</td>
                    <td>
                        <a href="{{ route('petugas.penugasan.show', $item->id) }}">Lihat Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection