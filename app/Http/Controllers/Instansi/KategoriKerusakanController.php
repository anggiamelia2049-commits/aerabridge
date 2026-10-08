<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Models\KategoriKerusakan;

class KategoriKerusakanController extends Controller
{
    public function index()
    {
        $kategori = KategoriKerusakan::orderBy('nama_kategori')->paginate(10);

        return view('instansi.kategori-kerusakan.index', compact('kategori'));
    }
}
