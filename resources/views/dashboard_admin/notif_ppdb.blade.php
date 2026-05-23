<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('dashboard.ppdb_management') }}</title>
    @if(isset($sekolah->logo))
    <link rel="icon" type="image/png" href="{{ asset($sekolah->logo) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('assets/img/default-favicon.png') }}">
    @endif
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root { --primary-green: #198754; --bg-soft: #f4f7f6; }
        body { background-color: var(--bg-soft); font-family: 'Inter', sans-serif; }
        .wrapper { display: flex; }
        #content { width: 100%; padding: 25px; transition: all 0.3s; }
        
        /* Notif Card Style */
        .card-notif { border: none; border-radius: 12px; transition: 0.3s; border-left: 5px solid var(--primary-green); background: white; }
        .card-notif:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .badge-pending { background-color: #fff3cd; color: #856404; font-weight: bold; border-radius: 50px; }
        
        /* Button Style */
        .btn-confirm { background-color: var(--primary-green); color: white; border: none; }
        .btn-confirm:hover { background-color: #146c43; color: white; }
        #sidebarCollapse { background: var(--primary-green); border: none; color: white; border-radius: 10px; padding: 8px 12px; }
        
        /* Modal Detail Style */
        .table-detail th { background-color: #f8f9fa; width: 35%; color: #555; font-size: 0.85rem; }
        .table-detail td { font-size: 0.85rem; color: #333; }
        .section-title { font-size: 0.9rem; font-weight: bold; color: var(--primary-green); border-bottom: 2px solid #eee; padding-bottom: 5px; margin-top: 15px; margin-bottom: 10px; }
        
        /* Toggle Transition */
        .transition { transition: all 0.4s ease; }
    </style>
</head>
<body>

<div class="wrapper">
    @include('dashboard_admin.sidebar_admin')

    <div id="content">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <button type="button" id="sidebarCollapse"><i class="bi bi-list fs-4"></i></button>
                    <div class="ms-3">
                        <h4 class="mb-0 fw-bold">{{ __('dashboard.ppdb_management') }}</h4>
                        <p class="text-muted small mb-0">{{ __('dashboard.ppdb_subtitle') }}</p>
                    </div>
                </div>

                <button id="btnTogglePPDB" class="btn btn-lg rounded-pill px-4 fw-bold shadow-sm transition">
                    <span id="ppdbStatusText"><i class="spinner-border spinner-border-sm me-2"></i>{{ __('dashboard.loading') }}</span>
                </button>
            </div>

            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 15px;">
                <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-bell-fill me-2 text-warning"></i>{{ __('dashboard.pending_registrations') }}</h5>
                <div id="notif-container">
                    <div class="text-center py-5">
                        <div class="spinner-border text-success" role="status"></div>
                        <p class="mt-2 text-muted">{{ __('dashboard.checking_new') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-vcard me-2"></i>{{ __('dashboard.full_registration_data') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="detail-content">
                </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">{{ __('general.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const btnToggle = document.getElementById('btnTogglePPDB');
    const statusText = document.getElementById('ppdbStatusText');
    const i18n = {
        closePpdb: @json(__('dashboard.close_ppdb')),
        openPpdb: @json(__('dashboard.open_ppdb')),
        confirmClose: @json(__('dashboard.confirm_close_ppdb')),
        confirmOpen: @json(__('dashboard.confirm_open_ppdb')),
        allProcessed: @json(__('dashboard.all_processed')),
        pendingVerification: @json(__('dashboard.pending_verification')),
        detailData: @json(__('dashboard.detail_data')),
        confirmBtn: @json(__('dashboard.confirm_btn')),
        loadingRegistrant: @json(__('dashboard.loading_registrant')),
        confirmImportant: @json(__('dashboard.confirm_important')),
        studentIdentity: @json(__('dashboard.student_identity')),
        domicileAddress: @json(__('dashboard.domicile_address')),
        fatherData: @json(__('dashboard.father_data')),
        motherData: @json(__('dashboard.mother_data')),
        nameLabel: @json(__('dashboard.name_label')),
        nisnNik: @json(__('dashboard.nisn_nik')),
        gender: @json(__('dashboard.gender')),
        male: @json(__('dashboard.male')),
        female: @json(__('dashboard.female')),
        birthPlaceDate: @json(__('dashboard.birth_place_date')),
        emailPhone: @json(__('dashboard.email_phone')),
        originSchool: @json(__('dashboard.origin_school')),
        physical: @json(__('dashboard.physical')),
        region: @json(__('dashboard.region')),
        addressDetail: @json(__('dashboard.address_detail')),
        occupation: @json(__('dashboard.occupation')),
        income: @json(__('dashboard.income')),
        nisnPrefix: @json(__('dashboard.nisn')) + ': ',
    };
    let ppdbIsOpen = false;

    function checkPPDBStatus() {
        fetch("{{ route('admin.ppdb.status') }}")
            .then(res => res.json())
            .then(data => {
                updateButtonUI(data.isOpen);
            });
    }

    function updateButtonUI(isOpen) {
        ppdbIsOpen = isOpen;
        if (isOpen) {
            btnToggle.className = "btn btn-danger btn-lg rounded-pill px-4 fw-bold shadow-sm transition";
            statusText.innerHTML = `<i class="bi bi-x-circle me-2"></i> ${i18n.closePpdb}`;
        } else {
            btnToggle.className = "btn btn-success btn-lg rounded-pill px-4 fw-bold shadow-sm transition";
            statusText.innerHTML = `<i class="bi bi-check-circle me-2"></i> ${i18n.openPpdb}`;
        }
    }

    btnToggle.onclick = function() {
        const msg = ppdbIsOpen ? i18n.confirmClose : i18n.confirmOpen;
        if (!confirm(msg)) return;

        fetch("{{ route('admin.ppdb.toggle') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateButtonUI(data.isOpen);
            }
        });
    };

    // --- 2. FUNGSI BADGE NOTIFIKASI SIDEBAR ---
    function updateBadge() {
        fetch("{{ route('admin.ppdb.count') }}")
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('ppdb-badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.innerText = data.count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            });
    }

    // --- 3. FUNGSI DAFTAR PENDAFTAR PENDING ---
    function fetchNotifications() {
        fetch("{{ route('admin.ppdb.data') }}")
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('notif-container');
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-5">
                            <i class="bi bi-check2-circle text-success" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-muted">${i18n.allProcessed}</p>
                        </div>`;
                    return;
                }

                data.forEach(m => {
                    const date = new Date(m.created_at).toLocaleDateString('id-ID', {
                        day: 'numeric', month: 'long', year: 'numeric'
                    });
                    
                    container.innerHTML += `
                        <div class="card card-notif p-3 mb-3 shadow-sm" id="row-${m.id}">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h6 class="fw-bold mb-1 text-success">${m.nama_lengkap}</h6>
                                    <small class="text-muted d-block mb-2"><i class="bi bi-person-vcard"></i> ${i18n.nisnPrefix}${m.nisn} | <i class="bi bi-calendar3"></i> ${date}</small>
                                    <span class="badge badge-pending px-3 py-2">${i18n.pendingVerification}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-success btn-sm px-3 rounded-pill" onclick="viewDetail(${m.id})">
                                        <i class="bi bi-eye"></i> ${i18n.detailData}
                                    </button>
                                    <button class="btn btn-confirm btn-sm px-3 rounded-pill" onclick="confirmPPDB(${m.id})">
                                        <i class="bi bi-check-circle"></i> ${i18n.confirmBtn}
                                    </button>
                                </div>
                            </div>
                        </div>`;
                });
            });
    }

    // --- 4. FUNGSI MODAL DETAIL LENGKAP ---
    function viewDetail(id) {
        const content = document.getElementById('detail-content');
        content.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-success"></div><p class="mt-2">${i18n.loadingRegistrant}</p></div>`;
        
        const myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetail'));
        myModal.show();

        fetch(`{{ url('admin/ppdb-notifications/detail') }}/${id}`)
            .then(res => res.json())
            .then(data => {
                const m = data.murid;
                const w = data.wali;

                content.innerHTML = `
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <div class="section-title"><i class="bi bi-person-fill"></i> ${i18n.studentIdentity}</div>
                            <table class="table table-sm table-bordered table-detail">
                                <tr><th>${i18n.nameLabel}</th><td>${m.nama_lengkap}</td></tr>
                                <tr><th>${i18n.nisnNik}</th><td>${m.nisn} / ${m.nik}</td></tr>
                                <tr><th>${i18n.gender}</th><td>${m.jenis_kelamin == 'L' ? i18n.male : i18n.female}</td></tr>
                                <tr><th>${i18n.birthPlaceDate}</th><td>${m.tempat_lahir || '-'}, ${m.tgl_lahir || '-'}</td></tr>
                                <tr><th>${i18n.emailPhone}</th><td>${m.alamat_email} / ${m.no_hp}</td></tr>
                                <tr><th>${i18n.originSchool}</th><td>${m.sekolah_asal || '-'}</td></tr>
                                <tr><th>${i18n.physical}</th><td>${m.tinggi_badan || '-'} cm / ${m.berat_badan || '-'} kg</td></tr>
                            </table>

                            <div class="section-title"><i class="bi bi-geo-alt-fill"></i> ${i18n.domicileAddress}</div>
                            <table class="table table-sm table-bordered table-detail">
                                <tr><th>${i18n.region}</th><td>${m.desa_kelurahan || '-'}, ${m.kota_kabupaten || '-'}</td></tr>
                                <tr><th>${i18n.addressDetail}</th><td>${m.alamat_detail || '-'} (RT/RW: ${m.rt_rw || '-'})</td></tr>
                            </table>
                        </div>

                        <div class="col-md-6 ps-md-4">
                            <div class="section-title text-primary"><i class="bi bi-gender-male"></i> ${i18n.fatherData}</div>
                            <table class="table table-sm table-bordered table-detail mb-3">
                                <tr><th>${i18n.nameLabel}</th><td>${w.nama_ayah || '-'}</td></tr>
                                <tr><th>${i18n.occupation}</th><td>${w.pekerjaan_ayah || '-'}</td></tr>
                                <tr><th>${i18n.income}</th><td>Rp ${new Number(w.penghasilan_ayah).toLocaleString('id-ID')}</td></tr>
                            </table>

                            <div class="section-title text-danger"><i class="bi bi-gender-female"></i> ${i18n.motherData}</div>
                            <table class="table table-sm table-bordered table-detail mb-3">
                                <tr><th>${i18n.nameLabel}</th><td>${w.nama_ibu || '-'}</td></tr>
                                <tr><th>${i18n.occupation}</th><td>${w.pekerjaan_ibu || '-'}</td></tr>
                                <tr><th>${i18n.income}</th><td>Rp ${new Number(w.penghasilan_ibu).toLocaleString('id-ID')}</td></tr>
                            </table>
                        </div>
                    </div>`;
            });
    }

    // --- 5. FUNGSI KONFIRMASI (UBAH PENDING KE KONFIRMASI) ---
    function confirmPPDB(id) {
        if (!confirm(i18n.confirmImportant)) return;

        fetch(`{{ url('admin/ppdb-notifications/confirm') }}/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`row-${id}`);
                row.style.transform = 'translateX(100px)';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    updateBadge();
                    fetchNotifications();
                }, 400);
            }
        });
    }

    // INIT
    document.addEventListener('DOMContentLoaded', () => {
        checkPPDBStatus();
        updateBadge();
        fetchNotifications();
        
        // Polling real-time
        setInterval(updateBadge, 10000); 
        setInterval(fetchNotifications, 60000); // 1 menit sekali

        document.getElementById('sidebarCollapse').onclick = () => {
            document.getElementById('sidebar').classList.toggle('inactive');
        };
    });
</script>
</body>
</html>