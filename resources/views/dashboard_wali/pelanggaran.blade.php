<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('dashboard.student_violation_title') }}</title>
     @if(isset($sekolah->logo))
    <link rel="icon" type="image/png" href="{{ asset($sekolah->logo) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('assets/img/default-favicon.png') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --primary-red: #dc3545; --primary-blue: #0d6efd; --bg-soft: #f8f9fa; }
        body { background-color: var(--bg-soft); font-family: 'Inter', sans-serif; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .text-red { color: var(--primary-red); }
        .text-blue { color: var(--primary-blue); }
        
        @media (max-width: 768px) {
            .desktop-table { display: none; }
            .mobile-card { display: block; margin-bottom: 15px; border-left: 5px solid var(--primary-red); }
        }
        @media (min-width: 769px) { .mobile-card { display: none; } }
        
        .chart-container { max-width: 250px; margin: 0 auto; position: relative; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('wali.home') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> {{ __('dashboard.back_btn_wali') }}
        </a>
        <h4 class="fw-bold mb-0 text-dark">{{ __('dashboard.esikap_title') }}</h4>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card p-4 mb-4 text-center">
                <h6 class="fw-bold mb-3 text-start">{{ __('dashboard.profile_stats') }}</h6>
                <div class="mb-4 text-start">
                    <label class="small text-muted">{{ __('dashboard.student_name_label2') }}</label>
                    <div class="fw-bold" id="namaMuridDisplay">-</div>
                    <label class="small text-muted mt-2">{{ __('dashboard.class_label2') }}</label>
                    <div class="fw-bold" id="kelasMuridDisplay">-</div>
                </div>

                <hr>

                <div class="mb-3">
                    <select id="filterRange" class="form-select mb-2" onchange="loadData()">
                        <option value="1_minggu">{{ __('dashboard.last_1_week') }}</option>
                        <option value="1_bulan" selected>{{ __('dashboard.last_1_month') }}</option>
                        <option value="1_tahun">{{ __('dashboard.last_1_year') }}</option>
                    </select>
                    <input type="date" id="filterTanggal" class="form-select" onchange="loadData()">
                </div>
                
                <div class="chart-container">
                    <canvas id="pelanggaranChart"></canvas>
                    <div class="mt-3">
                        <h3 class="fw-bold mb-0" id="totalSkorDisplay">0</h3>
                        <small class="text-muted">{{ __('dashboard.total_score') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold mb-0">{{ __('dashboard.violation_history') }}</h6>
                    <span class="badge bg-secondary rounded-pill">{{ __('general.latest_data') }}</span>
                </div>

                <div class="table-responsive desktop-table">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('dashboard.date_col3') }}</th>
                                <th>{{ __('dashboard.violation_col3') }}</th>
                                <th>{{ __('dashboard.score_col3') }}</th>
                                <th class="text-center">{{ __('general.action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>

                <div id="mobileBody"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">{{ __('dashboard.violation_detail') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="detailContent">
                </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const i18nWaliPel = {
        notInClass: @json(__('dashboard.not_in_class')),
        noViolations: @json(__('dashboard.no_violations')),
        noViolationsMobile: @json(__('dashboard.no_violations_mobile')),
        viewDetail: @json(__('dashboard.view_detail_mobile')),
        networkError: @json(__('dashboard.network_error')),
        failedLoad: @json(__('dashboard.failed_load_activeness')),
        pointsLabel: @json(__('dashboard.points_label')),
        notesLabel: @json(__('dashboard.notes_label2')),
        violationTypeLabel: @json(__('dashboard.violation_type_label')),
        pointsWord: @json(__('general.points')),
    };
    let myChart;

/**
 * Inisialisasi atau Update Chart.js
 * @param {Object} chartData - Data yang dikirim dari Controller
 */
function initChart(chartData) {
    const ctx = document.getElementById('pelanggaranChart').getContext('2d');
    
    // Hancurkan chart lama jika ada untuk mencegah tumpang tindih (overlay)
    if(myChart) {
        myChart.destroy();
    }

    myChart = new Chart(ctx, {
        type: 'doughnut',
        data: chartData,
        options: { 
            plugins: { 
                legend: { display: false } // Legend disembunyikan agar lebih bersih
            }, 
            cutout: '75%', // Membuat lubang di tengah lebih besar (donat tipis)
            responsive: true,
            maintainAspectRatio: true
        }
    });
}

/**
 * Mengambil data dari server menggunakan AJAX Fetch
 */
function loadData() {
    const range = document.getElementById('filterRange').value;
    const tgl = document.getElementById('filterTanggal').value;

    // URL mengarah ke route WaliPelanggaranController@getPelanggaranData
    fetch(`{{ route('wali.pelanggaran.data') }}?range=${range}&tanggal=${tgl}`)
        .then(res => {
            if (!res.ok) throw new Error(i18nWaliPel.networkError);
            return res.json();
        })
        .then(res => {
            // --- 1. UPDATE PROFIL MURID (Selalu Tampil) ---
            document.getElementById('namaMuridDisplay').innerText = res.murid.nama_lengkap;
            document.getElementById('kelasMuridDisplay').innerText = res.murid.nama_kelas || i18nWaliPel.notInClass;
            
            // Update Skor Poin
            const totalSkor = res.total_skor;
            const displaySkor = document.getElementById('totalSkorDisplay');
            displaySkor.innerText = totalSkor;
            
            // Ganti warna teks skor: Merah jika ada poin, Biru jika 0 (Disiplin)
            displaySkor.className = totalSkor > 0 ? 'fw-bold text-danger mb-0' : 'fw-bold text-primary mb-0';

            // --- 2. UPDATE PIE CHART ---
            initChart(res.chart);

            // --- 3. UPDATE TABEL & MOBILE LAYOUT ---
            let tableHtml = '';
            let mobileHtml = '';

            if(res.pelanggaran.length === 0) {
                // Tampilan jika TIDAK ADA pelanggaran
                tableHtml = `
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-shield-check text-success fs-1 d-block mb-2"></i>
                            ${i18nWaliPel.noViolations}
                        </td>
                    </tr>`;
                mobileHtml = `
                    <div class="card p-4 text-center text-muted shadow-sm border-0">
                        <i class="bi bi-emoji-smile text-primary fs-2 mb-2"></i>
                        <p class="mb-0">${i18nWaliPel.noViolationsMobile}</p>
                    </div>`;
            } else {
                // Tampilan jika ADA pelanggaran
                res.pelanggaran.forEach(p => {
                    // Render Tabel Desktop
                    tableHtml += `
                        <tr>
                            <td>${p.tanggal}</td>
                            <td><div class="fw-bold">${p.nama_pelanggaran}</div></td>
                            <td><span class="badge bg-danger">${p.skor} ${i18nWaliPel.pointsWord}</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light border" onclick="showDetail('${p.nama_pelanggaran}', '${p.skor}', '${p.keterangan || "-"}')">
                                    <i class="bi bi-search text-danger"></i>
                                </button>
                            </td>
                        </tr>`;
                    
                    // Render Card Mobile
                    mobileHtml += `
                        <div class="card mobile-card p-3 shadow-sm border-0 mb-3 bg-white">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small text-muted"><i class="bi bi-calendar-event me-1"></i>${p.tanggal}</span>
                                <span class="badge bg-danger">${p.skor} ${i18nWaliPel.pointsWord}</span>
                            </div>
                            <div class="fw-bold text-dark mb-3">${p.nama_pelanggaran}</div>
                            <button class="btn btn-sm btn-outline-danger w-100" onclick="showDetail('${p.nama_pelanggaran}', '${p.skor}', '${p.keterangan || "-"}')">${i18nWaliPel.viewDetail}</button>
                        </div>`;
                });
            }

            document.getElementById('tableBody').innerHTML = tableHtml;
            document.getElementById('mobileBody').innerHTML = mobileHtml;
        })
        .catch(err => {
    console.error("Detail Error:", err); // Lihat detail ini di F12 -> Console
    document.getElementById('tableBody').innerHTML = `
        <tr>
            <td colspan="4" class="text-center text-danger py-4">
                <i class="bi bi-exclamation-triangle-fill d-block mb-2"></i>
                ${i18nWaliPel.failedLoad} ${err.message}
            </td>
        </tr>`;
});
}

/**
 * Menampilkan Modal Detail Pelanggaran
 */
function showDetail(nama, skor, ket) {
    const content = `
        <div class="mb-3">
            <label class="small text-muted d-block text-uppercase fw-bold">${i18nWaliPel.violationTypeLabel}</label>
            <div class="fw-bold fs-5 text-dark">${nama}</div>
        </div>
        <div class="mb-3">
            <label class="small text-muted d-block text-uppercase fw-bold">${i18nWaliPel.pointsLabel}</label>
            <div class="badge bg-danger fs-6">${skor} ${i18nWaliPel.pointsWord}</div>
        </div>
        <div class="mb-0">
            <label class="small text-muted d-block text-uppercase fw-bold">${i18nWaliPel.notesLabel}</label>
            <div class="p-3 bg-light rounded border mt-2" style="white-space: pre-wrap; font-size: 0.95rem;">${ket}</div>
        </div>
    `;
    document.getElementById('detailContent').innerHTML = content;
    
    // Inisialisasi dan tampilkan modal Bootstrap
    const detailModal = new bootstrap.Modal(document.getElementById('modalDetail'));
    detailModal.show();
}

// Jalankan loadData saat halaman selesai dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadData();
});
</script>
</body>
</html>