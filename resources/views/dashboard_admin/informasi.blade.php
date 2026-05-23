<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('dashboard.manage_info') }}</title>
    @if(isset($sekolah->logo))
    <link rel="icon" type="image/png" href="{{ asset($sekolah->logo) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('assets/img/default-favicon.png') }}">
    @endif
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-green: #198754;
            --dark-green: #146c43;
            --light-bg: #f4f7f6;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            overflow-x: hidden;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        #content {
            width: 100%;
            padding: 20px 30px;
            transition: all 0.3s;
            min-height: 100vh;
        }

        /* Hamburger Button Styling */
        #sidebarCollapse {
            width: 45px;
            height: 45px;
            background: var(--primary-green);
            border: none;
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(25, 135, 84, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        #sidebarCollapse:hover {
            background: var(--dark-green);
            transform: scale(1.05);
        }

        /* Tab & Card Styling */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            background: #fff;
        }

        .nav-pills .nav-link {
            color: #555;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary-green);
            box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);
        }

        .table thead {
            background-color: #f8f9fa;
        }
        /* Menghilangkan kedipan saat perpindahan tab otomatis di awal load */
        .tab-pane.fade:not(.show) {
            display: none;
        }

        .img-thumbnail-custom {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Overlay for Mobile */
        #overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            top: 0;
            left: 0;
        }

        #overlay.active {
            display: block;
        }

        @media (max-width: 768px) {
            #content {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    <div id="overlay"></div>

    <div class="wrapper">
        @include('dashboard_admin.sidebar_admin')

        <div id="content">
            <div class="container-fluid">
                
                <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
                    <div class="d-flex align-items-center">
                        <button type="button" id="sidebarCollapse" class="btn">
                            <i class="bi bi-list fs-4"></i>
                        </button>
                        <div class="ms-3">
                            <h4 class="mb-0 fw-bold text-success">{{ __('dashboard.manage_info') }}</h4>
                            <p class="text-muted small mb-0">{{ __('dashboard.update_info_subtitle') }}</p>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card p-3 mb-4">
                    <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-kegiatan">
                                <i class="bi bi-camera me-2"></i> {{ __('dashboard.activities') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-program">
                                <i class="bi bi-mortarboard me-2"></i> {{ __('dashboard.programs') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-prestasi">
                                <i class="bi bi-trophy me-2"></i> {{ __('dashboard.achievements') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-artikel">
                                <i class="bi bi-newspaper me-2"></i> {{ __('dashboard.articles') }}
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="tab-kegiatan">
                        <div class="card p-4">
                            <h5 class="fw-bold mb-3">{{ __('dashboard.add_activity') }}</h5>
                            <form action="{{ route('kegiatan.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 mb-4">
                                @csrf
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('dashboard.photo_label') }}</label>
                                    <input type="text" name="label_foto" class="form-control" required placeholder="{{ __('dashboard.activity_name') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('dashboard.activity_photo') }}</label>
                                    <input type="file" name="foto_kegiatan" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('dashboard.description_optional') }}</label>
                                    <input type="text" name="deskripsi_foto" class="form-control" placeholder="{{ __('dashboard.brief_notes_placeholder') }}">
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-success px-4">{{ __('dashboard.save_activity') }}</button>
                                </div>
                            </form>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>{{ __('general.photo') }}</th>
                                            <th>{{ __('dashboard.photo_label') }}</th>
                                            <th>{{ __('general.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kegiatan as $k)
                                        <tr>
                                            <td><img src="{{ asset($k->foto_kegiatan) }}" class="img-thumbnail-custom shadow-sm"></td>
                                            <td>{{ $k->label_foto }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success" onclick="editKegiatan('{{ $k->id }}', '{{ $k->label_foto }}', '{{ $k->deskripsi_foto }}')">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                <form action="{{ route('kegiatan.destroy', $k->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm(@json(__('dashboard.confirm_delete_item')))"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-program">
                        <div class="card p-4">
                            <h5 class="fw-bold mb-3">{{ __('dashboard.add_program') }}</h5>
                            <form action="{{ route('program.store') }}" method="POST" class="row g-3 mb-4">
                                @csrf
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('dashboard.program_name') }}</label>
                                    <input type="text" name="nama_program" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('dashboard.short_description') }}</label>
                                    <input type="text" name="deskripsi_program" class="form-control" maxlength="150">
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-success px-4">{{ __('dashboard.save_program') }}</button>
                                </div>
                            </form>
                            <hr>
                            <table class="table">
                                <thead><tr><th>{{ __('dashboard.program_name') }}</th><th>{{ __('general.description') }}</th><th>{{ __('general.action') }}</th></tr></thead>
                                <tbody>
                                    @foreach($programs as $p)
                                    <tr>
                                        <td class="fw-bold">{{ $p->nama_program }}</td>
                                        <td>{{ $p->deskripsi_program }}</td>
                                        <td>
                                             <button class="btn btn-sm btn-outline-success mb-1" onclick="editProgram('{{ $p->id }}', '{{ $p->nama_program }}', '{{ $p->deskripsi_program }}')">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('program.destroy', $p->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab-prestasi">
                        <div class="card p-4">
                            <h5 class="fw-bold mb-3">{{ __('dashboard.add_achievement') }}</h5>
                            <form action="{{ route('prestasi.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 mb-4">
                                @csrf
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('dashboard.achievement_title') }}</label>
                                    <input type="text" name="judul_prestasi" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('dashboard.achievement_photos') }}</label>
                                    <input type="file" name="foto_prestasi[]" class="form-control" multiple required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('dashboard.achievement_desc') }}</label>
                                    <textarea name="deskripsi_prestasi" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-success px-4">{{ __('dashboard.save_achievement') }}</button>
                                </div>
                            </form>
                            <div class="row">
                                @foreach($prestasi as $pres)
                                <div class="col-md-4 mb-3">
                                    <div class="card border p-3">
                                        <h6 class="fw-bold">{{ $pres->judul_prestasi }}</h6>
                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                            @foreach($pres->fotos as $f)
                                                <img src="{{ asset($f->foto) }}" width="50" height="50" class="rounded object-fit-cover shadow-sm">
                                            @endforeach
                                        </div>
                                        <button class="btn btn-sm btn-outline-success mb-1 p-0" 
                                            onclick="editPrestasi('{{ $pres->id }}', '{{ $pres->judul_prestasi }}', `{{ $pres->deskripsi_prestasi }}`, {{ $pres->fotos->toJson() }})">
                                            <i class="bi bi-pencil-square fs-5"></i>{{ __('general.edit') }}
                                        </button>
                                        <form action="{{ route('prestasi.destroy', $pres->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger w-100">{{ __('general.delete') }}</button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                <div class="tab-pane fade" id="tab-artikel">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3" id="form-artikel-title">{{ __('dashboard.write_article') }}</h5>
                    
                    <form id="form-artikel" action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <input type="hidden" name="_method" id="artikel-method" value="POST">

                        <div class="col-md-8">
                            <label class="form-label">{{ __('dashboard.article_title') }}</label>
                            <input type="text" name="judul_artikel" id="art_judul" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('dashboard.teaser') }}</label>
                            <input type="text" name="teaser" id="art_teaser" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('dashboard.article_content_label') }}</label>
                            <textarea name="deskripsi" id="art_desc" class="form-control" rows="5" required></textarea>
                        </div>

                        <div id="container-foto-lama" class="col-12 d-none">
                            <label class="form-label fw-bold text-success">{{ __('dashboard.current_photos') }}</label>
                            <div id="preview-foto-artikel" class="d-flex flex-wrap gap-3 p-3 border rounded bg-light">
                                </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">{{ __('dashboard.new_photos_sources') }}</h6>
                                <!--<button type="button" class="btn btn-sm btn-outline-success" onclick="addPhotoRow()">
                                    <i class="bi bi-plus"></i> Tambah Baris
                                </button>-->
                            </div>
                            <div id="artikel-photo-wrapper">
                                <div class="row g-2 mb-2">
                                    <div class="col-md-5"><input type="file" name="foto_artikel[]" class="form-control"></div>
                                    <div class="col-md-5"><input type="text" name="sumber_foto[]" class="form-control" placeholder="{{ __('dashboard.photo_source') }}"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-center pt-3">
                            <button type="button" id="btn-cancel-artikel" class="btn btn-outline-secondary btn-lg px-4 d-none" onclick="cancelEditArtikel()">{{ __('dashboard.cancel_btn') }}</button>
                            <button type="submit" id="btn-submit-artikel" class="btn btn-success btn-lg px-5 shadow">{{ __('general.publish') }}</button>
                        </div>
                    </form>
                    <hr class="my-5">
                </div>

                        <h5 class="fw-bold mb-3">{{ __('dashboard.published_articles') }}</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="150">{{ __('dashboard.thumbnail') }}</th>
                                        <th>{{ __('dashboard.title_summary') }}</th>
                                        <th width="150">{{ __('general.date') }}</th>
                                        <th width="100" class="text-center">{{ __('general.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($artikels as $art)
                                    <tr>
                                        <td>
                                            @if($art->fotos->count() > 0)
                                                <img src="{{ asset($art->fotos->first()->foto_artikel) }}" class="img-thumbnail-custom shadow-sm" style="width: 120px; height: 80px;">
                                            @else
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="width: 120px; height: 80px; font-size: 0.7rem;">
                                                    {{ __('dashboard.no_photo_label') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $art->judul }}</div>
                                            <small class="text-muted d-block">{{ Str::limit($art->teaser, 70) }}</small>
                                            <span class="badge bg-light text-success border mt-1">{{ __('dashboard.photos_count_badge', ['count' => $art->fotos->count()]) }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> {{ $art->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td class="text-center">
                                        <button class="btn btn-sm btn-outline-success mb-1" 
                                                onclick="editArtikel('{{ $art->id }}', '{{ $art->judul }}', '{{ $art->teaser }}', `{{ $art->deskripsi }}`, {{ $art->fotos->toJson() }})">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="{{ route('artikel.destroy', $art->id) }}" method="POST" onsubmit="return confirm(@json(__('dashboard.confirm_delete_article')))">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-journal-x display-4 d-block mb-2"></i>
                                            {{ __('dashboard.no_articles') }}
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                </div> </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditKegiatan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditKegiatan" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            @csrf @method('PUT')
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">{{ __('dashboard.edit_activity') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 row g-3">
                <div class="col-12">
                    <label class="form-label fw-bold">{{ __('dashboard.photo_label') }}</label>
                    <input type="text" name="label_foto" id="edit_kegiatan_label" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">{{ __('dashboard.replace_photo') }}</label>
                    <input type="file" name="foto_kegiatan" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">{{ __('general.description') }}</label>
                    <input type="text" name="deskripsi_foto" id="edit_kegiatan_desc" class="form-control">
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-success w-100">{{ __('dashboard.save_changes_btn') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditProgram" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditProgram" method="POST" class="modal-content border-0 shadow">
            @csrf @method('PUT')
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">{{ __('dashboard.edit_program') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 row g-3">
                <div class="col-12">
                    <label class="form-label fw-bold">{{ __('dashboard.program_name') }}</label>
                    <input type="text" name="nama_program" id="edit_program_nama" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">{{ __('general.description') }}</label>
                    <input type="text" name="deskripsi_program" id="edit_program_desc" class="form-control">
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-success w-100">{{ __('dashboard.save_changes_btn') }}</button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modalEditPrestasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditPrestasi" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            @csrf @method('PUT')
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">{{ __('dashboard.edit_achievement') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 row g-3">
                <div class="col-12">
                    <label class="form-label fw-bold">{{ __('dashboard.achievement_title') }}</label>
                    <input type="text" name="judul_prestasi" id="edit_prestasi_judul" class="form-control" required>
                </div>
                
                <div class="col-12">
                    <label class="form-label fw-bold">{{ __('dashboard.current_photos_delete') }}</label>
                    <div id="preview-foto-lama" class="d-flex flex-wrap gap-2 p-2 border rounded bg-light">
                        </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">{{ __('dashboard.add_new_photos') }}</label>
                    <input type="file" name="foto_prestasi[]" class="form-control" multiple>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">{{ __('general.description') }}</label>
                    <textarea name="deskripsi_prestasi" id="edit_prestasi_desc" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-success w-100 py-2 shadow-sm">{{ __('dashboard.save_changes_btn') }}</button>
            </div>
        </form>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const i18nInfo = {
            editArticlePrefix: @json(__('dashboard.edit_article_prefix')),
            photoSourcePlaceholder: @json(__('dashboard.photo_source')),
            noPhotos: @json(__('dashboard.no_photos')),
            failedDeletePhoto: @json(__('dashboard.failed_delete_photo')),
        };
        document.addEventListener("DOMContentLoaded", function() {
            const tabTriggerList = [].slice.call(document.querySelectorAll('#pills-tab button'));
    const activeTabTarget = localStorage.getItem('activeTab');

    if (activeTabTarget) {
        const tabToActivate = document.querySelector(`button[data-bs-target="${activeTabTarget}"]`);
        
        if (tabToActivate) {
            const tab = new bootstrap.Tab(tabToActivate);
            tab.show();
        }
    }
    tabTriggerList.forEach(function(tabEl) {
        tabEl.addEventListener('shown.bs.tab', function(event) {
            const target = event.target.getAttribute('data-bs-target');
            localStorage.setItem('activeTab', target);
        });
    });
            const sidebar = document.getElementById('sidebar');
            const collapseBtn = document.getElementById('sidebarCollapse');
            const closeBtn = document.getElementById('close-sidebar');
            const overlay = document.getElementById('overlay');

            // Sidebar Toggle Logic
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

        // Add Multi Photo Row for Artikel
        function addPhotoRow() {
            const wrapper = document.getElementById('artikel-photo-wrapper');
            const div = document.createElement('div');
            div.className = 'row g-2 mb-2 align-items-center';
            div.innerHTML = `
                <div class="col-md-5"><input type="file" name="foto_artikel[]" class="form-control"></div>
                <div class="col-md-5"><input type="text" name="sumber_foto[]" class="form-control" placeholder="${i18nInfo.photoSourcePlaceholder}"></div>
                <div class="col-md-2"><button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="this.parentElement.parentElement.remove()"><i class="bi bi-trash"></i></button></div>
            `;
            wrapper.appendChild(div);
        }
        function editKegiatan(id, label, desc) {
        document.getElementById('formEditKegiatan').action = `/informasi/kegiatan/${id}`;
        document.getElementById('edit_kegiatan_label').value = label;
        document.getElementById('edit_kegiatan_desc').value = desc;
        new bootstrap.Modal(document.getElementById('modalEditKegiatan')).show();
    }

    function editProgram(id, nama, desc) {
        document.getElementById('formEditProgram').action = `/informasi/program/${id}`;
        document.getElementById('edit_program_nama').value = nama;
        document.getElementById('edit_program_desc').value = desc;
        new bootstrap.Modal(document.getElementById('modalEditProgram')).show();
    }

    function editArtikel(id, judul, teaser, desc, fotos) {
    // 1. Ubah Status Form ke Mode Edit
    document.getElementById('form-artikel-title').innerText = i18nInfo.editArticlePrefix + judul;
    document.getElementById('btn-submit-artikel').innerText = @json(__('dashboard.save_changes_btn'));
    document.getElementById('btn-submit-artikel').className = "btn btn-success btn-lg px-5 shadow";
    document.getElementById('btn-cancel-artikel').classList.remove('d-none');

    // 2. Set URL Action & Method PUT
    const form = document.getElementById('form-artikel');
    form.action = `/informasi/artikel/${id}`;
    document.getElementById('artikel-method').value = "PUT";

    // 3. Isi Data Text
    document.getElementById('art_judul').value = judul;
    document.getElementById('art_teaser').value = teaser;
    document.getElementById('art_desc').value = desc;

    // 4. Tampilkan Preview Foto Lama
    const containerLama = document.getElementById('container-foto-lama');
    const previewBox = document.getElementById('preview-foto-artikel');
    
    containerLama.classList.remove('d-none');
    previewBox.innerHTML = ''; 

    fotos.forEach(foto => {
        const div = document.createElement('div');
        div.className = 'position-relative';
        div.id = `wrapper-foto-art-${foto.id}`;
        div.innerHTML = `
            <img src="/${foto.foto_artikel}" class="rounded border shadow-sm" style="width: 110px; height: 80px; object-fit: cover;">
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 shadow" 
                style="width: 24px; height: 24px; border-radius: 50%; margin-top:-8px; margin-right:-8px;"
                onclick="ajaxHapusFotoArtikel('${foto.id}')">×</button>
            <small class="d-block text-center text-muted mt-1" style="font-size: 10px;">${foto.sumber_foto}</small>
        `;
        previewBox.appendChild(div);
    });

    window.scrollTo({ top: form.offsetTop - 100, behavior: 'smooth' });
}

function ajaxHapusFotoArtikel(fotoId) {
    if(confirm(@json(__('dashboard.confirm_delete_photo'))) {
        fetch(`/informasi/artikel/foto/${fotoId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(res => res.json()).then(data => {
            document.getElementById(`wrapper-foto-art-${fotoId}`).remove();
        });
    }
}

function cancelEditArtikel() {
    location.reload(); // Cara paling aman untuk mereset seluruh state form
}
    function editPrestasi(id, judul, desc, fotos) {
    // 1. Set URL Action Form
    document.getElementById('formEditPrestasi').action = `/informasi/prestasi/${id}`;
    
    // 2. Isi Input Teks
    document.getElementById('edit_prestasi_judul').value = judul;
    document.getElementById('edit_prestasi_desc').value = desc;

    // 3. Render Foto Lama
    const container = document.getElementById('preview-foto-lama');
    container.innerHTML = ''; // Bersihkan kontainer

    if (fotos.length === 0) {
        container.innerHTML = '<small class="text-muted">' + i18nInfo.noPhotos + '</small>';
    }

    fotos.forEach(foto => {
        const div = document.createElement('div');
        div.className = 'position-relative';
        div.id = `foto-wrapper-${foto.id}`;
        div.innerHTML = `
            <img src="/${foto.foto}" class="rounded border" style="width: 80px; height: 80px; object-fit: cover;">
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 shadow-sm" 
                style="width: 22px; height: 22px; border-radius: 50%;"
                onclick="hapusFotoSatu('${foto.id}')">
                <i class="bi bi-x-small"></i>×
            </button>
        `;
        container.appendChild(div);
    });

    // 4. Tampilkan Modal
    new bootstrap.Modal(document.getElementById('modalEditPrestasi')).show();
}

// Fungsi AJAX untuk hapus foto tanpa tutup modal
function hapusFotoSatu(fotoId) {
    if (confirm(@json(__('dashboard.confirm_delete_photo_permanent'))) {
        fetch(`/informasi/prestasi/foto/${fotoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hapus elemen dari tampilan modal
            document.getElementById(`foto-wrapper-${fotoId}`).remove();
        })
        .catch(error => alert(i18nInfo.failedDeletePhoto));
    }
}
    </script>
</body>
</html>