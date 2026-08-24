<?php

namespace App\Http\Controllers;

use App\Models\TemplatePesan;
use Illuminate\Http\Request;

class TemplatePesanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templatePesan = TemplatePesan::latest()->get();

        return view(
            'template_pesan.index',
            compact('templatePesan')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('template_pesan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_template' => 'required|string|max:255|unique:template_pesan,kode_template',
            'judul' => 'required|string|max:255',
            'isi_pesan' => 'required|string',
            'kategori' => 'required|in:laporan,penugasan,sla,reward,lainnya',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        TemplatePesan::create([
            'kode_template' => $request->kode_template,
            'judul' => $request->judul,
            'isi_pesan' => $request->isi_pesan,
            'kategori' => $request->kategori,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('template-pesan.index')
            ->with('success', 'Template pesan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TemplatePesan $templatePesan)
    {
        return view(
            'template_pesan.show',
            compact('templatePesan')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TemplatePesan $templatePesan)
    {
        return view(
            'template_pesan.edit',
            compact('templatePesan')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TemplatePesan $templatePesan)
    {
        $request->validate([
            'kode_template' => 'required|string|max:255|unique:template_pesan,kode_template,' . $templatePesan->id,
            'judul' => 'required|string|max:255',
            'isi_pesan' => 'required|string',
            'kategori' => 'required|in:laporan,penugasan,sla,reward,lainnya',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $templatePesan->update([
            'kode_template' => $request->kode_template,
            'judul' => $request->judul,
            'isi_pesan' => $request->isi_pesan,
            'kategori' => $request->kategori,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('template-pesan.index')
            ->with('success', 'Template pesan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TemplatePesan $templatePesan)
    {
        $templatePesan->delete();

        return redirect()
            ->route('template-pesan.index')
            ->with('success', 'Template pesan berhasil dihapus.');
    }
}