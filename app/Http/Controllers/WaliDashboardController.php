<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WaliDashboardController extends Controller
{
    public function index()
    {
        // Alur Join: relasi_wali -> wali_murid -> murid
        $dataWali = DB::table('relasi_wali')
            ->join('wali_murid', 'relasi_wali.id_wali', '=', 'wali_murid.id')
            ->join('murid', 'wali_murid.id_murid', '=', 'murid.id')
            ->where('relasi_wali.id_user', Auth::id()) // Mencari berdasarkan id_user di tabel relasi
            ->select(
                'murid.nama_lengkap', 
                'murid.nisn', 
                'murid.id as murid_id'
            )
            ->first();

        return view('dashboard_wali.index', compact('dataWali'));
    }
}