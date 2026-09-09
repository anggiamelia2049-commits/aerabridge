<h2>Detail Template Pesan</h2>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>Kode Template</th>
        <td>{{ $templatePesan->kode_template }}</td>
    </tr>

    <tr>
        <th>Judul</th>
        <td>{{ $templatePesan->judul }}</td>
    </tr>

    <tr>
        <th>Isi Pesan</th>
        <td>{{ $templatePesan->isi_pesan }}</td>
    </tr>

    <tr>
        <th>Kategori</th>
        <td>{{ ($templatePesan->kategori) }}</td>
    </tr>

    <tr>
        <th>Status</th>
        <td>{{ ($templatePesan->status) }}</td>
    </tr>

    <tr>
        <th>Dibuat Pada</th>
        <td>{{ $templatePesan->created_at }}</td>
    </tr>

    <tr>
        <th>Terakhir Diubah</th>
        <td>{{ $templatePesan->updated_at }}</td>
    </tr>
</table>

<br>

<a href="{{ route('template-pesan.edit', $templatePesan->id) }}">Edit</a>
<a href="{{ route('template-pesan.index') }}">Kembali</a>

