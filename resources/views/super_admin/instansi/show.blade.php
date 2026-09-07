<h2>Detail Instansi</h2>

<table border="1" cellpadding="10">
    <tr>
        <td>ID</td>
        <td>{{ $instansi->id }}</td>
    </tr>

    <tr>
        <td>Nama Instansi</td>
        <td>{{ $instansi->nama_instansi }}</td>
    </tr>

    <tr>
        <td>Deskripsi</td>
        <td>{{ $instansi->deskripsi }}</td>
    </tr>

    <tr>
        <td>Alamat</td>
        <td>{{ $instansi->alamat }}</td>
    </tr>

    <tr>
        <td>No Telepon</td>
        <td>{{ $instansi->no_telp }}</td>
    </tr>

    <tr>
        <td>Email</td>
        <td>{{ $instansi->email }}</td>
    </tr>

    <tr>
        <td>Logo</td>
        <td>
            @if($instansi->logo)
                <img src="{{ asset($instansi->logo) }}" width="150">
            @else
                Tidak ada logo
            @endif
        </td>
    </tr>

    <tr>
        <td>Status</td>
        <td>{{ $instansi->status }}</td>
    </tr>
</table>

<br>

<a href="{{ route('instansi.index') }}">Kembali</a>