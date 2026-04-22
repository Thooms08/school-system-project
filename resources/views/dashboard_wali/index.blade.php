<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Wali Murid</title>
     @if(isset($sekolah->logo))
    <link rel="icon" type="image/png" href="{{ asset($sekolah->logo) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('assets/img/default-favicon.png') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-green: #198754;
            --dark-green: #146c43;
            --soft-green: #e8f5e9;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: var(--primary-green) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .welcome-section {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 40px 0;
            border-radius: 0 0 30px 30px;
            margin-bottom: 30px;
        }

        .card-menu {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            height: 100%;
        }

        .card-menu:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(25, 135, 84, 0.2);
        }

        .icon-box {
            width: 70px;
            height: 70px;
            background-color: var(--soft-green);
            color: var(--primary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            font-size: 30px;
            margin-bottom: 20px;
        }

        .card-title {
            font-weight: 700;
            color: #333;
        }

        .btn-logout {
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark mb-0">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-mortarboard-fill me-2"></i>WALI MURDI PANEL</a>
            <form action="{{ route('logout') }}" method="POST" class="ms-auto">
                @csrf
                <button type="submit" class="btn btn-light btn-sm btn-logout text-success">
                    <i class="bi bi-box-arrow-right me-2"></i>Log Out
                </button>
            </form>
        </div>
    </nav>

    <div class="welcome-section shadow-sm">
        <div class="container text-center">
            <h2 class="fw-bold">Selamat Datang, Bapak/Ibu Wali Murid</h2>
            @if($dataWali)
                <p class="opacity-75">Memantau perkembangan <strong>{{ $dataWali->nama_lengkap }}</strong> ({{ $dataWali->nisn }})</p>
            @else
                <p class="opacity-75">Data murid tidak ditemukan. Hubungi admin sekolah.</p>
            @endif
        </div>
    </div>

    <div class="container mb-5">
        <div class="row g-4">
            <div class="col-md-4">
                <a href="{{ route('wali.absen.index') }}" class="text-decoration-none">
                    <div class="card card-menu p-4">
                        <div class="icon-box">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <h4 class="card-title">Absen Murid</h4>
                        <p class="text-muted small">Pantau kehadiran harian, sakit, izin, atau tanpa keterangan ananda.</p>
                        <div class="text-success fw-bold">
                            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                        </div>
                    </div>
                </a>
            </div>

           <div class="col-md-4">
                <a href="{{ route('wali.pelanggaran.index') }}" class="text-decoration-none">
                    <div class="card card-menu p-4">
                        <div class="icon-box" style="background-color: #fff3f3; color: #dc3545;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <h4 class="card-title">Pelanggaran Murid</h4>
                        <p class="text-muted small">Informasi poin pelanggaran, teguran, atau masalah kedisiplinan murid.</p>
                        <div class="text-danger fw-bold">
                            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('wali.keaktifan.index') }}" class="text-decoration-none">
                    <div class="card card-menu p-4">
                        <div class="icon-box" style="background-color: #e3f2fd; color: #0d6efd;">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h4 class="card-title">Keaktifan Murid</h4>
                        <p class="text-muted small">Rekap partisipasi dalam kelas, nilai keaktifan harian, dan prestasi.</p>
                        <div class="text-primary fw-bold">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>