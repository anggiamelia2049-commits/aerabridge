<?php
// app/Http/Controllers/Instansi/TimSatgasController.php
namespace App\Http\Controllers\Instansi;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\{Penugasan, TimSatgas};
use Illuminate\Http\Request;

class TimSatgasController extends Controller
{
    public function index(Request $request)
    {
        $instansiId = Auth::user()->instansi_id; // sesuaikan jika berbeda

        $timSatgas = TimSatgas::where('instansi_id', $instansiId)
            ->withCount(['penugasan as penugasan_aktif_count' => fn ($q) => $q->aktif()])
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('nama_tim', 'like', "%$s%")->orWhere('ketua', 'like', "%$s%")))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(10)->withQueryString();

        $base = TimSatgas::where('instansi_id', $instansiId);
        $totalAktif = (clone $base)->where('status', 'aktif')->count();
        $totalBertugas = (clone $base)->whereHas('penugasan', fn ($q) => $q->aktif())->count();
        $totalOverdue = Penugasan::aktif()->with('laporan') ->get()->filter(fn ($p) => $p->isOverdue())->count();

        return view('instansi.tim-satgas.index', compact('timSatgas', 'totalAktif', 'totalBertugas', 'totalOverdue'));
    }

    public function show(TimSatgas $timSatgas)
    {
        abort_if($timSatgas->instansi_id !== Auth::user()->instansi_id, 403);
        $timSatgas->load('penugasan.laporan', 'penugasan.petugas');

        return view('instansi.tim-satgas.show', ['tim' => $timSatgas]);
    }

    public function create()
    {
        return view('instansi.tim-satgas.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['instansi_id'] = Auth::user()->instansi_id;
        $tim = TimSatgas::create($data);

        return redirect()->route('instansi.tim-satgas.show', $tim)->with('success', 'Tim Satgas berhasil ditambahkan.');
    }

    public function edit(TimSatgas $timSatgas)
    {
        abort_if($timSatgas->instansi_id !== Auth::user()->instansi_id, 403);

        return view('instansi.tim-satgas.edit', ['tim' => $timSatgas]);
    }

    public function update(Request $request, TimSatgas $timSatgas)
    {
        abort_if($timSatgas->instansi_id !== Auth::user()->instansi_id, 403);
        $timSatgas->update($this->validated($request));

        return redirect()->route('instansi.tim-satgas.show', $timSatgas)->with('success', 'Tim Satgas berhasil diperbarui.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_tim'       => 'required|string|max:255',
            'ketua'          => 'required|string|max:255',
            'kontak'         => 'required|string|max:30',
            'jumlah_anggota' => 'required|integer|min:0',
            'status'         => 'required|in:aktif,nonaktif',
        ]);
    }
}
