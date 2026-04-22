<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $isMinggu = Carbon::now()->isSunday();
        $kelas = DB::table('kelas')->get();
        return view('dashboard_guru.absensi', compact('kelas', 'isMinggu'));
    }

    public function store(Request $request)
    {
        $request->validate(['id_kelas' => 'required', 'absensi' => 'required|array']);

        if (Carbon::now()->isSunday()) {
            return redirect()->back()->with('error', 'Tidak dapat melakukan absensi di hari Minggu.');
        }

        $guru = DB::table('guru')
            ->join('relasi_guru', 'guru.id', '=', 'relasi_guru.id_guru')
            ->where('relasi_guru.id_user', Auth::id())
            ->select('guru.*')
            ->first();
        
        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan. Pastikan relasi user dan guru sudah diatur.');
        }

        try {
            DB::transaction(function () use ($request, $guru) {
                foreach ($request->absensi as $id_murid => $data) {
                    DB::table('absensi_murid')->updateOrInsert(
                        ['id_murid' => $id_murid, 'tanggal' => Carbon::now()->format('Y-m-d')],
                        [
                            'id_guru'    => $guru->id, 
                            'status'     => $data['status'],
                            'keterangan' => $data['keterangan'] ?? null,
                            'updated_at' => now(),
                            'created_at' => now()
                        ]
                    );
                }
            });
            return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function getArsip(Request $request)
    {
        $id_kelas = $request->id_kelas;
        $search = $request->search;
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $query = DB::table('murid_kelas')
            ->join('murid', 'murid_kelas.id_murid', '=', 'murid.id')
            ->leftJoin('absensi_murid', function($join) use ($bulan, $tahun) {
                $join->on('murid.id', '=', 'absensi_murid.id_murid')
                     ->whereMonth('absensi_murid.tanggal', $bulan)
                     ->whereYear('absensi_murid.tanggal', $tahun);
            })
            ->where('murid_kelas.id_kelas', $id_kelas);

        if (!empty($search)) {
            $query->where('murid.nama_lengkap', 'like', '%' . $search . '%');
        }

        $murid = $query->select(
                'murid.id', 
                'murid.nama_lengkap',
                DB::raw("SUM(CASE WHEN absensi_murid.status = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
                DB::raw("SUM(CASE WHEN absensi_murid.status = 'tidak_hadir' THEN 1 ELSE 0 END) as total_tidak_hadir")
            )
            ->groupBy('murid.id', 'murid.nama_lengkap')
            ->get();

        return response()->json($murid);
    }

    public function getRekapMurid(Request $request)
    {
        $bulan = (int) ($request->bulan ?? date('m'));
        $tahun = (int) ($request->tahun ?? date('Y'));
        $id_murid = $request->id_murid;

        if (!$id_murid) {
            return response()->json(['error' => 'ID Murid tidak ditemukan'], 400);
        }

        try {
            $dateContext = Carbon::createFromDate($tahun, $bulan, 1);
            $daysInMonth = $dateContext->daysInMonth;
            
            $absensi = DB::table('absensi_murid')
                ->where('id_murid', $id_murid)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->get()
                ->keyBy(function($item) {
                    return (int)Carbon::parse($item->tanggal)->format('d');
                });

            $rekap = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $checkDate = Carbon::createFromDate($tahun, $bulan, $i);
                
                if ($checkDate->isSunday()) {
                    $status = 'libur';
                } else {
                    $status = isset($absensi[$i]) ? $absensi[$i]->status : 'none';
                }
                
                $rekap[] = [
                    'tgl' => $i,
                    'status' => $status
                ];
            }

            return response()->json($rekap);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan pada server',
                'debug' => $e->getMessage()
            ], 500);
        }
    }
}