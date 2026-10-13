@extends('template.layout')

@section('title', 'Detail Laporan')

@section('content')
<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Detail Laporan</h3>
            <p class="text-muted small mb-0">Informasi lengkap laporan masyarakat.</p>
        </div>

        <a href="{{ route('petugas.laporan.index') }}"
           class="btn btn-outline-secondary btn-sm">
            &larr; Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3">

            <h5 class="fw-bold text-dark mb-3">
                {{ $laporan->judul }}
            </h5>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="table-light" style="width: 200px;">Pelapor</th>
                            <td>{{ $laporan->user->nama ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Kategori</th>
                            <td>{{ $laporan->kategori->nama_kategori ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Instansi</th>
                            <td>{{ $laporan->instansi->nama_instansi ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Judul</th>
                            <td>{{ $laporan->judul ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Deskripsi</th>
                            <td style="white-space: pre-line;">{{ $laporan->deskripsi ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Foto Laporan</th>
                            <td>
                                @if ($laporan->foto)
                                    <a href="{{ asset('storage/' . $laporan->foto) }}"
                                       target="_blank">
                                        <img src="{{ asset('storage/' . $laporan->foto) }}"
                                             alt="Foto laporan"
                                             class="rounded border"
                                             style="width: 200px; max-height: 200px; object-fit: cover;">
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada foto</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="table-light">Latitude</th>
                            <td>{{ $laporan->latitude ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Longitude</th>
                            <td>{{ $laporan->longitude ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Alamat</th>
                            <td>{{ $laporan->alamat ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Tingkat Prioritas</th>
                            <td>
                                @if ($laporan->tingkat_prioritas == 'Krisis')
                                    <span class="badge bg-danger fw-normal">Krisis</span>
                                @elseif ($laporan->tingkat_prioritas == 'Sedang')
                                    <span class="badge bg-warning text-dark fw-normal">Sedang</span>
                                @elseif ($laporan->tingkat_prioritas == 'Rendah')
                                    <span class="badge bg-success fw-normal">Rendah</span>
                                @else
                                    <span class="badge bg-secondary fw-normal">
                                        {{ $laporan->tingkat_prioritas ?? '-' }}
                                    </span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="table-light">Status</th>
                            <td>
                                @if ($laporan->status == 'Selesai')
                                    <span class="badge bg-success fw-normal">Selesai</span>
                                @elseif ($laporan->status == 'Diproses')
                                    <span class="badge bg-primary fw-normal">Diproses</span>
                                @elseif ($laporan->status == 'Diverifikasi')
                                    <span class="badge bg-info text-dark fw-normal">Diverifikasi</span>
                                @elseif ($laporan->status == 'Ditolak')
                                    <span class="badge bg-danger fw-normal">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary fw-normal">
                                        {{ $laporan->status ?? 'Menunggu' }}
                                    </span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="table-light">Diverifikasi Oleh</th>
                            <td>{{ $laporan->diverifikasiOleh->nama ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <a href="{{ route('petugas.laporan.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    &larr; Kembali ke Daftar Laporan
                </a>
            </div>

        </div>
    </div>

</div>
@endsection