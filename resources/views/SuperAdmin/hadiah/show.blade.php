<table border="1" cellpadding="10">
    <tr>
        <th>Nama Hadiah</th>
        <td>{{ $hadiah->nama_hadiah }}</td>
    </tr>

    <tr>
        <th>Deskripsi</th>
        <td>{{ $hadiah->deskripsi ?? '-' }}</td>
    </tr>

    <tr>
        <th>Poin Dibutuhkan</th>
        <td>{{ $hadiah->poin_dibutuhkan }}</td>
    </tr>

    <tr>
        <th>Stok</th>
        <td>{{ $hadiah->stok }}</td>
    </tr>

    <tr>
        <th>Gambar</th>
        <td>
            @if ($hadiah->gambar)
                <img src="{{ asset('storage/' . $hadiah->gambar) }}" width="150">
            @else
                Tidak ada gambar
            @endif
        </td>
    </tr>

    <tr>
        <th>Status</th>
        <td>{{ $hadiah->status }}</td>
    </tr>

    <tr>
        <th>Dibuat</th>
        <td>{{ $hadiah->created_at->format('d-m-Y H:i') }}</td>
    </tr>

    <tr>
        <th>Terakhir Diubah</th>
        <td>{{ $hadiah->updated_at->format('d-m-Y H:i') }}</td>
    </tr>
</table>

<br>

<a href="{{ route('super_admin.hadiah.index') }}">Kembali</a>
|
<a href="{{ route('super_admin.hadiah.edit', $hadiah->id) }}">Edit</a>