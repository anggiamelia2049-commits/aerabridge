<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'semua');

        if (!in_array($filter, ['semua', 'aktif', 'prioritas', 'selesai'])) {
            $filter = 'semua';
        }

        // Mengambil penugasan milik petugas yang login
        $semua = Penugasan::with([
                'laporan.kategori',
            ])
            ->where('petugas_id', Auth::id())
            ->whereHas('laporan')
            ->latest('tanggal_penugasan')
            ->get();

        // Tugas aktif
        $aktif = $semua->whereIn('status', [
            'ditugaskan',
            'dalam_proses',
        ]);

        // Statistik dashboard
        $jumlahAktif = $aktif->count();

        $jumlahPrioritas = $aktif->filter(function ($penugasan) {
            return $penugasan->laporan->tingkat_prioritas === 'Kritis';
        })->count();

        $jumlahSelesai = $semua
            ->where('status', 'selesai')
            ->count();

        $jumlahHarian = $aktif->filter(function ($penugasan) {
            $tanggal = $penugasan->tanggal_penugasan;
            $batasSla = $penugasan->batasWaktuSla();

            return ($tanggal && $tanggal->isToday())
                || ($batasSla && $batasSla->isToday());
        })->count();

        // Data tugas untuk peta dan detail
        $dataTugas = $semua
            ->whereIn('status', [
                'ditugaskan',
                'dalam_proses',
                'selesai',
            ])
            ->map(function ($penugasan) {
                $laporan = $penugasan->laporan;

                $daftarPrioritas = [
                    'Kritis' => [
                        'label' => 'Tinggi',
                        'warna' => '#E53935',
                        'teks' => '#FFFFFF',
                    ],
                    'Sedang' => [
                        'label' => 'Sedang',
                        'warna' => '#FFC107',
                        'teks' => '#4A4A4A',
                    ],
                    'Rendah' => [
                        'label' => 'Rendah',
                        'warna' => '#4CAF50',
                        'teks' => '#FFFFFF',
                    ],
                ];

                $prioritas = $daftarPrioritas[$laporan->tingkat_prioritas]
                    ?? [
                        'label' => '-',
                        'warna' => '#9E9E9E',
                        'teks' => '#FFFFFF',
                    ];

                $lat = $laporan->latitude !== null
                    ? (float) $laporan->latitude
                    : null;

                $lng = $laporan->longitude !== null
                    ? (float) $laporan->longitude
                    : null;

                $tujuan = ($lat !== null && $lng !== null)
                    ? $lat . ',' . $lng
                    : null;

                $urlRute = $tujuan
                    ? 'https://www.google.com/maps/dir/?api=1&destination=' . $tujuan
                    : null;

                return [
                    'id' => $penugasan->id,
                    'judul' => $laporan->judul,
                    'alamat' => $laporan->alamat ?: ('Kec. ' . ($laporan->kecamatan ?? '-')),
                    'kategori' => $laporan->kategori->nama_kategori ?? '-',

                    'prioritas' => $prioritas['label'],
                    'warna' => $prioritas['warna'],
                    'teks' => $prioritas['teks'],
                    'kritis' => $laporan->tingkat_prioritas === 'Kritis',

                    'grup' => $penugasan->status === 'selesai'
                        ? 'selesai'
                        : 'aktif',

                    'baru' => $penugasan->status === 'ditugaskan',

                    'lat' => $lat,
                    'lng' => $lng,

                    'foto' => $laporan->lampiran
    ? asset('storage/' . $laporan->lampiran)
    : null,

                    'detail' => route(
                        'petugas.penugasan.show',
                        $penugasan->id
                    ),

                    'rute' => $urlRute,

                    'navigasi' => $urlRute
                        ? $urlRute . '&travelmode=driving&dir_action=navigate'
                        : null,
                ];
            })
            ->values();

        // Maksimal lima tugas baru
        $tugasBaru = $dataTugas
            ->where('baru', true)
            ->take(5)
            ->values();

        // Menentukan tugas terpilih sesuai tab
        $sesuaiTab = $dataTugas->filter(function ($tugas) use ($filter) {
            if ($filter === 'aktif') {
                return $tugas['grup'] === 'aktif';
            }

            if ($filter === 'prioritas') {
                return $tugas['grup'] === 'aktif'
                    && $tugas['kritis'];
            }

            if ($filter === 'selesai') {
                return $tugas['grup'] === 'selesai';
            }

            return true;
        });

        $terpilih = $sesuaiTab->firstWhere('baru', true)
            ?? $sesuaiTab->firstWhere('grup', 'aktif')
            ?? $sesuaiTab->first();

        return view('petugas.dashboard', compact(
            'filter',
            'jumlahAktif',
            'jumlahPrioritas',
            'jumlahSelesai',
            'jumlahHarian',
            'dataTugas',
            'tugasBaru',
            'terpilih'
        ));
    }
}