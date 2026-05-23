<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('general.language_settings') }}</title>
    @if(isset($sekolah->logo))
    <link rel="icon" type="image/png" href="{{ asset($sekolah->logo) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('assets/img/default-favicon.png') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-green: #198754; --dark-green: #146c43; }
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; overflow-x: hidden; }
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        #content { width: 100%; padding: 20px 30px; transition: all 0.3s; min-height: 100vh; }
        #sidebarCollapse { width: 45px; height: 45px; background: var(--primary-green); border: none; color: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(25,135,84,0.2); display: flex; align-items: center; justify-content: center; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        #overlay { display: none; position: fixed; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 1040; top: 0; left: 0; }
        #overlay.active { display: block; }

        .lang-card {
            border: 2px solid #e9ecef;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        .lang-card:hover {
            border-color: var(--primary-green);
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(25,135,84,0.15);
        }
        .lang-card.active-lang {
            border-color: var(--primary-green);
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        }
        .lang-flag { font-size: 3.5rem; margin-bottom: 15px; }
        .lang-name { font-size: 1.3rem; font-weight: 700; color: #1a1a1a; }
        .lang-code { font-size: 0.85rem; color: #6c757d; margin-top: 4px; }
        .active-badge {
            display: inline-block;
            background: var(--primary-green);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 12px;
            border-radius: 50px;
            margin-top: 10px;
        }
        @media (max-width: 768px) { #content { padding: 15px; } }
    </style>
</head>
<body>

<div id="overlay"></div>

<div class="wrapper">
    @include('dashboard_admin.sidebar_admin')

    <div id="content">
        <div class="container-fluid">

            <div class="d-flex align-items-center mb-4 mt-2">
                <button type="button" id="sidebarCollapse" class="btn"><i class="bi bi-list fs-4"></i></button>
                <div class="ms-3">
                    <h4 class="mb-0 fw-bold text-success">{{ __('general.language_settings') }}</h4>
                    <p class="text-muted small mb-0">{{ __('general.current_language') }}: <strong>{{ $currentLocale === 'en' ? __('general.english') : __('general.indonesian') }}</strong></p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-1">{{ __('general.switch_to') }}</h5>
                <p class="text-muted small mb-4">{{ $currentLocale === 'en' ? __('general.english') : __('general.indonesian') }} &rarr; {{ $currentLocale === 'en' ? __('general.indonesian') : __('general.english') }}</p>

                <div class="row g-4 justify-content-center">
                    {{-- English Card --}}
                    <div class="col-md-4 col-sm-6">
                        <form action="{{ route('lang.switch') }}" method="POST">
                            @csrf
                            <input type="hidden" name="locale" value="en">
                            <button type="submit" class="w-100 border-0 bg-transparent p-0">
                                <div class="lang-card {{ $currentLocale === 'en' ? 'active-lang' : '' }}">
                                    <div class="lang-flag">🇬🇧</div>
                                    <div class="lang-name">{{ __('general.english') }}</div>
                                    <div class="lang-code">en</div>
                                    @if($currentLocale === 'en')
                                        <span class="active-badge"><i class="bi bi-check-circle-fill me-1"></i> {{ __('general.active') }}</span>
                                    @else
                                        <div class="mt-2 text-success small fw-semibold">{{ __('general.switch_to') }} {{ __('general.english') }}</div>
                                    @endif
                                </div>
                            </button>
                        </form>
                    </div>

                    {{-- Indonesian Card --}}
                    <div class="col-md-4 col-sm-6">
                        <form action="{{ route('lang.switch') }}" method="POST">
                            @csrf
                            <input type="hidden" name="locale" value="id">
                            <button type="submit" class="w-100 border-0 bg-transparent p-0">
                                <div class="lang-card {{ $currentLocale === 'id' ? 'active-lang' : '' }}">
                                    <div class="lang-flag">🇮🇩</div>
                                    <div class="lang-name">{{ __('general.indonesian') }}</div>
                                    <div class="lang-code">id</div>
                                    @if($currentLocale === 'id')
                                        <span class="active-badge"><i class="bi bi-check-circle-fill me-1"></i> {{ __('general.active') }}</span>
                                    @else
                                        <div class="mt-2 text-success small fw-semibold">{{ __('general.switch_to') }} {{ __('general.indonesian') }}</div>
                                    @endif
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle-fill text-success me-2"></i>{{ __('general.info') }}</h6>
                <ul class="text-muted small mb-0">
                    <li>{{ __('general.language') }} {{ __('general.default_language') }}: <strong>{{ __('general.english') }}</strong></li>
                    <li>{{ $currentLocale === 'en' ? __('general.lang_switch_id_hint') : __('general.lang_switch_en_hint') }}</li>
                    <li>{{ __('general.lang_session_hint') }}</li>
                </ul>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById('sidebar');
        const collapseBtn = document.getElementById('sidebarCollapse');
        const overlay = document.getElementById('overlay');
        const closeBtn = document.getElementById('close-sidebar');

        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show-mobile');
                overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('inactive');
            }
        }

        if(collapseBtn) collapseBtn.addEventListener('click', toggleSidebar);
        if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);
    });
</script>
</body>
</html>
