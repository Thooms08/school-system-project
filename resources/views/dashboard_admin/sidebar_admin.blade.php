<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    :root {
        --sidebar-bg: #1a3a3a;
        --sidebar-hover: #2d5a5a;
        --sidebar-active: #198754;
        --sidebar-header: #142d2d;
    }

    #sidebar {
        min-width: 280px;
        max-width: 280px;
        background: var(--sidebar-bg);
        color: #fff;
        transition: all 0.3s ease-in-out;
        height: 100vh;
        position: sticky;
        top: 0;
        display: flex;
        flex-direction: column;
        z-index: 1050;
    }

    #sidebar.inactive { margin-left: -280px; }

    #sidebar .sidebar-header {
        padding: 20px;
        background: var(--sidebar-header);
        border-bottom: 1px solid rgba(255,255,255,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #close-sidebar {
        background: transparent;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        display: none;
        line-height: 1;
    }

    #sidebar ul.components {
        padding: 15px 0;
        flex-grow: 1;
        overflow-y: auto;
    }

    #sidebar ul li a {
        padding: 12px 20px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        text-decoration: none;
        color: rgba(255, 255, 255, 0.8);
        transition: 0.2s;
    }

    #sidebar ul li a:hover { background: var(--sidebar-hover); color: #fff; }
    #sidebar ul li.active > a { background: var(--sidebar-active); color: #fff; }
    #sidebar ul li a i { margin-right: 15px; font-size: 1.1rem; }

    .collapse-inner { background: rgba(0, 0, 0, 0.15); padding: 5px 0; }
    .collapse-inner a { padding-left: 50px !important; font-size: 0.85rem !important; color: rgba(255, 255, 255, 0.7) !important; }
    .collapse-inner a:hover { color: #fff !important; }

    .logout-section { border-top: 1px solid rgba(255,255,255,0.1); padding: 15px; }

    .btn-logout {
        width: 100%;
        text-align: left;
        padding: 12px 15px;
        background: transparent;
        border: none;
        color: #ff8080;
        display: flex;
        align-items: center;
        transition: 0.2s;
        border-radius: 8px;
    }

    .btn-logout:hover { background: rgba(255, 77, 77, 0.1); color: #ff4d4d; }

    .collapse-inner a.active-sub {
        color: #198754 !important;
        font-weight: bold;
        background-color: rgba(25, 135, 84, 0.1);
        border-radius: 5px;
        padding-left: 10px;
    }

    .dropdown-toggle.active { background-color: rgba(25, 135, 84, 0.05); }

    .badge-notif {
        background: #ff4d4d;
        color: white;
        padding: 2px 8px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: bold;
        margin-left: auto;
        box-shadow: 0 2px 5px rgba(255, 77, 77, 0.3);
        display: none;
    }

    @media (max-width: 768px) {
        #sidebar { position: fixed; left: -280px; margin-left: 0 !important; }
        #sidebar.show-mobile { left: 0; }
        #close-sidebar { display: block; }
    }
</style>

<nav id="sidebar">
    <div class="sidebar-header">
        <div>
            <h5 class="mb-0 fw-bold"><i class="bi bi-shield-check me-2"></i>{{ __('menu.admin_panel') }}</h5>
            <small class="text-success text-opacity-75">{{ __('menu.school_system') }}</small>
        </div>
        <button id="close-sidebar" title="{{ __('menu.close_menu') }}">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ Request::is('dashboard_admin') ? 'active' : '' }}">
            <a href="{{ route('admin.home') }}">
                <i class="bi bi-speedometer2"></i> {{ __('menu.dashboard') }}
            </a>
        </li>

        <li class="{{ Request::is('profile-sekolah*') ? 'active' : '' }}">
            <a href="{{ route('profile-sekolah.index') }}">
                <i class="bi bi-building"></i> {{ __('menu.school_profile') }}
            </a>
        </li>

        <li class="{{ request()->routeIs('informasi.*') ? 'active' : '' }}">
            <a href="{{ route('informasi.index') }}">
                <i class="bi bi-info-circle"></i> {{ __('menu.manage_info') }}
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.ppdb.*') ? 'active' : '' }}">
            <a href="{{ route('admin.ppdb.index') }}" class="d-flex align-items-center w-100">
                <i class="bi bi-bell"></i>
                <span>{{ __('menu.ppdb_notification') }}</span>
                <span id="ppdb-badge" class="badge-notif" style="display: none;">0</span>
            </a>
        </li>

        <li class="{{ Request::is('kelas*') ? 'active' : '' }}">
            <a href="{{ route('kelas.index') }}">
                <i class="bi bi-door-open"></i> {{ __('menu.manage_class') }}
            </a>
        </li>

        <li class="px-3 mt-4 mb-2">
            <small class="text-uppercase fw-bold" style="font-size: 1rem; letter-spacing: 1px; color:white;">{{ __('menu.data_master') }}</small>
        </li>

        <li class="{{ Request::is('guru*') ? 'active' : '' }}">
            <a href="{{ route('guru.index') }}">
                <i class="bi bi-person-badge"></i> {{ __('menu.teacher_data') }}
            </a>
        </li>

        <li class="{{ request()->routeIs('murid.*') ? 'active' : '' }}">
            <a href="{{ route('murid.index') }}">
                <i class="bi bi-people"></i> {{ __('menu.student_data') }}
            </a>
        </li>

        <li class="{{ request()->routeIs('wali-murid.*') ? 'active' : '' }}">
            <a href="{{ route('wali-murid.index') }}">
                <i class="bi bi-person-hearts"></i> {{ __('menu.guardian_data') }}
            </a>
        </li>

        <li class="{{ request()->routeIs('akun-guru.*', 'akun-wali.*') ? 'active' : '' }}">
            <a href="#submenuAkun"
            data-bs-toggle="collapse"
            aria-expanded="{{ request()->routeIs('akun-guru.*', 'akun-wali.*') ? 'true' : 'false' }}"
            class="dropdown-toggle {{ request()->routeIs('akun-guru.*', 'akun-wali.*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i> {{ __('menu.manage_account') }}
            </a>

            <div class="collapse {{ request()->routeIs('akun-guru.*', 'akun-wali.*') ? 'show' : '' }}" id="submenuAkun">
                <div class="collapse-inner">
                    <a href="{{ route('akun-guru.index') }}"
                    class="d-block {{ request()->routeIs('akun-guru.*') ? 'active-sub' : '' }}">
                    <i class="bi bi-person-badge-fill me-2" style="font-size: 0.8rem;"></i> {{ __('menu.teacher_account') }}
                    </a>

                    <a href="{{ route('akun-wali.index') }}"
                    class="d-block {{ request()->routeIs('akun-wali.*') ? 'active-sub' : '' }}">
                    <i class="bi bi-person-hearts me-2" style="font-size: 0.8rem;"></i> {{ __('menu.guardian_account') }}
                    </a>
                </div>
            </div>
        </li>

        <li class="{{ request()->routeIs('setting.lang') ? 'active' : '' }}">
            <a href="{{ route('setting.lang') }}">
                <i class="bi bi-translate"></i> {{ __('menu.language_settings') }}
            </a>
        </li>
    </ul>

    <div class="logout-section">
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right me-3"></i>
                <span>{{ __('menu.logout') }}</span>
            </button>
        </form>
    </div>
</nav>

<script>
    function updateAllBadges() {
        fetch("{{ route('admin.pelanggaran.count') }}")
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notif-count');
                if (badge) {
                    badge.innerText = data.count;
                    badge.style.display = data.count > 0 ? 'block' : 'none';
                }
            })
            .catch(error => console.error('Error Pelanggaran Badge:', error));

        fetch("{{ route('admin.ppdb.count') }}")
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('ppdb-badge');
                if (badge) {
                    badge.innerText = data.count;
                    badge.style.display = data.count > 0 ? 'block' : 'none';
                }
            })
            .catch(error => console.error('Error PPDB Badge:', error));
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateAllBadges();
        setInterval(updateAllBadges, 10000);

        const sidebar = document.getElementById('sidebar');
        const closeBtn = document.getElementById('close-sidebar');

        window.toggleSidebar = function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show-mobile');
            } else {
                sidebar.classList.toggle('inactive');
            }
        };

        if(closeBtn) {
            closeBtn.addEventListener('click', function() {
                sidebar.classList.remove('show-mobile');
            });
        }
    });
</script>
