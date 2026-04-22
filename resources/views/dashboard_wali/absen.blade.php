<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wali Murid | Absensi Murid</title>
     @if(isset($sekolah->logo))
    <link rel="icon" type="image/png" href="{{ asset($sekolah->logo) }}">
    @else
    <link rel="icon" type="image/png" href="{{ asset('assets/img/default-favicon.png') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --primary-green: #198754; --soft-green: #e8f5e9; }
        body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .btn-success { background-color: var(--primary-green); border: none; }
        
        /* Table Responsive to Card */
        @media (max-width: 768px) {
            .table-responsive-stack tr { display: flex; flex-direction: column; border-bottom: 2px solid #eee; margin-bottom: 15px; padding-bottom: 10px; }
            .table-responsive-stack td { border: none; padding: 5px 0; }
            .table-responsive-stack thead { display: none; }
            .mobile-label { font-weight: bold; color: var(--primary-green); display: inline-block; width: 120px; }
        }
        @media (min-width: 769px) { .mobile-label { display: none; } }

        /* Calendar Grid */
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; }
        .day-box { height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-weight: bold; font-size: 0.9rem; color: white; background: #ddd; }
        .day-absent { background-color: #198754 !important; } /* Hijau */
        .day-present { background-color: #dc3545 !important; }  /* Merah */
        .day-sunday { background-color: #fd7e14 !important; }  /* Jingga */
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('wali.home') }}" class="btn btn-outline-success rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
        <h4 class="fw-bold mb-0 text-success">Absensi Ananda</h4>
    </div>

    <div class="card p-3 mb-4">
        <form action="{{ route('wali.absen.index') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <select name="bulan" class="form-select">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ (int)$bulan == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month((int)$m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <select name="tahun" class="form-select">
                    @for($y = date('Y'); $y >= date('Y')-2; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100"><i class="bi bi-filter"></i> Filter</button>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table align-middle table-responsive-stack">
                <thead class="bg-light">
                    <tr>
                        <th>Nama Murid</th>
                        <th>Kelas</th>
                        <th class="text-center">Kalender</th>
                        <th class="text-center">Total Hadir</th>
                        <th class="text-center">Tidak Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="mobile-label">Nama:</span> <strong>{{ $murid->nama_lengkap }}</strong></td>
                        <td><span class="mobile-label">Kelas:</span> {{ $murid->nama_kelas ?? 'Belum ada kelas' }}</td>
                        <td class="text-center">
                            <span class="mobile-label">Opsi:</span>
                            <button class="btn btn-sm btn-outline-success" onclick="openCalendar({{ $murid->id }})">
                                <i class="bi bi-calendar3"></i> Lihat Detail
                            </button>
                        </td>
                        <td class="text-center">
                            <span class="mobile-label">Hadir:</span>
                            <span class="badge bg-success px-3">{{ $rekap->total_hadir ?? 0 }} Hari</span>
                        </td>
                        <td class="text-center">
                            <span class="mobile-label">Absen:</span>
                            <span class="badge bg-danger px-3">{{ $rekap->total_tidak_hadir ?? 0 }} Hari</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCalendar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
               <h5 class="modal-title">
                    Kalender Absensi - {{ Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="calendar-grid" id="calendarContainer">
                    </div>
                <div class="mt-4 d-flex justify-content-center gap-3 small fw-bold text-muted">
                    <span><i class="bi bi-square-fill text-success"></i> Hadir</span>
                    <span><i class="bi bi-square-fill text-danger"></i> Tidak Hadir</span>
                    <span><i class="bi bi-square-fill text-warning"></i> Minggu</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openCalendar(idMurid) {
        const container = document.getElementById('calendarContainer');
        container.innerHTML = '<div class="spinner-border text-success"></div>';
        
        const myModal = new bootstrap.Modal(document.getElementById('modalCalendar'));
        myModal.show();

        const bulan = "{{ $bulan }}";
        const tahun = "{{ $tahun }}";

        fetch(`{{ route('wali.absen.calendar') }}?id_murid=${idMurid}&bulan=${bulan}&tahun=${tahun}`)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                // Mendapatkan jumlah hari dalam bulan tersebut
                const daysInMonth = new Date(tahun, bulan, 0).getDate();

                for (let d = 1; d <= daysInMonth; d++) {
                    // Padding bulan dan hari agar formatnya YYYY-MM-DD (contoh: 2025-01-05)
                    const padBulan = String(bulan).padStart(2, '0');
                    const padHari = String(d).padStart(2, '0');
                    const dateStr = `${tahun}-${padBulan}-${padHari}`;
                    
                    // Cek hari (0 = Minggu)
                    const dayOfWeek = new Date(tahun, bulan - 1, d).getDay();
                    
                    let statusClass = '';
                    if (dayOfWeek === 0) {
                        statusClass = 'day-sunday'; 
                    } else if (data[dateStr]) {
                        statusClass = (data[dateStr] === 'Hadir') ? 'day-present' : 'day-absent';
                    }

                    container.innerHTML += `<div class="day-box ${statusClass}">${d}</div>`;
                }
            })
            .catch(err => {
                container.innerHTML = '<span class="text-danger">Gagal memuat data.</span>';
                console.error(err);
            });
    }
</script>
</body>
</html>