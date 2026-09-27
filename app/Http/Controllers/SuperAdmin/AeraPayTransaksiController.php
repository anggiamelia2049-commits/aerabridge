<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AeraPayTransaksi;
use Illuminate\Http\Request;

class AeraPayTransaksiController extends Controller
{
   
    public function index(Request $request)
    {
        $query = AeraPayTransaksi::with(['user', 'laporan']);

        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $transaksis = $query->latest()
            ->paginate(20)
            ->withQueryString();

        return view('SuperAdmin.aeraPay.index', compact('transaksis'));
    }

       public function show(string $id)
    {
        $aeraPayTransaksi = AeraPayTransaksi::with(['user', 'laporan'])
            ->findOrFail($id);

        return view('SuperAdmin.aeraPay.show', compact('aeraPayTransaksi'));
    }
}