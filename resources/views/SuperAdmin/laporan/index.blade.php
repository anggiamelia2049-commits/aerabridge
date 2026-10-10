@extends('template.layout')

@section('title', 'Data Laporan')

@section('content')

@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<table border="1">
    <tr>
        <th>No</th>
        <th>Kode Lacak</th>
        <th>Pelapor</th>
        <th>Jenis</th>
        <th>Kategori</th>
        <th>Instansi</th>
        <th>Judul</th>
        <th>Tanggal Kejadian</th>
        <th>Kecamatan</th>
        <th>Foto</th>
        <th>Prioritas</th>
        <th>Status</th>
        <th>Verifikasi Oleh</th>
        <th>Aksi</th>
    </tr>

    @forelse ($laporan as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>

            <td>{{ $v->kode_lacak ?? '-' }}</td>

            <td>{{ $v->nama_pelapor }}</td>

            <td>{{ $v->jenis_laporan ?? '-' }}</td>

            <td>
                {{ optional($v->kategori)->nama_kategori ?? ($v->kategori_lainnya ?: '-') }}
            </td>

            <td>{{ optional($v->instansi)->nama_instansi ?? '-' }}</td>

            <td>{{ $v->judul }}</td>

            <td>
                {{ $v->tanggal_kejadian ? \Carbon\Carbon::parse($v->tanggal_kejadian)->format('d-m-Y') : '-' }}
            </td>

            <td>{{ $v->kecamatan ?? '-' }}</td>

            <td>
                @if ($v->foto)
                    <img
                        src="{{ asset('storage/' . $v->foto) }}"
                        width="100"
                        alt="Foto laporan"
                    >
                @elseif ($v->lampiran)
                    <img
                        src="{{ asset('storage/' . $v->lampiran) }}"
                        width="100"
                        alt="Lampiran laporan"
                    >
                @else
                    Tidak ada foto
                @endif
            </td>

            <td>{{ $v->tingkat_prioritas }}</td>

            <td>{{ $v->status }}</td>

            <td>{{ optional($v->diverifikasiOleh)->name ?? '-' }}</td>

            <td>
                <a href="{{ route('super_admin.laporan.show', $v->id) }}">
                    Show
                </a>

                <a href="{{ route('super_admin.laporan.edit', $v->id) }}">
                    Edit
                </a>

                <form
                    action="{{ route('super_admin.laporan.destroy', $v->id) }}"
                    method="POST"
                    style="display: inline;"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Apakah kamu yakin ingin menghapus laporan ini?')"
                    >
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="14">Belum ada laporan.</td>
        </tr>
    @endforelse
</table>

@endsection