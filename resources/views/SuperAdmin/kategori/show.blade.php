```blade
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Detail Kategori Kerusakan</h2>
            <p class="text-muted mb-0">
                Informasi lengkap kategori kerusakan
            </p>
        </div>

        <a href="{{ route('super_admin.kategori.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    Nama Kategori
                </div>
                <div class="col-md-9">
                    {{ $kategori->nama_kategori }}
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    Icon
                </div>
                <div class="col-md-9">
                    {{ $kategori->icon ?? '-' }}
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    Deskripsi
                </div>
                <div class="col-md-9">
                    {{ $kategori->deskripsi ?? '-' }}
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    Warna Marker
                </div>

                <div class="col-md-9 d-flex align-items-center gap-2">

                    @if($kategori->warna_marker)
                        <span
                            style="
                                width: 30px;
                                height: 30px;
                                display: inline-block;
                                background-color: {{ $kategori->warna_marker }};
                                border: 1px solid #ccc;
                                border-radius: 5px;
                            ">
                        </span>

                        <span>{{ $kategori->warna_marker }}</span>
                    @else
                        <span>-</span>
                    @endif

                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-3 fw-bold">
                    Status
                </div>

                <div class="col-md-9">
                    @if($kategori->status == 'Aktif')
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </div>
            </div>

            <hr>

            <div class="mt-4">
                <a href="{{ route('super_admin.kategori.edit', $kategori->id) }}"
                   class="btn btn-warning">
                    Edit
                </a>

                <a href="{{ route('super_admin.kategori.index') }}"
                   class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </div>
    </div>

</div>
```
