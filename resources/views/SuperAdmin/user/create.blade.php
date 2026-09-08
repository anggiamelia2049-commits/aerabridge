
<!DOCTYPE html>
<html>
<head>
    <title>Tambah User</title>
</head>
<body>

<h2>Tambah User</h2>

@if($errors->any())
    <ul style="color: red;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>NIK</label><br>
    <input type="text" name="nik" value="{{ old('nik') }}">
    <br><br>

    <label>Nama</label><br>
    <input type="text" name="nama" value="{{ old('nama') }}">
    <br><br>

    <label>Username</label><br>
    <input type="text" name="username" value="{{ old('username') }}">
    <br><br>

    <label>Email</label><br>
    <input type="email" name="email" value="{{ old('email') }}">
    <br><br>

    <label>Password</label><br>
    <input type="password" name="password">
    <br><br>

    <label>No HP</label><br>
    <input type="text" name="no_hp" value="{{ old('no_hp') }}">
    <br><br>

    <label>Jenis Kelamin</label><br>
    <select name="jenis_kelamin">
        <option value="">-- Pilih --</option>
        <option value="Laki-laki">Laki-laki</option>
        <option value="Perempuan">Perempuan</option>
    </select>
    <br><br>

    <label>Pekerjaan</label><br>
    <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}">
    <br><br>

    <label>Alamat</label><br>
    <textarea name="alamat">{{ old('alamat') }}</textarea>
    <br><br>

    <label>Tanggal Lahir</label><br>
    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
    <br><br>

    <label>Penyandang Disabilitas</label><br>
    <select name="penyandang_disabilitas">
        <option value="">-- Pilih --</option>
        <option value="Ya">Ya</option>
        <option value="Tidak">Tidak</option>
    </select>
    <br><br>

    <label>Foto</label><br>
    <input type="file" name="foto">
    <br><br>

    <label>Role</label><br>
    <select name="role">
        <option value="">-- Pilih Role --</option>
        <option value="super_admin">Super Admin</option>
        <option value="warga">Warga</option>
        <option value="instansi">Instansi</option>
        <option value="petugas">Petugas</option>
    </select>
    <br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="">-- Pilih Status --</option>
        <option value="Aktif">Aktif</option>
        <option value="Nonaktif">Nonaktif</option>
    </select>
    <br><br>

    <button type="submit">Simpan</button>
    <a href="{{ route('user.index') }}">Kembali</a>

</form>

</body>
</html>

