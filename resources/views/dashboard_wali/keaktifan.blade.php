<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wali Murid | Keaktifan Murid</title>
     @if(isset($sekolah->logo))
    <link rel="icon" type="image/png" href="{{ asset($sekolah->logo) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('assets/img/default-favicon.png') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --primary-blue: #0d6efd; --primary-red: #dc3545; --bg-light: #f8f9fa; }
        body { background-color: var(--bg-light); font-family: 'Plus Jakarta Sans', sans-serif; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        /* Responsive: Desktop Table, Mobile Card */
        @media (max-width: 768px) {
            .desktop-view { display: none; }
            .mobile-view { display: block; }
            .mobile-card { margin-bottom: 15px; border-left: 5px solid var(--primary-blue); }
            .mobile-card.inactive { border-left-color: var(--primary-red); }
        }
        @media (min-width: 769px) {
            .mobile-view { display: none; }
            .desktop-view { display: block; }
        }

        .img-doc { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; cursor: pointer; transition: 0.3s; }
        .img-doc:hover { transform: scale(1.1); }
        .status-badge { font-size: 0.8rem; padding: 5px 12px; border-radius: 50px; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('wali.home') }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
        <h4 class="fw-bold mb-0 text-primary">Monitoring Keaktifan</h4>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card p-4 mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-funnel-fill me-2"></i>Filter Tanggal</h6>
                <input type="date" id="filterTanggal" class="form-control mb-4" value="{{ date('Y-m-d') }}" onchange="loadKeaktifan()">
                
                <hr>
                
                <h6 class="fw-bold mb-3 text-center">Statistik Harian</h6>
                <div style="height: 250px;">
                    <canvas id="keaktifanChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-0" id="namaAnanda">-</h5>
                        <small class="text-muted" id="kelasAnanda">-</small>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3">Live Data</span>
                </div>

                <div class="table-responsive desktop-view">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kegiatan</th>
                                <th class="text-center">Status</th>
                                <th>Foto</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>

                <div id="mobileBody" class="mobile-view"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">Dokumentasi Kegiatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img src="" id="imgPreview" class="img-fluid w-100">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let myChart;

    function initChart(chartData) {
        const ctx = document.getElementById('keaktifanChart').getContext('2d');
        if(myChart) myChart.destroy();
        myChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }

    function loadKeaktifan() {
        const tgl = document.getElementById('filterTanggal').value;
        const tableBody = document.getElementById('tableBody');
        const mobileBody = document.getElementById('mobileBody');

        tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4"><div class="spinner-border text-primary"></div></td></tr>';
        mobileBody.innerHTML = '';

        fetch(`{{ route('wali.keaktifan.data') }}?tanggal=${tgl}`)
            .then(res => res.json())
            .then(res => {
                document.getElementById('namaAnanda').innerText = res.murid.nama_lengkap;
                document.getElementById('kelasAnanda').innerText = "Kelas: " + (res.murid.nama_kelas || '-');
                
                initChart(res.chart);

                let tHtml = '';
                let mHtml = '';

                if(res.keaktifan.length === 0) {
                    const emptyMsg = `<div class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>Belum ada data keaktifan pada tanggal ini.</div>`;
                    tableBody.innerHTML = `<tr><td colspan="4">${emptyMsg}</td></tr>`;
                    mobileBody.innerHTML = emptyMsg;
                } else {
                    res.keaktifan.forEach(k => {
                        const statusIcon = k.is_active == 1 ? '<i class="bi bi-check-circle-fill text-primary fs-5"></i>' : '<i class="bi bi-x-circle-fill text-danger fs-5"></i>';
                        const statusText = k.is_active == 1 ? 'Aktif' : 'Tidak Aktif';
                        const badgeClass = k.is_active == 1 ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger';
                        const fotoPath = k.foto ? `/${k.foto}` : 'https://placehold.co/100x100?text=No+Photo';

                        // Render Tabel
                        tHtml += `
                            <tr>
                                <td class="fw-bold">${k.nama_keaktifan}</td>
                                <td class="text-center">${statusIcon}<br><small>${statusText}</small></td>
                                <td><img src="${fotoPath}" class="img-doc" onclick="viewFoto('${fotoPath}')"></td>
                                <td><small class="text-muted">${k.keterangan || '-'}</small></td>
                            </tr>`;

                        // Render Mobile Card
                        mHtml += `
                            <div class="card mobile-card p-3 shadow-sm ${k.is_active == 1 ? '' : 'inactive'}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0">${k.nama_keaktifan}</h6>
                                    <span class="badge ${badgeClass}">${statusText}</span>
                                </div>
                                <div class="d-flex gap-3">
                                    <img src="${fotoPath}" class="img-doc" style="width: 70px; height: 70px;" onclick="viewFoto('${fotoPath}')">
                                    <div class="small text-muted">${k.keterangan || '-'}</div>
                                </div>
                            </div>`;
                    });
                    tableBody.innerHTML = tHtml;
                    mobileBody.innerHTML = mHtml;
                }
            })
            .catch(err => {
                console.error(err);
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Gagal memuat data.</td></tr>';
            });
    }

    function viewFoto(path) {
        document.getElementById('imgPreview').src = path;
        new bootstrap.Modal(document.getElementById('modalFoto')).show();
    }

    document.addEventListener('DOMContentLoaded', loadKeaktifan);
</script>
</body>
</html>