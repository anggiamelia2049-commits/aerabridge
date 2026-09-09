<!DOCTYPE html>
<html>
<head>
    <title>Detail User</title>
</head>
<body>

<h2>Detail User</h2>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if($user->foto)
    <div>
        <img src="{{ asset('storage/' . $user->foto) }}"
             width="150"
             height="150"
             style="object-fit: cover;">
    </div>
@else
    <p>Tidak ada foto</p>
@endif

<table border="1" cellpadding="8" cellspacing="0">

    <tr>
        <th>NIK</th>
        <td>{{ $user->nik }}</td>
    </tr>

    <tr>
        <th>Nama</th>
        <td>{{ $user->nama }}</td>
    </tr>

    <tr>
        <th>Username</th>
        <td>{{ $user->username }}</td>
    </tr>

    <tr>
        <th>Email</th>
        <td>{{ $user->email }}</td>
    </tr>

    <tr>
        <th>No HP</th>
        <td>{{ $user->no_hp }}</td>
    </tr>

    <tr>
        <th>Jenis Kelamin</th>
        <td>{{ $user->jenis_kelamin }}</td>
    </tr>

    <tr>
        <th>Pekerjaan</th>
        <td>{{ $user->pekerjaan }}</td>
    </tr>

    <tr>
        <th>Role</th>
        <td>{{ $user->role }}</td>
    </tr>

    <tr>
        <th>Status</th>
        <td>{{ $user->status }}</td>
    </tr>

</table>

<br>

<a href="{{ route('user.index') }}">Kembali</a>

<a href="{{ route('user.edit', $user->id) }}">Edit</a>

</body>
</html>