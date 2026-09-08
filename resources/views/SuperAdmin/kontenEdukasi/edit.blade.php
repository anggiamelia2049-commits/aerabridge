<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Konten Edukasi</title>
</head>
<body>

    <h1>Edit Konten Edukasi</h1>

    @if ($errors->any())
        <div>
            <strong>Terjadi kesalahan:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('konten-edukasi.update', $konten->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')


        <label for="judul">Judul:</label>
        <br>

        <input
            type="text"
            id="judul"
            name="judul"
            value="{{ old('judul', $konten->judul) }}"
            required
        >

        <br><br>


        <label>Thumbnail Saat Ini:</label>
        <br>

        @if ($konten->thumbnail)

            <img
                src="{{ asset('storage/' . $konten->thumbnail) }}"
                alt="Thumbnail {{ $konten->judul }}"
                width="150"
                height="150"
                style="object-fit: contain;"
            >

        @else

            <p>
                Belum ada thumbnail.
            </p>

        @endif

        <br><br>


        <label for="thumbnail">Ganti Thumbnail:</label>
        <br>

        <input
            type="file"
            id="thumbnail"
            name="thumbnail"
            accept=".jpg,.jpeg,.png"
        >

        <br>

        <small>
            Kosongkan jika tidak ingin mengganti thumbnail.
        </small>

        <br><br>


        <label for="isi">Isi:</label>
        <br>

        <textarea
            id="isi"
            name="isi"
            rows="8"
            cols="50"
            required
        >{{ old('isi', $konten->isi) }}</textarea>

        <br><br>


        <label for="kategori">Kategori:</label>
        <br>

        <input
            type="text"
            id="kategori"
            name="kategori"
            value="{{ old('kategori', $konten->kategori) }}"
            required
        >

        <br><br>


        <label for="status">Status:</label>
        <br>

        <select id="status" name="status">

            <option
                value="draft"
                {{ old('status', $konten->status) == 'draft' ? 'selected' : '' }}
            >
                Draft
            </option>

            <option
                value="publish"
                {{ old('status', $konten->status) == 'publish' ? 'selected' : '' }}
            >
                Publish
            </option>

            <option
                value="nonaktif"
                {{ old('status', $konten->status) == 'nonaktif' ? 'selected' : '' }}
            >
                Nonaktif
            </option>

        </select>

        <br><br>


        <button type="submit">
            Update
        </button>

        <a href="{{ route('konten-edukasi.index') }}">
            Kembali
        </a>

    </form>

</body>
</html>