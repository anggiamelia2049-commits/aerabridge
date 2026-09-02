<?php

namespace App\Http\Controllers;

use App\Models\AeraPayTransaksi;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;

class AeraPayTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksis = AeraPayTransaksi::with(['user', 'laporan'])->latest()->get();
        return view('AeraPayTransaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $laporans = Laporan::all();
        return view('AeraPayTransaksi.create', compact('users', 'laporans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'laporan_id' => 'nullable|exists:laporans,id',
            'jenis_transaksi' => 'required|in:reward,redeem,admin_adjust',
            'nominal' => 'required|integer',
            'saldo_sebelum' => 'required|integer',
            'saldo_sesudah' => 'required|integer',
            'status' => 'required|in:berhasil,pending,gagal',
        ]);

        AeraPayTransaksi::create([
            'user_id' => $request->user_id,
            'laporan_id' => $request->laporan_id,
            'jenis_transaksi' => $request->jenis_transaksi,
            'nominal' => $request->nominal,
            'saldo_sebelum' => $request->saldo_sebelum,
            'saldo_sesudah' => $request->saldo_sesudah,
            'status' => $request->status,
        ]);

        return redirect()->route('AeraPayTransaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $aeraPayTransaksi = AeraPayTransaksi::with([
            'user',
            'laporan'
        ])->findOrFail($id);
        return view('aerapaytransaksi.show',compact('aeraPayTransaksi')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $aeraPayTransaksi = AeraPayTransaksi::findOrFail($id);
        return view(
            'aera_pay_transaksi.edit',
            compact('aeraPayTransaksi')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'laporan_id' => 'nullable|exists:laporans,id',
            'jenis_transaksi' => 'required|in:reward,redeem,admin_adjust',
            'nominal' => 'required|integer',
            'saldo_sebelum' => 'required|integer',
            'saldo_sesudah' => 'required|integer',
            'status' => 'required|in:berhasil,pending,gagal',
        ]);

        $aeraPayTransaksi = AeraPayTransaksi::findOrFail($id);

        $aeraPayTransaksi->update([
            'user_id' => $request->user_id,
            'laporan_id' => $request->laporan_id,
            'jenis_transaksi' => $request->jenis_transaksi,
            'nominal' => $request->nominal,
            'saldo_sebelum' => $request->saldo_sebelum,
            'saldo_sesudah' => $request->saldo_sesudah,
            'status' => $request->status,
        ]);

        return redirect()->route('aera_pay_transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aeraPayTransaksi = AeraPayTransaksi::findOrFail($id);
        $aeraPayTransaksi->delete();
        return redirect()->route('aera_pay_transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
