@extends('template.layout')

@section('title', 'Detail Penugasan')

@section('content')
@php
    $laporan = $penugasan->laporan;
    $adaKoordinat = $laporan->latitude !== null
        && $laporan->longitude !== null
        && $laporan->latitude !== ''
        && $laporan->longitude !== '';

    $status = $penugasan->labelStatus();
@endphp

<div class="container-fluid px-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Detail Penugasan</h3>
            <p class="text-muted small mb-0">
                Informasi tugas dan penanganan laporan masyarakat.
            </p>
        </div>

        <a href="{{ route('petugas.penugasan.index') }}"
           class="btn btn-outline-secondary btn-sm">
            &larr; Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h5 class="fw-bold text-dark mb-2">
                        {{ $laporan->judul ?? 'Tanpa Judul' }}
                    </h5>

                    <span class="text-muted small">
                        {{ $laporan->kategori->nama_kategori ?? '-' }}
                    </span>
                </div>

                <div class="d-flex flex-wrap gap-2">
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

                    @if ($penugasan->status === 'selesai')
                        <span class="badge bg-success fw-normal">Selesai</span>
                    @elseif ($penugasan->status === 'dalam_proses')
                        <span class="badge bg-primary fw-normal">Dalam Proses</span>
                    @elseif ($penugasan->status === 'dibatalkan')
                        <span class="badge bg-danger fw-normal">Dibatalkan</span>
                    @else
                        <span class="badge bg-secondary fw-normal">
                            {{ $status }}
                        </span>
                    @endif
                </div>
            </div>

            <h6 class="fw-bold mb-3">Informasi Laporan</h6>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-4">
                    <tbody>
                        <tr>
                            <th class="table-light" style="width: 220px;">Kategori</th>
                            <td>{{ $laporan->kategori->nama_kategori ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Instansi</th>
                            <td>{{ $laporan->instansi->nama_instansi ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Deskripsi</th>
                            <td style="white-space: pre-line;">{{ $laporan->deskripsi ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Alamat</th>
                            <td>{{ $laporan->alamat ?: '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Latitude</th>
                            <td>{{ $laporan->latitude ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Longitude</th>
                            <td>{{ $laporan->longitude ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="fw-bold mb-3">Informasi Penugasan</h6>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="table-light" style="width: 220px;">Status Tugas</th>
                            <td>{{ $status }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Catatan dari Instansi</th>
                            <td style="white-space: pre-line;">{{ $penugasan->catatan ?: '-' }}</td>
                        </tr>

                        <tr>
                            <th class="table-light">Tanggal Penugasan</th>
                            <td>
                                {{ $penugasan->tanggal_penugasan
                                    ? $penugasan->tanggal_penugasan->format('d-m-Y H:i')
                                    : '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="table-light">Batas SLA</th>
                            <td>
                                {{ $batasSla ? $batasSla->format('d-m-Y H:i') : '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th class="table-light">Sisa Waktu</th>
                            <td>
                                <span class="{{ $overdue ? 'text-danger fw-semibold' : 'text-dark' }}">
                                    {{ $penugasan->teksSla() }}
                                </span>

                                @if ($overdue)
                                    <small class="text-danger d-block">
                                        Melewati batas waktu penanganan.
                                    </small>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Foto Laporan</h6>

                    @if ($laporan->foto)
                        <a href="{{ asset('storage/' . $laporan->foto) }}"
                           target="_blank" rel="noopener">
                            <img src="{{ asset('storage/' . $laporan->foto) }}"
                                 alt="Foto laporan"
                                 class="img-fluid rounded border"
                                 style="max-height: 280px; width: 100%; object-fit: contain;">
                        </a>
                    @else
                        <p class="text-muted small mb-0">Tidak ada foto laporan.</p>
                    @endif
                </div>
            </div>
        </div>

        @if ($penugasan->status === 'selesai')
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Hasil Perbaikan</h6>

                        @if ($penugasan->foto_hasil)
                            <a href="{{ asset('storage/' . $penugasan->foto_hasil) }}"
                               target="_blank" rel="noopener">
                                <img src="{{ asset('storage/' . $penugasan->foto_hasil) }}"
                                     alt="Foto hasil perbaikan"
                                     class="img-fluid rounded border mb-3"
                                     style="max-height: 280px; width: 100%; object-fit: contain;">
                            </a>
                        @else
                            <p class="text-muted small">Tidak ada foto hasil perbaikan.</p>
                        @endif

                        <p class="text-dark mb-2" style="white-space: pre-line;">{{ $penugasan->catatan_penyelesaian ?: '-' }}</p>

                        @if ($penugasan->tanggal_selesai)
                            <small class="text-muted">
                                Dikirim pada
                                {{ $penugasan->tanggal_selesai->format('d-m-Y H:i') }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        @endif

    </div>

    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">

        @if ($adaKoordinat)
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $laporan->latitude }},{{ $laporan->longitude }}"
               target="_blank"
               rel="noopener"
               class="btn btn-outline-primary btn-sm">
                Buka Rute di Google Maps
            </a>
        @endif

        @if ($penugasan->status === 'ditugaskan')
            <form action="{{ route('petugas.penugasan.update', $penugasan->id) }}"
                  method="POST"
                  onsubmit="return confirm('Mulai tugas ini sekarang?')">
                @csrf
                @method('PUT')

                <input type="hidden" name="status" value="dalam_proses">

                <button type="submit" class="btn btn-primary btn-sm">
                    Mulai Tugas
                </button>
            </form>
        @endif

        @if ($penugasan->status === 'dalam_proses')
            <a href="{{ route('petugas.penugasan.edit', $penugasan->id) }}"
               class="btn btn-success btn-sm">
                Isi Closing Report
            </a>
        @endif

        <a href="{{ route('petugas.penugasan.index') }}"
           class="btn btn-outline-secondary btn-sm">
            Kembali ke Daftar
        </a>

    </div>

</div>
@endsection