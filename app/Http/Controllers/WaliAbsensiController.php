<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WaliAbsensiController extends Controller
{
   public function index(Request $request)
{
    // Pastikan input dikonversi menjadi Integer (angka)
    $bulan = (int) $request->get('bulan', date('m'));
    $tahun = (int) $request->get('tahun', date('Y'));

    // Ambil Data Murid (Query tetap sama)
    $murid = DB::table('relasi_wali')
        ->join('wali_murid', 'relasi_wali.id_wali', '=', 'wali_murid.id')
        ->join('murid', 'wali_murid.id_murid', '=', 'murid.id')
        ->leftJoin('murid_kelas', 'murid.id', '=', 'murid_kelas.id_murid')
        ->leftJoin('kelas', 'murid_kelas.id_kelas', '=', 'kelas.id')
        ->where('relasi_wali.id_user', Auth::id())
        ->select('murid.id', 'murid.nama_lengkap', 'murid.nisn', 'kelas.nama_kelas')
        ->first();

    if (!$murid) {
        return redirect()->route('wali.home')->with('error', 'Data murid tidak ditemukan.');
    }

    // Hitung Rekap (Query tetap sama)
    $rekap = DB::table('absensi_murid')
        ->where('id_murid', $murid->id)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->select(
            DB::raw("SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as total_hadir"),
            DB::raw("SUM(CASE WHEN status != 'Hadir' THEN 1 ELSE 0 END) as total_tidak_hadir")
        )
        ->first();

    return view('dashboard_wali.absen', compact('murid', 'rekap', 'bulan', 'tahun'));
}
    public function getCalendarData(Request $request)
    {
        $muridId = $request->id_murid;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Ambil semua data absen di bulan tersebut
        $dataAbsen = DB::table('absensi_murid')
            ->where('id_murid', $muridId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->pluck('status', 'tanggal')
            ->toArray();

        return response()->json($dataAbsen);
    }
}