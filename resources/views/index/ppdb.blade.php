<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('dashboard.ppdb_form_title') }}</title>
    @if(isset($sekolah->logo))
    <link rel="icon" type="image/png" href="{{ asset($sekolah->logo) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('assets/img/default-favicon.png') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .ppdb-container { max-width: 950px; margin: 40px auto; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .step-header { border-bottom: 2px solid #eee; margin-bottom: 25px; padding-bottom: 10px; }
        .btn-success { background-color: #198754; border: none; padding: 12px 30px; font-weight: bold; border-radius: 10px; }
        .hidden { display: none; }
        .form-label { font-weight: 600; color: #444; font-size: 0.9rem; }
        .form-control, .form-select { border-radius: 8px; padding: 10px; border: 1px solid #ddd; }
        .form-control:focus { border-color: #198754; box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.1); }
    </style>
</head>
<body>
    <div class="container ppdb-container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-success">{{ __('dashboard.ppdb_form_title') }}</h2>
            <p class="text-muted">{{ __('dashboard.ppdb_form_subtitle') }}</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        

        <form action="{{ route('ppdb.store') }}" method="POST">
            @csrf

            <div id="step1" class="card p-4">
                <h5 class="step-header text-success fw-bold"><i class="bi bi-1-circle-fill me-2"></i>{{ __('dashboard.step1_title') }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('dashboard.full_name') }}</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('dashboard.gender_label') }}</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>{{ __('dashboard.male_option') }}</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>{{ __('dashboard.female_option') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('dashboard.nisn_10') }}</label>
                        <input type="text" name="nisn" class="form-control" maxlength="10" value="{{ old('nisn') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('dashboard.nik') }}</label>
                        <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('dashboard.birth_place') }}</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('dashboard.birth_date') }}</label>
                        <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir') }}">
                    </div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.rt_rw') }}</label><input type="text" name="rt_rw" class="form-control" value="{{ old('rt_rw') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.village') }}</label><input type="text" name="desa_kelurahan" class="form-control" value="{{ old('desa_kelurahan') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.city') }}</label><input type="text" name="kota_kabupaten" class="form-control" value="{{ old('kota_kabupaten') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.province') }}</label><input type="text" name="provinsi" class="form-control" value="{{ old('provinsi') }}"></div>
                    <div class="col-12"><label class="form-label">{{ __('dashboard.address_detail_label') }}</label><textarea name="alamat_detail" class="form-control" rows="2" placeholder="{{ __('dashboard.address_placeholder') }}">{{ old('alamat_detail') }}</textarea></div>
                    <div class="col-md-4"><label class="form-label">{{ __('dashboard.transportation') }}</label><input type="text" name="transportasi" class="form-control" value="{{ old('transportasi') }}"></div>
                    <div class="col-md-4"><label class="form-label">{{ __('dashboard.phone_wa') }}</label><input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required></div>
                    <div class="col-md-4"><label class="form-label">{{ __('dashboard.active_email') }}</label><input type="email" name="alamat_email" class="form-control" value="{{ old('alamat_email') }}" required></div>
                    <div class="col-md-6"><label class="form-label">{{ __('dashboard.origin_school_label') }}</label><input type="text" name="sekolah_asal" class="form-control" value="{{ old('sekolah_asal') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.height_cm') }}</label><input type="number" step="0.1" name="tinggi_badan" class="form-control" value="{{ old('tinggi_badan') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.weight_kg') }}</label><input type="number" step="0.1" name="berat_badan" class="form-control" value="{{ old('berat_badan') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.child_order') }}</label><input type="number" name="anak_ke" class="form-control" value="{{ old('anak_ke') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.siblings_count') }}</label><input type="number" name="jml_saudara" class="form-control" value="{{ old('jml_saudara') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.older_siblings') }}</label><input type="number" name="jumlah_kakak" class="form-control" value="{{ old('jumlah_kakak') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.younger_siblings') }}</label><input type="number" name="jumlah_adik" class="form-control" value="{{ old('jumlah_adik') }}"></div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="/" class="btn btn-outline-secondary px-4 d-flex align-items-center rounded-pill">
                        <i class="bi bi-arrow-left me-2"></i> {{ __('dashboard.cancel_btn') }}
                    </a>
                    <button type="button" class="btn btn-success px-5 rounded-pill shadow-sm" onclick="showStep2()">
                        {{ __('dashboard.next_guardian_data') }} <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>

            <div id="step2" class="card p-4 hidden">
                <h5 class="step-header text-success fw-bold"><i class="bi bi-2-circle-fill me-2"></i>{{ __('dashboard.step2_title') }}</h5>
                <div class="row g-3">
                    <h6 class="fw-bold text-muted border-start border-success border-3 ps-2">{{ __('dashboard.father_data_title') }}</h6>
                    <div class="col-md-4"><label class="form-label">{{ __('dashboard.father_name') }}</label><input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah') }}" required></div>
                    <div class="col-md-4"><label class="form-label">Tempat Lahir</label><input type="text" name="tempat_lahir_ayah" class="form-control" value="{{ old('tempat_lahir_ayah') }}"></div>
                    <div class="col-md-4"><label class="form-label">Tgl Lahir</label><input type="date" name="tgl_lahir_ayah" class="form-control" value="{{ old('tgl_lahir_ayah') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.education') }}</label><input type="text" name="pendidikan_ayah" class="form-control" value="{{ old('pendidikan_ayah') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.occupation') }}</label><input type="text" name="pekerjaan_ayah" class="form-control" value="{{ old('pekerjaan_ayah') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.income_label') }}</label><input type="number" name="penghasilan_ayah" class="form-control" value="{{ old('penghasilan_ayah') }}"></div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('dashboard.status_label') }}</label>
                        <select name="status_ayah" class="form-select">
                            <option value="hidup" {{ old('status_ayah') == 'hidup' ? 'selected' : '' }}>{{ __('dashboard.alive') }}</option>
                            <option value="meninggal" {{ old('status_ayah') == 'meninggal' ? 'selected' : '' }}>{{ __('dashboard.deceased') }}</option>
                        </select>
                    </div>

                    <hr class="my-4">
                    
                    <h6 class="fw-bold text-muted border-start border-success border-3 ps-2">{{ __('dashboard.mother_data_title') }}</h6>
                    <div class="col-md-4"><label class="form-label">{{ __('dashboard.mother_name') }}</label><input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu') }}" required></div>
                    <div class="col-md-4"><label class="form-label">Tempat Lahir</label><input type="text" name="tempat_lahir_ibu" class="form-control" value="{{ old('tempat_lahir_ibu') }}"></div>
                    <div class="col-md-4"><label class="form-label">Tgl Lahir</label><input type="date" name="tgl_lahir_ibu" class="form-control" value="{{ old('tgl_lahir_ibu') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.education') }}</label><input type="text" name="pendidikan_ibu" class="form-control" value="{{ old('pendidikan_ibu') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.occupation') }}</label><input type="text" name="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu') }}"></div>
                    <div class="col-md-3"><label class="form-label">{{ __('dashboard.income_label') }}</label><input type="number" name="penghasilan_ibu" class="form-control" value="{{ old('penghasilan_ibu') }}"></div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status_ibu" class="form-select">
                            <option value="hidup" {{ old('status_ibu') == 'hidup' ? 'selected' : '' }}>{{ __('dashboard.alive') }}</option>
                            <option value="meninggal" {{ old('status_ibu') == 'meninggal' ? 'selected' : '' }}>{{ __('dashboard.deceased') }}</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-5">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" onclick="showStep1()">
                        <i class="bi bi-arrow-left me-2"></i> {{ __('dashboard.back_btn') }}
                    </button>
                    <button type="submit" class="btn btn-success px-5 rounded-pill shadow-sm">
                        {{ __('dashboard.submit_registration') }} <i class="bi bi-send-fill ms-2"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function showStep2() {
            // Sederhana: scroll ke atas saat ganti step
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