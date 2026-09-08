
<!DOCTYPE html>
<html>
<head>
    <title>Data User</title>
</head>
<body>

<h2>Data User</h2>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<a href="{{ route('user.create') }}">+ Tambah User</a>

<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Jenis Kelamin</th>
            <th>Pekerjaan</th>
            <th>Role</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}"
                             width="60"
                             height="60"
                             style="object-fit: cover;">
                    @else
                        Tidak ada foto
                    @endif
                </td>

                <td>{{ $user->nik }}</td>
                <td>{{ $user->nama }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->no_hp }}</td>
                <td>{{ $user->jenis_kelamin }}</td>
                <td>{{ $user->pekerjaan }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->status }}</td>

                <td>
                    <a href="{{ route('user.show', $user->id) }}">Lihat</a> |
                    <a href="{{ route('user.edit', $user->id) }}">Edit</a> |

                    <form action="{{ route('user.destroy', $user->id) }}"
                          method="POST"
                          style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                onclick="return confirm('Yakin ingin menghapus user ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="12">Belum ada data user.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>