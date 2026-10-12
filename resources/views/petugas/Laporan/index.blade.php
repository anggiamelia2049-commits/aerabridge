@extends('template.layout')

@section('title', 'Laporan')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Daftar Laporan</h3>
            <p class="text-muted mb-0 small">Kelola dan pantau laporan masyarakat.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3 py-3">No</th>
                            <th class="py-3">Pelapor</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Instansi</th>
                            <th class="py-3">Judul Laporan</th>
                            <th class="py-3">Foto</th>
                            <th class="py-3">Prioritas</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Verifikasi Oleh</th>
                            <th class="text-center py-3 pe-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($laporan as $v)
                        <tr>
                            <td class="px-3 text-muted">{{ $loop->iteration }}</td>

                            <td>
                                <span class="fw-semibold text-dark">
                                    {{ $v->user->nama ?? '-' }}
                                </span>
                            </td>

                            <td>{{ $v->kategori->nama_kategori ?? '-' }}</td>

                            <td>{{ $v->instansi->nama_instansi ?? '-' }}</td>

                            <td style="min-width: 180px; max-width: 240px;">
                                <div class="text-dark fw-semibold text-truncate"
                                     title="{{ $v->judul }}">
                                    {{ $v->judul }}
                                </div>
                                <small class="text-muted d-block text-truncate"
                                       title="{{ $v->deskripsi }}">
                                    {{ $v->deskripsi }}
                                </small>
                            </td>

                            <td>
                                @if ($v->foto)
                                    <img src="{{ asset('storage/' . $v->foto) }}"
                                         alt="Foto laporan"
                                         width="48"
                                         height="48"
                                         class="rounded"
                                         style="object-fit: cover;">
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            <td>
                                @if ($v->tingkat_prioritas == 'Krisis')
                                    <span class="badge bg-danger fw-normal">Krisis</span>
                                @elseif ($v->tingkat_prioritas == 'Sedang')
                                    <span class="badge bg-warning text-dark fw-normal">Sedang</span>
                                @else
                                    <span class="badge bg-success fw-normal">
                                        {{ $v->tingkat_prioritas ?? '-' }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if ($v->status == 'Selesai')
                                    <span class="badge bg-success fw-normal">Selesai</span>
                                @elseif ($v->status == 'Diproses')
                                    <span class="badge bg-primary fw-normal">Diproses</span>
                                @elseif ($v->status == 'Diverifikasi')
                                    <span class="badge bg-info text-dark fw-normal">Diverifikasi</span>
                                @elseif ($v->status == 'Ditolak')
                                    <span class="badge bg-danger fw-normal">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary fw-normal">
                                        {{ $v->status ?? 'Menunggu' }}
                                    </span>
                                @endif
                            </td>

                            <td>{{ $v->diverifikasiOleh->nama ?? '-' }}</td>

                            <td class="text-center pe-3">
                                <a href="{{ route('petugas.laporan.show', $v->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <p class="text-muted mb-1">Belum ada laporan.</p>
                                <small class="text-muted">Laporan masyarakat akan muncul di sini.</small>
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