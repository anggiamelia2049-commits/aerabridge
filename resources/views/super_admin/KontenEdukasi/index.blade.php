<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konten Edukasi</title>
</head>
<body>

    <h1>Daftar Konten Edukasi</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Thumbnail</th>
                <th>Isi</th>
                <th>Kategori</th>
                <th>Penulis</th>
                <th>Status</th>
                <th><a href="{{ route('konten-edukasi.create') }}">Tambah Konten</a></th>
            </tr>
        </thead>

        <tbody>
            @forelse ($konten as $v)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $v->judul }}
                    </td>

                    <td>
                        @if ($v->thumbnail)
                            <img
                                src="{{ asset('storage/' . $v->thumbnail) }}"
                                alt="Thumbnail {{ $v->judul }}"
                                width="100"
                                height="100"
                                style="object-fit: contain;"
                            >
                        @else
                            Tidak ada thumbnail
                        @endif
                    </td>

                    <td>
                        {{ $v->isi }}
                    </td>

                    <td>
                        {{ $v->kategori }}
                    </td>

                    <td>
                        {{ $v->penulis->name ?? '-' }}
                    </td>

                    <td>
                        {{ $v->status }}
                    </td>

                    <td>
                        <a href="{{ route('konten-edukasi.show', $v->id) }}">
                            Detail
                        </a>

                        |

                        <a href="{{ route('konten-edukasi.edit', $v->id) }}">
                            Edit
                        </a>

                        |

                        <form
                            action="{{ route('konten-edukasi.destroy', $v->id) }}"
                            method="POST"
                            style="display: inline;"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus konten ini?')"
                            >
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        Belum ada konten edukasi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>