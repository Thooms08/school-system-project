<?php

namespace App\Http\Controllers;

use App\Models\Murid;
use App\Models\WaliMurid;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MuridController extends Controller
{
    public function index()
    {
        $murids = Murid::where('status', 'konfirmasi')->get();
        return view('dashboard_admin.murid', compact('murids'));
    }

    public function create()
    {
        return view('dashboard_admin.form_ppdb');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Step 1: Murid
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required',
            'nisn' => 'required|string|size:10|unique:murid,nisn',
            'nik' => 'required|string|unique:murid,nik',
            'no_hp' => 'required',
            'alamat_email' => 'required|email',
            // Step 2: Wali
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            // Simpan Murid
            $murid = Murid::create($request->only([
                'nama_lengkap', 'jenis_kelamin', 'nisn', 'nik', 'tempat_lahir', 'tgl_lahir',
                'rt_rw', 'desa_kelurahan', 'kota_kabupaten', 'provinsi', 'alamat_detail',
                'transportasi', 'no_hp', 'alamat_email', 'sekolah_asal', 'tinggi_badan',
                'berat_badan', 'anak_ke', 'jml_saudara', 'jumlah_kakak', 'jumlah_adik'
            ]));

            // Simpan Wali Murid
            WaliMurid::create(array_merge(
                $request->only([
                    'nama_ayah', 'tempat_lahir_ayah', 'tgl_lahir_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'status_ayah',
                    'nama_ibu', 'tempat_lahir_ibu', 'tgl_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu', 'status_ibu'
                ]),
                ['id_murid' => $murid->id]
            ));
        });

        return redirect()->route('murid.index')->with('success', 'Murid Baru Berhasil Ditambahkan');
    }

    public function show($id)
    {
        $murid = Murid::with('wali')->findOrFail($id);
        return response()->json($murid);
    }

    public function destroy($id)
    {
        Murid::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data murid berhasil dihapus');
    }
    // Tambahkan fungsi ini di MuridController.php

public function edit($id)
{
    // Mengambil data murid beserta data walinya
    $murid = Murid::with('wali')->findOrFail($id);
    return view('dashboard_admin.form_ppdb', compact('murid'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'nisn' => 'required|string|size:10|unique:murid,nisn,' . $id,
        'nik' => 'required|string|unique:murid,nik,' . $id,
        'no_hp' => 'required',
        'alamat_email' => 'required|email',
        'nama_ayah' => 'required',
        'nama_ibu' => 'required',
    ]);

    DB::transaction(function () use ($request, $id) {
        $murid = Murid::findOrFail($id);
        $murid->update($request->only([
            'nama_lengkap', 'jenis_kelamin', 'nisn', 'nik', 'tempat_lahir', 'tgl_lahir',
            'rt_rw', 'desa_kelurahan', 'kota_kabupaten', 'provinsi', 'alamat_detail',
            'transportasi', 'no_hp', 'alamat_email', 'sekolah_asal', 'tinggi_badan',
            'berat_badan', 'anak_ke', 'jml_saudara', 'jumlah_kakak', 'jumlah_adik'
        ]));

        $murid->wali()->update($request->only([
            'nama_ayah', 'tempat_lahir_ayah', 'tgl_lahir_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'status_ayah',
            'nama_ibu', 'tempat_lahir_ibu', 'tgl_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu', 'status_ibu'
        ]));
    });

    return redirect()->route('murid.index')->with('success', 'Data Murid Berhasil Diperbarui');
}
public function downloadPDF($id)
    {
        // Ambil data murid dengan relasi walinya
        $murid = Murid::with('wali')->findOrFail($id);
        
        // Ambil data profil sekolah
        $sekolah = DB::table('profile_sekolah')->first();

        // Load view khusus untuk format PDF
        $pdf = Pdf::loadView('dashboard_admin.pdf_murid', compact('murid', 'sekolah'));

        // Atur ukuran kertas ke F4 atau A4 (Potrait)
        $pdf->setPaper('a4', 'portrait');

        // Download file dengan nama murid
        return $pdf->download('Formulir_Pendaftaran_' . $murid->nama_lengkap . '.pdf');
    }

    public function search(Request $request)
    {
        $query = $request->get('query') ?? $request->get('search');
        $muridQuery = Murid::query();

        if ($query) {
            $muridQuery->where(function($q) use ($query) {
                $q->where('nama_lengkap', 'LIKE', '%' . $query . '%')
                  ->orWhere('nisn', 'LIKE', '%' . $query . '%');
            });
        }

        $murids = $muridQuery->get();
        $output = "";

        if ($murids->count() > 0) {
            foreach ($murids as $m) {
                // Perhatikan: Menambahkan tombol PDF di sini agar hasil pencarian AJAX tetap memiliki tombol download
                $output .= '
                <tr>
                    <td class="fw-bold">' . $m->nama_lengkap . '</td>
                    <td>' . $m->nisn . '</td>
                    <td>' . $m->no_hp . '</td>
                    <td class="text-center">
                        <a href="' . route('murid.pdf', $m->id) . '" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                        <a href="' . route('murid.edit', $m->id) . '" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="' . route('murid.destroy', $m->id) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Hapus murid ini?\')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>';
            }
        } else {
            $output = '<tr><td colspan="4" class="text-center py-4 text-muted">Data tidak ditemukan</td></tr>';
        }
        return response($output);
    }
// app/Http/Controllers/MuridController.php

public function getMuridByKelas(Request $request)
{
    $kelas_id = $request->get('kelas_id');

    if (!$kelas_id) {
        return response()->json([]);
    }

    // Menggunakan DB Join karena data id_kelas berada di tabel murid_kelas
    $murids = DB::table('murid')
        ->join('murid_kelas', 'murid.id', '=', 'murid_kelas.id_murid')
        ->where('murid_kelas.id_kelas', $kelas_id)
        ->select('murid.id', 'murid.nama_lengkap', 'murid.nisn')
        ->orderBy('murid.nama_lengkap', 'asc')
        ->get();

    return response()->json($murids);
}
}