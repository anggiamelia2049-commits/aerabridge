<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Konten Edukasi</title>
</head>
<body>

    <h1>Detail Konten Edukasi</h1>

    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>Judul</th>
            <td>
                {{ $konten->judul }}
            </td>
        </tr>

        <tr>
            <th>Thumbnail</th>
            <td>
                @if ($konten->thumbnail)

                    <img
                        src="{{ asset('storage/' . $konten->thumbnail) }}"
                        alt="Thumbnail {{ $konten->judul }}"
                        width="250"
                        height="250"
                        style="object-fit: contain;"
                    >

                @else

                    Tidak ada thumbnail

                @endif
            </td>
        </tr>

        <tr>
            <th>Isi</th>
            <td>
                {{ $konten->isi }}
            </td>
        </tr>

        <tr>
            <th>Kategori</th>
            <td>
                {{ $konten->kategori }}
            </td>
        </tr>

        <tr>
            <th>Penulis</th>
            <td>
                {{ $konten->penulis->name ?? '-' }}
            </td>
        </tr>

        <tr>
            <th>Status</th>
            <td>
                {{ $konten->status }}
            </td>
        </tr>

        <tr>
            <th>Dibuat</th>
            <td>
                {{ $konten->created_at->format('d-m-Y H:i') }}
            </td>
        </tr>

        <tr>
            <th>Terakhir Diubah</th>
            <td>
                {{ $konten->updated_at->format('d-m-Y H:i') }}
            </td>
        </tr>

    </table>

    <br>

    <a href="{{ route('konten-edukasi.index') }}">
        Kembali
    </a>

    |

    <a href="{{ route('konten-edukasi.edit', $konten->id) }}">
        Edit
    </a>

</body>
</html>