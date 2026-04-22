<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $sekolah->nama_sekolah ?? 'Website Sekolah')</title>
    
    <link rel="icon" type="image/png" href="{{ asset($sekolah->logo ?? '') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-green: #198754;
            --dark-green: #0b4629;
            --soft-green: #f0fdf4;
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #2d3436; background-color: #ffffff; scroll-behavior: smooth; }

        /* NAVBAR STYLING */
        .navbar { padding: 12px 0; background: rgba(255, 255, 255, 0.9) !important; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,0,0,0.05); }
        .nav-link { font-weight: 600; font-size: 0.95rem; color: #4b5563 !important; margin: 0 8px; }
        .btn-login { border: 2px solid var(--primary-green); color: var(--primary-green) !important; border-radius: 12px; padding: 7px 18px; font-weight: 700; transition: 0.3s; }
        .btn-login:hover { background-color: var(--primary-green); color: white !important; }

        /* FOOTER STYLING */
        footer { background-color: #0f172a; color: #94a3b8; padding: 80px 0 30px; border-radius: 50px 50px 0 0; }
        footer h5 { color: white; font-weight: 700; }

        /* SHAREABLE SECTION STYLING */
        .section-title { font-weight: 800; font-size: 2.2rem; color: var(--dark-green); margin-bottom: 1.5rem; }
        /* Pastikan iframe mengisi seluruh area container */
        .maps-container iframe {
            width: 100% !important;
            height: 100% !important;
            border: 0;
        }
    </style>
    @yield('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset($sekolah->logo ?? '') }}" alt="Logo" width="40" class="me-2">
                <span class="fw-bold text-success" style="font-size: 16px;">{{ $sekolah->nama_sekolah ?? 'SEKOLAH KAMI' }}</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list text-success fs-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#profil">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#prestasi">Prestasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#program">Program</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#artikel">Artikel</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-login shadow-sm" href="{{ route('login') }}">
                            <i class="bi bi-person-circle me-1"></i> Log In
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer>
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset($sekolah->logo ?? '') }}" width="50" class="me-3 bg-white p-1 rounded-circle">
                        <h5 class="mb-0">{{ $sekolah->nama_sekolah ?? '' }}</h5>
                    </div>
                    <!--<p class="small lh-lg">{{ $sekolah->deskripsi ?? '' }}</p>-->
                </div>
                <div class="col-lg-4">
                    <h5>Informasi Kontak</h5>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-3 d-flex align-items-start"><i class="bi bi-geo-alt-fill text-success me-3 fs-5"></i> {{ $sekolah->alamat ?? '' }}</li>
                        <li class="mb-3 d-flex align-items-start"><i class="bi bi-telephone-fill text-success me-3 fs-5"></i> {{ $sekolah->no_hp ?? '' }}</li>
                        <li class="mb-3 d-flex align-items-start"><i class="bi bi-envelope-fill text-success me-3 fs-5"></i> {{ $sekolah->email ?? '' }}</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5>Lokasi Kami</h5>
                    <div class="rounded-4 overflow-hidden shadow-lg mt-4 bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                        @if(!empty($sekolah->tautan_google_maps))
                            <div class="maps-container w-100 h-100">
                                {!! $sekolah->tautan_google_maps !!}
                            </div>
                        @else
                            <div class="text-center p-4">
                                <i class="bi bi-map text-secondary fs-1 mb-2"></i>
                                <p class="small text-secondary mb-0">Lokasi belum tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!--<div class="border-top border-secondary mt-5 pt-4 text-center">-->
                <!--<p class="mb-0 small opacity-50">&copy; {{ date('Y') }} {{ $sekolah->nama_sekolah ?? '' }}. All Rights Reserved.</p>-->
            <!--</div>-->
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>