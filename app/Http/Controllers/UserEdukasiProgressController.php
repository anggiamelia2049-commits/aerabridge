<?php

namespace App\Http\Controllers;

use App\Models\UserEdukasiProgress;
use App\Models\User;
use App\Models\KontenEdukasi;
use Illuminate\Http\Request;

class UserEdukasiProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $progress = UserEdukasiProgress::with([
            'user',
            'konten'
        ])->latest()->get();

        return view('user_edukasi_progress.index', compact('progress'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $konten = KontenEdukasi::all();

        return view('user_edukasi_progress.create', compact('user', 'konten'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user,id',
            'konten_id' => 'required|exixts:konten_edukasi,id',
            'status' => 'required|in:belum_dibaca,sedang,selesai',
            'progress' => 'required|integer|min:0|max:100',
            'selesai_pada' => 'nullable|date',
        ]);

        UserEdukasiProgress::create([
            'user_id' => $request->user_id,
            'konten_id' => $request->konten_id,
            'status' => $request->status,
            'progress' => $request->progress,
            'selesai_pada' => $request->selesai_pada
        ]);

        return redirect()->route('user_edukasi_progress.index')->with('success', 'Progress edukasi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userEdukasiProgress->load(['user', 'konten']);

        return view('user_edukasi_progress.show', compact('userEdukasiProgress'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = User::all();
        $konten = KontenEdukasi::all();

        return view('user_edukasi_progress.edit', compact('userEdukasiProgress', 'users', 'konten'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:user,id',
            'konten_id' => 'required|exixts:konten_edukasi,id',
            'status' => 'requires|in:belum_dibaca,sedang,selesai',
            'progress' => 'required|integer|min:0|max:100',
            'selesai_pada' => 'nullable|date'
        ]);

        return redirect()->route('user_edukasi_progress.index')->with('success', 'Progress edukasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
