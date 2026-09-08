
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>

<h2>Edit User</h2>

@if($errors->any())
    <ul style="color: red;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('user.update', $dataedituser->id) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <label>NIK</label><br>
    <input type="text" name="nik" value="{{ old('nik', $dataedituser->nik) }}">
    <br><br>

    <label>Nama</label><br>
    <input type="text" name="nama" value="{{ old('nama', $dataedituser->nama) }}">
    <br><br>

    <label>Username</label><br>
    <input type="text" name="username" value="{{ old('username', $dataedituser->username) }}">
    <br><br>

    <label>Email</label><br>
    <input type="email" name="email" value="{{ old('email', $dataedituser->email) }}">
    <br><br>

    <label>Password</label><br>
    <input type="password" name="password">
    <small>Kosongkan jika tidak ingin mengubah password.</small>
    <br><br>

    <label>No HP</label><br>
    <input type="text" name="no_hp" value="{{ old('no_hp', $dataedituser->no_hp) }}">
    <br><br>

    <label>Jenis Kelamin</label><br>
    <select name="jenis_kelamin">
        <option value="Laki-laki"
            {{ $dataedituser->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
            Laki-laki
        </option>

        <option value="Perempuan"
            {{ $dataedituser->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
            Perempuan
        </option>
    </select>
    <br><br>

    <label>Pekerjaan</label><br>
    <input type="text"
           name="pekerjaan"
           value="{{ old('pekerjaan', $dataedituser->pekerjaan) }}">
    <br><br>

    <label>Alamat</label><br>
    <textarea name="alamat">{{ old('alamat', $dataedituser->alamat) }}</textarea>
    <br><br>

    <label>Tanggal Lahir</label><br>
    <input type="date"
           name="tanggal_lahir"
           value="{{ old('tanggal_lahir', $dataedituser->tanggal_lahir) }}">
    <br><br>

    <label>Penyandang Disabilitas</label><br>
    <select name="penyandang_disabilitas">
        <option value="Ya"
            {{ $dataedituser->penyandang_disabilitas == 'Ya' ? 'selected' : '' }}>
            Ya
        </option>

        <option value="Tidak"
            {{ $dataedituser->penyandang_disabilitas == 'Tidak' ? 'selected' : '' }}>
            Tidak
        </option>
    </select>
    <br><br>

    <label>Foto</label><br>

    @if($dataedituser->foto)
        <img src="{{ asset('storage/' . $dataedituser->foto) }}"
             width="100">
        <br><br>
    @endif

    <input type="file" name="foto">
    <br><br>

    <label>Role</label><br>
    <select name="role">
        <option value="super_admin"
            {{ $dataedituser->role == 'super_admin' ? 'selected' : '' }}>
            Super Admin
        </option>

        <option value="warga"
            {{ $dataedituser->role == 'warga' ? 'selected' : '' }}>
            Warga
        </option>

        <option value="instansi"
            {{ $dataedituser->role == 'instansi' ? 'selected' : '' }}>
            Instansi
        </option>

        <option value="petugas"
            {{ $dataedituser->role == 'petugas' ? 'selected' : '' }}>
            Petugas
        </option>
    </select>
    <br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="Aktif"
            {{ $dataedituser->status == 'Aktif' ? 'selected' : '' }}>
            Aktif
        </option>

        <option value="Nonaktif"
            {{ $dataedituser->status == 'Nonaktif' ? 'selected' : '' }}>
            Nonaktif
        </option>
    </select>
    <br><br>

    <button type="submit">Update</button>
    <a href="{{ route('user.index') }}">Kembali</a>

</form>

</body>
</html>

