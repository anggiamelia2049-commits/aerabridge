<form action="{{ route('instansi.update', $instansi->id) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    Nama Instansi :
    <input
        type="text"
        name="nama_instansi"
        value="{{ old('nama_instansi', $instansi->nama_instansi) }}"
    >

    @if ($errors->has('nama_instansi'))
        <span>{{ $errors->first('nama_instansi') }}</span>
    @endif

    <br>

    Deskripsi :
    <textarea name="deskripsi">{{ old('deskripsi', $instansi->deskripsi) }}</textarea>

    @if ($errors->has('deskripsi'))
        <span>{{ $errors->first('deskripsi') }}</span>
    @endif

    <br>

    Alamat :
    <textarea name="alamat">{{ old('alamat', $instansi->alamat) }}</textarea>

    @if ($errors->has('alamat'))
        <span>{{ $errors->first('alamat') }}</span>
    @endif

    <br>

    No Telepon :
    <input
        type="text"
        name="no_telp"
        value="{{ old('no_telp', $instansi->no_telp) }}"
    >

    @if ($errors->has('no_telp'))
        <span>{{ $errors->first('no_telp') }}</span>
    @endif

    <br>

    Email :
    <input
        type="email"
        name="email"
        value="{{ old('email', $instansi->email) }}"
    >

    @if ($errors->has('email'))
        <span>{{ $errors->first('email') }}</span>
    @endif

    <br>

    Logo :

    @if ($instansi->logo)
        <div>
            <p>Logo saat ini:</p>

            <img
                src="{{ asset('storage/' . $instansi->logo) }}"
                alt="Logo {{ $instansi->nama_instansi }}"
                width="100"
                height="100"
                style="object-fit: contain;"
            >
        </div>
    @else
        <p>Belum ada logo.</p>
    @endif

    <br>

    <input
        type="file"
        name="logo"
        accept=".jpg,.jpeg,.png"
    >

    <small>
        Kosongkan jika tidak ingin mengganti logo.
    </small>

    @if ($errors->has('logo'))
        <span>{{ $errors->first('logo') }}</span>
    @endif

    <br>

    Status :
    <select name="status">

        <option
            value="Aktif"
            {{ old('status', $instansi->status) == 'Aktif' ? 'selected' : '' }}
        >
            Aktif
        </option>

        <option
            value="Nonaktif"
            {{ old('status', $instansi->status) == 'Nonaktif' ? 'selected' : '' }}
        >
            Nonaktif
        </option>

    </select>

    @if ($errors->has('status'))
        <span>{{ $errors->first('status') }}</span>
    @endif

    <br>

    <button type="submit">Update</button>

    <a href="{{ route('instansi.index') }}">Back</a>

</form>