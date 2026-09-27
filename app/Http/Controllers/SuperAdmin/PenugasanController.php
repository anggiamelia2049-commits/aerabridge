<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Penugasan;
use App\Models\Instansi;
use Illuminate\Http\Request;

class PenugasanController extends Controller
{
  
    public function index(Request $request)
    {
        $query = Penugasan::with(['laporan', 'timSatgas', 'petugas.instansi']);

        if ($request->filled('instansi_id')) {
            $query->whereHas('petugas', function ($q) use ($request) {
                $q->where('instansi_id', $request->instansi_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penugasan = $query->latest('tanggal_penugasan')
            ->paginate(15)
            ->withQueryString();

        $penugasan->getCollection()->transform(function ($item) {
            $item->overdue = $item->isOverdue();
            return $item;
        });

        $instansi = Instansi::orderBy('nama_instansi')->get();

        return view('SuperAdmin.penugasan.index', compact('penugasan', 'instansi'));
    }

    public function show(Penugasan $penugasan)
    {
        $penugasan->load(['laporan', 'timSatgas', 'petugas.instansi']);

        $batasSla = $penugasan->batasWaktuSla();
        $sisaMenit = $penugasan->sisaWaktuSla();
        $overdue = $penugasan->isOverdue();

        return view('SuperAdmin.penugasan.show', compact(
            'penugasan',
            'batasSla',
            'sisaMenit',
            'overdue'
        ));
    }
}