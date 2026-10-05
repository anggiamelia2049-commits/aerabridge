@extends('template.layout')

@section('title', 'Detail User')

@section('content')
    <div class="p-6">


        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col sm:flex-row gap-6">

                {{-- Foto --}}
                <div class="flex-shrink-0 flex justify-center sm:justify-start">
                    @if ($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}"
                            class="w-36 h-36 rounded-lg object-cover border border-gray-200">
                    @else
                        <div
                            class="w-36 h-36 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-sm text-center px-2">
                            Tidak ada foto
                        </div>
                    @endif
                </div>

                {{-- Detail --}}
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">

                    <div>
                        <p class="text-xs uppercase text-gray-400 mb-1">NIK</p>
                        <p class="text-gray-800 font-medium">{{ $user->nik }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-400 mb-1">Nama</p>
                        <p class="text-gray-800 font-medium">{{ $user->nama }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-400 mb-1">Username</p>
                        <p class="text-gray-800 font-medium">{{ $user->username }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-400 mb-1">Email</p>
                        <p class="text-gray-800 font-medium">{{ $user->email }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-400 mb-1">No HP</p>
                        <p class="text-gray-800 font-medium">{{ $user->no_hp }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-400 mb-1">Jenis Kelamin</p>
                        <p class="text-gray-800 font-medium">{{ $user->jenis_kelamin }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-400 mb-1">Pekerjaan</p>
                        <p class="text-gray-800 font-medium">{{ $user->pekerjaan }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-400 mb-1">Role</p>
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-700">
                            {{ $user->role }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-gray-400 mb-1">Status</p>
                        @if ($user->status == 'aktif')
                            <span
                                class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                {{ $user->status }}
                            </span>
                        @else
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                {{ $user->status }}
                            </span>
                        @endif
                    </div>

                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('super_admin.user.index') }}"
                    class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    Kembali
                </a>
            </div>

        </div>

    </div>
@endsection
