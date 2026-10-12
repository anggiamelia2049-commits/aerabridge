```blade
@extends('template.layout')

@section('title', 'Penugasan')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Daftar Penugasan</h3>
        <p class="text-muted small mb-0">Pantau tugas penanganan laporan yang diberikan kepada kamu.</p>
    </div>

    @php
        $tabs = [
            'semua' => 'Semua',
            'aktif' => 'Aktif',
            'prioritas' => 'Prioritas',
            'selesai' => 'Selesai',
        ];
    @endphp

    <div class="d-flex flex-wrap gap-2 mb-3">
        @foreach ($tabs as $key => $label)
            <a href="{{ route('petugas.penugasan.index', $key === 'semua' ? [] : ['filter' => $key]) }}"
               class="btn btn-sm {{ $filter === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3 py-3">No</th>
                            <th class="py-3">Laporan</th>
                            <th class="py-3">Prioritas</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Tanggal Penugasan</th>
                            <th class="py-3">SLA</th>
                            <th class="text-center py-3 pe-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($penugasan as $item)
                            <tr>
                                <td class="px-3 text-muted">{{ $loop->iteration }}</td>

                                <td style="min-width: 200px;">
                                    <span class="fw-semibold text-dark">
                                        {{ $item->laporan->judul ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    @if (($item->laporan->tingkat_prioritas ?? '') == 'Krisis')
                                        <span class="badge bg-danger fw-normal">Krisis</span>
                                    @elseif (($item->laporan->tingkat_prioritas ?? '') == 'Sedang')
                                        <span class="badge bg-warning text-dark fw-normal">Sedang</span>
                                    @elseif (($item->laporan->tingkat_prioritas ?? '') == 'Rendah')
                                        <span class="badge bg-success fw-normal">Rendah</span>
                                    @else
                                        <span class="badge bg-secondary fw-normal">
                                            {{ $item->laporan->tingkat_prioritas ?? '-' }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $status = $item->labelStatus();
                                    @endphp

                                    @if ($status == 'Selesai' || $status == 'selesai')
                                        <span class="badge bg-success fw-normal">{{ $status }}</span>
                                    @elseif ($status == 'Dalam Proses' || $status == 'dalam_proses')
                                        <span class="badge bg-primary fw-normal">Dalam Proses</span>
                                    @elseif ($status == 'Dibatalkan' || $status == 'dibatalkan')
                                        <span class="badge bg-danger fw-normal">Dibatalkan</span>
                                    @else
                                        <span class="badge bg-secondary fw-normal">{{ $status }}</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->tanggal_penugasan
                                        ? $item->tanggal_penugasan->format('d-m-Y H:i')
                                        : '-' }}
                                </td>

                                <td>
                                    <span class="{{ $item->isOverdue() ? 'text-danger fw-semibold' : 'text-muted' }}">
                                        {{ $item->teksSla() }}
                                    </span>
                                    @if ($item->isOverdue())
                                        <small class="text-danger d-block">Melewati batas waktu</small>
                                    @endif
                                </td>

                                <td class="text-center pe-3">
                                    <a href="{{ route('petugas.penugasan.show', $item->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <p class="text-muted mb-1">Belum ada penugasan.</p>
                                    <small class="text-muted">
                                        Penugasan akan muncul di sini saat tersedia.
                                    </small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>
@endsection
```