<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:255|unique:users,nik',
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:3',
            'no_hp' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'required|date',
            'penyandang_disabilitas' => 'required|in:Ya,Tidak',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required|in:super_admin,warga,instansi,petugas',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('foto-user', 'public');
        }

        User::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'no_hp' => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'penyandang_disabilitas' => $request->penyandang_disabilitas,
            'foto' => $foto,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dataedituser = User::findOrFail($id);

        return view('user.edit', compact('dataedituser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nik' => 'required|string|max:255|unique:users,nik,' . $id,
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|min:3',
            'no_hp' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'required|date',
            'penyandang_disabilitas' => 'required|in:Ya,Tidak',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required|in:super_admin,warga,instansi,petugas',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $data = [
            'nik' => $request->nik,
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'penyandang_disabilitas' => $request->penyandang_disabilitas,
            'role' => $request->role,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            $data['foto'] = $request->file('foto')->store('foto-user', 'public');
        }

        $user->update($data);

        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();

        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}