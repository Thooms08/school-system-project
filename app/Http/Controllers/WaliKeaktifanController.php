<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WaliKeaktifanController extends Controller
{
    public function index()
    {
        // Menampilkan view utama
        return view('dashboard_wali.keaktifan');
    }

    public function getKeaktifanData(Request $request)
    {
        try {
            // 1. Dapatkan filter tanggal (default hari ini)
            $tanggal = $request->get('tanggal', date('Y-m-d'));

            // 2. Cari data Murid yang terhubung dengan Wali (User) yang sedang login
            $murid = DB::table('relasi_wali')
                ->join('wali_murid', 'relasi_wali.id_wali', '=', 'wali_murid.id')
                ->join('murid', 'wali_murid.id_murid', '=', 'murid.id')
                ->leftJoin('murid_kelas', 'murid.id', '=', 'murid_kelas.id_murid')
                ->leftJoin('kelas', 'murid_kelas.id_kelas', '=', 'kelas.id')
                ->where('relasi_wali.id_user', Auth::id())
                ->select('murid.id', 'murid.nama_lengkap', 'kelas.nama_kelas')
                ->first();

            if (!$murid) {
                return response()->json(['error' => 'Data ananda tidak ditemukan'], 404);
            }

            // 3. Ambil riwayat keaktifan berdasarkan ID Murid dan Tanggal
            $listKeaktifan = DB::table('keaktifan_murid')
                ->join('keaktifans', 'keaktifan_murid.keaktifan_id', '=', 'keaktifans.id')
                ->where('keaktifan_murid.murid_id', $murid->id)
                ->whereDate('keaktifans.tanggal', $tanggal)
                ->select(
                    'keaktifans.nama_keaktifan',
                    'keaktifans.tanggal',
                    'keaktifans.foto',
                    'keaktifans.keterangan',
                    'keaktifan_murid.is_active'
                )
                ->orderBy('keaktifans.created_at', 'desc')
                ->get();

            // 4. Hitung Statistik untuk Chart Bar
            $aktif = $listKeaktifan->where('is_active', 1)->count();
            $tidakAktif = $listKeaktifan->where('is_active', 0)->count();

            $chartData = [
                'labels' => ['Aktif (✔)', 'Tidak Aktif (✖)'],
                'datasets' => [[
                    'label' => 'Jumlah Keaktifan',
                    'data' => [$aktif, $tidakAktif],
                    'backgroundColor' => ['#0d6efd', '#dc3545'], // Biru & Merah
                    'borderRadius' => 8
                ]]
            ];

            return response()->json([
                'murid' => $murid,
                'keaktifan' => $listKeaktifan,
                'chart' => $chartData,
                'summary' => [
                    'aktif' => $aktif,
                    'tidak_aktif' => $tidakAktif
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}