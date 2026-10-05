@extends('template.layout')

@section('content')
    <div class="flex items-center justify-end mb-4">
        <a href="{{ route('super_admin.user.create') }}"
            class="px-5 py-2 rounded-lg bg-cyan-700 text-white text-sm font-medium hover:bg-cyan-6 transition">
            + Tambah Data User
        </a>
    </div>
    <div class="bg-white rounded-lg shadow overflow-x-auto">

        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">NIK</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">No HP</th>
                    <th class="px-4 py-3">Jenis Kelamin</th>
                    <th class="px-4 py-3">Pekerjaan</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)
                    <tr class="border-b">

                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3">

                            @if ($user->foto)
                                <img src="{{ asset('storage/' . $user->foto) }}" width="60" height="60"
                                    class="object-cover rounded">
                            @else
                                <span class="text-gray-500">
                                    Tidak ada foto
                                </span>
                            @endif

                        </td>

                        <td class="px-4 py-3">
                            {{ $user->nik }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->nama }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->username }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->email }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->no_hp }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->jenis_kelamin }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->pekerjaan }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->role }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $user->status }}
                        </td>

                        <td class="px-4 py-3 whitespace-nowrap">

                            <a href="{{ route('super_admin.user.show', $user->id) }}"
                                class="text-[#45818E] hover:underline">
                                Lihat
                            </a>

                            |

                            <a href="{{ route('super_admin.user.edit', $user->id) }}"
                                class="text-[#45818E] hover:underline">
                                Edit
                            </a>

                            |

                            <form action="{{ route('super_admin.user.destroy', $user->id) }}" method="POST"
                                class="inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit" onclick="return confirm('Yakin ingin menghapus user ini?')"
                                    class="text-red-500 hover:underline">
                                    Hapus
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="12" class="px-4 py-6 text-center">
                            Belum ada data user.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>
@endsection
