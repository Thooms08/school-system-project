<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($murid) ? __('dashboard.edit_student_data_title', ['name' => $murid->nama_lengkap]) : __('dashboard.new_student_ppdb_admin') }}</title>
    @if(isset($sekolah->logo))
    <link rel="icon" type="image/png" href="{{ asset($sekolah->logo) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('assets/img/default-favicon.png') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .wrapper { display: flex; width: 100%; }
        #content { width: 100%; padding: 20px 30px; }
        .card { border: none; border-radius: 15px; }
        .step-header { border-bottom: 2px solid #eee; margin-bottom: 25px; padding-bottom: 10px; }
        .btn-success { background-color: #198754; border: none; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        @include('dashboard_admin.sidebar_admin')
        <div id="content">
            <div class="container-fluid">
                <h4 class="fw-bold text-success mb-4">
                    {{ isset($murid) ? __('dashboard.edit_student_data_title', ['name' => $murid->nama_lengkap]) : __('dashboard.new_student_ppdb_admin') }}
                </h4>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ isset($murid) ? route('murid.update', $murid->id) : route('murid.store') }}" method="POST">
                    @csrf
                    @if(isset($murid)) @method('PUT') @endif

                    <div id="step1" class="card p-4">
                        <h5 class="step-header text-success fw-bold"><i class="bi bi-1-circle-fill me-2"></i>{{ __('dashboard.student_data_step') }}</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('dashboard.full_name') }}</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $murid->nama_lengkap ?? '') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('dashboard.gender_label') }}</label>
                                <select name="jenis_kelamin" class="form-select">
                                    <option value="L" {{ (old('jenis_kelamin', $murid->jenis_kelamin ?? '') == 'L') ? 'selected' : '' }}>{{ __('dashboard.male_option') }}</option>
                                    <option value="P" {{ (old('jenis_kelamin', $murid->jenis_kelamin ?? '') == 'P') ? 'selected' : '' }}>{{ __('dashboard.female_option') }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('dashboard.nisn_10') }}</label>
                                <input type="text" name="nisn" class="form-control" maxlength="10" value="{{ old('nisn', $murid->nisn ?? '') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('dashboard.nik') }}</label>
                                <input type="text" name="nik" class="form-control" value="{{ old('nik', $murid->nik ?? '') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('dashboard.birth_place') }}</label>
                                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $murid->tempat_lahir ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('dashboard.birth_date') }}</label>
                                <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', $murid->tgl_lahir ?? '') }}">
                            </div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.rt_rw') }}</label><input type="text" name="rt_rw" class="form-control" value="{{ old('rt_rw', $murid->rt_rw ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.village') }}</label><input type="text" name="desa_kelurahan" class="form-control" value="{{ old('desa_kelurahan', $murid->desa_kelurahan ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.city') }}</label><input type="text" name="kota_kabupaten" class="form-control" value="{{ old('kota_kabupaten', $murid->kota_kabupaten ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.province') }}</label><input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $murid->provinsi ?? '') }}"></div>
                            <div class="col-12"><label class="form-label">{{ __('dashboard.address_detail_label') }}</label><textarea name="alamat_detail" class="form-control" rows="2">{{ old('alamat_detail', $murid->alamat_detail ?? '') }}</textarea></div>
                            <div class="col-md-4"><label class="form-label">{{ __('dashboard.transportation') }}</label><input type="text" name="transportasi" class="form-control" value="{{ old('transportasi', $murid->transportasi ?? '') }}"></div>
                            <div class="col-md-4"><label class="form-label">{{ __('dashboard.phone_wa') }}</label><input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $murid->no_hp ?? '') }}" required></div>
                            <div class="col-md-4"><label class="form-label">{{ __('general.email') }}</label><input type="email" name="alamat_email" class="form-control" value="{{ old('alamat_email', $murid->alamat_email ?? '') }}" required></div>
                            <div class="col-md-6"><label class="form-label">{{ __('dashboard.origin_school_label') }}</label><input type="text" name="sekolah_asal" class="form-control" value="{{ old('sekolah_asal', $murid->sekolah_asal ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.height_cm') }}</label><input type="number" step="0.1" name="tinggi_badan" class="form-control" value="{{ old('tinggi_badan', $murid->tinggi_badan ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.weight_kg') }}</label><input type="number" step="0.1" name="berat_badan" class="form-control" value="{{ old('berat_badan', $murid->berat_badan ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.child_order') }}</label><input type="number" name="anak_ke" class="form-control" value="{{ old('anak_ke', $murid->anak_ke ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.siblings_count') }}</label><input type="number" name="jml_saudara" class="form-control" value="{{ old('jml_saudara', $murid->jml_saudara ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.older_siblings') }}</label><input type="number" name="jumlah_kakak" class="form-control" value="{{ old('jumlah_kakak', $murid->jumlah_kakak ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.younger_siblings') }}</label><input type="number" name="jumlah_adik" class="form-control" value="{{ old('jumlah_adik', $murid->jumlah_adik ?? '') }}"></div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-success px-5 py-2" onclick="showStep2()">{{ __('dashboard.next_btn') }} <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>

                    <div id="step2" class="card p-4 hidden">
                        <h5 class="step-header text-success fw-bold"><i class="bi bi-2-circle-fill me-2"></i>{{ __('dashboard.guardian_data_step') }}</h5>
                        <div class="row g-3">
                            <h6 class="fw-bold text-muted">{{ __('dashboard.father_data_title') }}</h6>
                            <div class="col-md-4"><label class="form-label">{{ __('dashboard.father_name') }}</label><input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $murid->wali->nama_ayah ?? '') }}" required></div>
                            <div class="col-md-4"><label class="form-label">Tempat Lahir</label><input type="text" name="tempat_lahir_ayah" class="form-control" value="{{ old('tempat_lahir_ayah', $murid->wali->tempat_lahir_ayah ?? '') }}"></div>
                            <div class="col-md-4"><label class="form-label">Tgl Lahir</label><input type="date" name="tgl_lahir_ayah" class="form-control" value="{{ old('tgl_lahir_ayah', $murid->wali->tgl_lahir_ayah ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.education') }}</label><input type="text" name="pendidikan_ayah" class="form-control" value="{{ old('pendidikan_ayah', $murid->wali->pendidikan_ayah ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.occupation') }}</label><input type="text" name="pekerjaan_ayah" class="form-control" value="{{ old('pekerjaan_ayah', $murid->wali->pekerjaan_ayah ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.income_label') }}</label><input type="number" name="penghasilan_ayah" class="form-control" value="{{ old('penghasilan_ayah', $murid->wali->penghasilan_ayah ?? '') }}"></div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('dashboard.status_label') }}</label>
                                <select name="status_ayah" class="form-select">
                                    <option value="hidup" {{ (old('status_ayah', $murid->wali->status_ayah ?? '') == 'hidup') ? 'selected' : '' }}>{{ __('dashboard.alive') }}</option>
                                    <option value="meninggal" {{ (old('status_ayah', $murid->wali->status_ayah ?? '') == 'meninggal') ? 'selected' : '' }}>{{ __('dashboard.deceased') }}</option>
                                </select>
                            </div>
                            <hr>
                            <h6 class="fw-bold text-muted">{{ __('dashboard.mother_data_title') }}</h6>
                            <div class="col-md-4"><label class="form-label">{{ __('dashboard.mother_name') }}</label><input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $murid->wali->nama_ibu ?? '') }}" required></div>
                            <div class="col-md-4"><label class="form-label">Tempat Lahir</label><input type="text" name="tempat_lahir_ibu" class="form-control" value="{{ old('tempat_lahir_ibu', $murid->wali->tempat_lahir_ibu ?? '') }}"></div>
                            <div class="col-md-4"><label class="form-label">Tgl Lahir</label><input type="date" name="tgl_lahir_ibu" class="form-control" value="{{ old('tgl_lahir_ibu', $murid->wali->tgl_lahir_ibu ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.education') }}</label><input type="text" name="pendidikan_ibu" class="form-control" value="{{ old('pendidikan_ibu', $murid->wali->pendidikan_ibu ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.occupation') }}</label><input type="text" name="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu', $murid->wali->pekerjaan_ibu ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label">{{ __('dashboard.income_label') }}</label><input type="number" name="penghasilan_ibu" class="form-control" value="{{ old('penghasilan_ibu', $murid->wali->penghasilan_ibu ?? '') }}"></div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status_ibu" class="form-select">
                                    <option value="hidup" {{ (old('status_ibu', $murid->wali->status_ibu ?? '') == 'hidup') ? 'selected' : '' }}>{{ __('dashboard.alive') }}</option>
                                    <option value="meninggal" {{ (old('status_ibu', $murid->wali->status_ibu ?? '') == 'meninggal') ? 'selected' : '' }}>{{ __('dashboard.deceased') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4" onclick="showStep1()"><i class="bi bi-arrow-left"></i> {{ __('dashboard.back_btn') }}</button>
                            <button type="submit" class="btn btn-success px-5">
                                {{ isset($murid) ? __('general.save_changes') : __('dashboard.add_student_submit') }} <i class="bi bi-check-circle"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showStep2() {
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
            window.scrollTo(0,0);
        }
        function showStep1() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
            window.scrollTo(0,0);
        }
    </script>
</body>
</html>