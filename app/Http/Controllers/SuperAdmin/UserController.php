<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('instansi')->get();

        return view('SuperAdmin.user.index', compact('users'));
    }

    public function create()
    {
        $instansis = Instansi::where('status', 'Aktif')->get();

        return view('SuperAdmin.user.create', compact('instansis'));
    }

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
            'instansi_id' => 'required_if:role,instansi|nullable|exists:instansi,id',
            'status' => 'required|in:Aktif,Nonaktif',
        ], [
            'instansi_id.required_if' => 'Instansi wajib dipilih untuk akun dengan role instansi.',
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
            'password' => bcrypt($request->password),
            'no_hp' => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'penyandang_disabilitas' => $request->penyandang_disabilitas,
            'foto' => $foto,
            'role' => $request->role,
            'instansi_id' => $request->role === 'instansi' ? $request->instansi_id : null,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('super_admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show($id)
    {
        $user = User::with('instansi')->findOrFail($id);

        return view('SuperAdmin.user.show', compact('user'));
    }

    public function edit($id)
    {
        $dataedituser = User::findOrFail($id);
        $instansis = Instansi::where('status', 'Aktif')->get();

        return view('SuperAdmin.user.edit', compact('dataedituser', 'instansis'));
    }

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
            'instansi_id' => 'required_if:role,instansi|nullable|exists:instansi,id',
            'status' => 'required|in:Aktif,Nonaktif',
        ], [
            'instansi_id.required_if' => 'Instansi wajib dipilih untuk akun dengan role instansi.',
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
            'instansi_id' => $request->role === 'instansi' ? $request->instansi_id : null,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            $data['foto'] = $request->file('foto')->store('foto-user', 'public');
        }

        $user->update($data);

        return redirect()
            ->route('super_admin.user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();

        return redirect()
            ->route('super_admin.user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}