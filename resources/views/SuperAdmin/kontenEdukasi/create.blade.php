<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Konten Edukasi</title>
</head>
<body>

    <h1>Tambah Konten Edukasi</h1>

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
        action="{{ route('konten-edukasi.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <label for="judul">Judul:</label>
        <br>

        <input
            type="text"
            id="judul"
            name="judul"
            value="{{ old('judul') }}"
            required
        >

        <br><br>


        <label for="thumbnail">Thumbnail:</label>
        <br>

        <input
            type="file"
            id="thumbnail"
            name="thumbnail"
            accept=".jpg,.jpeg,.png"
        >

        <br>

        <small>
            Format: JPG, JPEG, PNG. Maksimal 2MB.
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
        >{{ old('isi') }}</textarea>

        <br><br>


        <label for="kategori">Kategori:</label>
        <br>

        <input
            type="text"
            id="kategori"
            name="kategori"
            value="{{ old('kategori') }}"
            required
        >

        <br><br>


        <label for="status">Status:</label>
        <br>

        <select id="status" name="status">

            <option
                value="draft"
                {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}
            >
                Draft
            </option>

            <option
                value="publish"
                {{ old('status') == 'publish' ? 'selected' : '' }}
            >
                Publish
            </option>

            <option
                value="nonaktif"
                {{ old('status') == 'nonaktif' ? 'selected' : '' }}
            >
                Nonaktif
            </option>

        </select>

        <br><br>


        <button type="submit">
            Simpan
        </button>

        <a href="{{ route('konten-edukasi.index') }}">
            Kembali
        </a>

    </form>

</body>
</html>