<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instansis = Instansi::latest()->get();

        return view(
            'super_admin.instansi.index',
            compact('instansis')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super_admin.instansi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_telp' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $data = [
            'nama_instansi' => $request->nama_instansi,
            'deskripsi' => $request->deskripsi,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'status' => $request->status,
        ];

        // Jika user memilih logo
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store(
                'instansi',
                'public'
            );
        }

        Instansi::create($data);

        return redirect()
            ->route('instansi.index')
            ->with('success', 'Instansi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $instansi = Instansi::findOrFail($id);

        return view(
            'super_admin.instansi.show',
            compact('instansi')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $instansi = Instansi::findOrFail($id);

        return view(
            'super_admin.instansi.edit',
            compact('instansi')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_telp' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $instansi = Instansi::findOrFail($id);

        $data = [
            'nama_instansi' => $request->nama_instansi,
            'deskripsi' => $request->deskripsi,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
            'status' => $request->status,
        ];

        // Jika user memilih logo baru
        if ($request->hasFile('logo')) {

            // Hapus logo lama jika ada
            if ($instansi->logo) {
                Storage::disk('public')->delete($instansi->logo);
            }

            // Simpan logo baru
            $data['logo'] = $request->file('logo')->store(
                'instansi',
                'public'
            );
        }

        $instansi->update($data);

        return redirect()
            ->route('instansi.index')
            ->with('success', 'Instansi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $instansi = Instansi::findOrFail($id);

        // Hapus file logo jika ada
        if ($instansi->logo) {
            Storage::disk('public')->delete($instansi->logo);
        }

        $instansi->delete();

        return redirect()
            ->route('instansi.index')
            ->with('success', 'Instansi berhasil dihapus.');
    }
}