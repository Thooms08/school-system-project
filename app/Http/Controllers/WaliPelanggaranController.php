<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WaliPelanggaranController extends Controller
{
    public function index()
    {
        return view('dashboard_wali.pelanggaran');
    }

    public function getPelanggaranData(Request $request)
{
    try {
        $range = $request->get('range', '1_bulan');
        $tanggal = $request->get('tanggal');
        
        // 1. Ambil Profil Murid
        $murid = DB::table('relasi_wali')
            ->join('wali_murid', 'relasi_wali.id_wali', '=', 'wali_murid.id')
            ->join('murid', 'wali_murid.id_murid', '=', 'murid.id')
            ->leftJoin('murid_kelas', 'murid.id', '=', 'murid_kelas.id_murid')
            ->leftJoin('kelas', 'murid_kelas.id_kelas', '=', 'kelas.id')
            ->where('relasi_wali.id_user', Auth::id())
            ->select('murid.id', 'murid.nama_lengkap', 'kelas.nama_kelas')
            ->first();

        if (!$murid) {
            return response()->json(['error' => 'Data murid tidak ditemukan'], 404);
        }

        // 2. Query Pelanggaran - Menggunakan created_at sebagai pengganti tanggal
        $query = DB::table('pelanggaran_murid')
            ->join('aturan_pelanggaran', 'pelanggaran_murid.id_aturan_pelanggaran', '=', 'aturan_pelanggaran.id')
            ->where('pelanggaran_murid.id_murid', $murid->id);

        // Filter Waktu (Menggunakan kolom created_at)
        if ($tanggal) {
            $query->whereDate('pelanggaran_murid.created_at', $tanggal);
        } else {
            if ($range == '1_minggu') $query->where('pelanggaran_murid.created_at', '>=', now()->subWeek());
            if ($range == '1_bulan') $query->where('pelanggaran_murid.created_at', '>=', now()->subMonth());
            if ($range == '1_tahun') $query->where('pelanggaran_murid.created_at', '>=', now()->subYear());
        }

        $listPelanggaran = $query->select(
                'pelanggaran_murid.id',
                'pelanggaran_murid.keterangan',
                'pelanggaran_murid.created_at as tanggal', // Alias agar JS tidak perlu diubah
                'aturan_pelanggaran.nama_pelanggaran', 
                'aturan_pelanggaran.skor'
            )
            ->orderBy('pelanggaran_murid.created_at', 'desc')
            ->get();

        $totalSkor = $listPelanggaran->sum('skor');

        // 3. Data untuk Chart
        $chartData = [
            'labels' => [$totalSkor == 0 ? 'Kedisiplinan' : 'Poin Pelanggaran'],
            'datasets' => [[
                'data' => [$totalSkor == 0 ? 1 : $totalSkor],
                'backgroundColor' => [$totalSkor == 0 ? '#0d6efd' : '#dc3545']
            ]]
        ];

        return response()->json([
            'murid' => $murid,
            'pelanggaran' => $listPelanggaran,
            'total_skor' => $totalSkor,
            'chart' => $chartData
        ]);

    } catch (\Exception $e) {
        // Jika masih error 500, ini akan mengirimkan pesan error PHP-nya ke Browser
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}