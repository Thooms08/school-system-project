<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; margin: 0.5cm; }
        .header { text-align: center; border-bottom: 3px solid black; padding-bottom: 10px; position: relative; }
        .logo { position: absolute; left: 0; top: 0; width: 80px; }
        .header-text h2, .header-text h3, .header-text p { margin: 0; padding: 0; }
        .content { margin-top: 20px; }
        .title { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 20px; }
        .section-title { font-weight: bold; margin-top: 15px; }
        table { width: 100%; border: none; }
        td { vertical-align: top; padding: 2px 0; }
        .dots { border-bottom: 1px dotted black; display: inline-block; width: 100%; height: 15px; }
        .footer { margin-top: 40px; }
        .sign-table { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        @if($sekolah->logo)
            <img src="{{ public_path($sekolah->logo) }}" class="logo">
        @endif
        <div class="header-text">
            <h3>YAYASAN BUNGA MELATI</h3>
            <h2>{{ strtoupper($sekolah->nama_sekolah) }}</h2>
            <p>{{ $sekolah->alamat }}</p>
        </div>
    </div>

    <div class="content">
        <div class="title">FORMULIR PENDAFTARAN SISWA BARU<br>TAHUN PELAJARAN {{ date('Y') }}/{{ date('Y')+1 }}</div>

        <div class="section-title">A. Biodata Anak</div>
        <table>
            <tr><td width="30%">1. Nama Calon Siswa</td><td width="3%">:</td><td>{{ $murid->nama_lengkap }}</td></tr>
            <tr><td>2. Tempat/Tanggal lahir</td><td>:</td><td>{{ $murid->tempat_lahir }}, {{ \Carbon\Carbon::parse($murid->tgl_lahir)->format('d-m-Y') }}</td></tr>
            <tr><td>3. Jenis Kelamin</td><td>:</td><td>{{ ucfirst($murid->jenis_kelamin) }}</td></tr>
            <tr><td>4. Alamat</td><td>:</td><td>{{ $murid->alamat_detail }}, {{ $murid->desa_kelurahan }}, {{ $murid->kota_kabupaten }}</td></tr>
            <tr><td>5. Nomor Telp/Hp</td><td>:</td><td>{{ $murid->no_hp }}</td></tr>
            <tr><td>6. Asal Sekolah</td><td>:</td><td>{{ $murid->sekolah_asal }}</td></tr>
        </table>

        <div class="section-title">B. Nama Orang Tua /Wali Murid</div>
        <table>
            @if($murid->wali)
            <tr><td width="30%">1. Nama Ayah</td><td width="3%">:</td><td>{{ $murid->wali->nama_ayah }}</td></tr>
            <tr><td>2. Nama Ibu</td><td>:</td><td>{{ $murid->wali->nama_ibu }}</td></tr>
            <tr><td>3. Tempat/Tanggal lahir Ayah</td><td>:</td><td>{{ $murid->wali->tempat_lahir_ayah }}, {{ \Carbon\Carbon::parse($murid->wali->tgl_lahir_ayah)->format('d-m-Y') }}</td></tr>
            <tr><td>4. Pendidikan Tertinggi</td><td>:</td><td>{{ $murid->wali->pendidikan_ayah }}</td></tr>
            <tr><td>5. Pekerjaan Ayah</td><td>:</td><td>{{ $murid->wali->pekerjaan_ayah }}</td></tr>
            <tr><td>6. Alamat</td><td>:</td><td>{{ $murid->alamat_detail }}</td></tr>
            @endif
        </table>

        <div class="section-title">C. Persyaratan yang diserahkan</div>
        <table>
            <tr><td width="5%">1.</td><td width="50%">Foto Copy Ijasah dan Nilai</td><td>: .......................................</td></tr>
            <tr><td>2.</td><td>Foto Copy Akta Kelahiran</td><td>: .......................................</td></tr>
            <tr><td>3.</td><td>Foto Copy Kartu Keluarga</td><td>: .......................................</td></tr>
            <tr><td>4.</td><td>Pas Foto ukuran 3x4 cm (3 lbr)</td><td>: .......................................</td></tr>
        </table>

        <p style="font-size: 11pt; margin-top: 30px;">Demikian data diri kami sampaikan untuk dapat dipergunakan sebagaimana mestinya.</p>

        <div class="footer">
            <table class="sign-table">
                <tr>
                    <td></td>
                    <td>{{ $sekolah->kota_kabupaten ?? 'Temanggung' }}, ...........................</td>
                </tr>
                <tr>
                    <td width="50%">Orang Tua/ Wali Murid</td>
                    <td width="50%">Calon Siswa</td>
                </tr>
                <tr><td height="60px"></td><td></td></tr>
                <tr>
                    <td>(........................................)</td>
                    <td>(<strong>{{ $murid->nama_lengkap }}</strong>)</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top: 20px;">Petugas yang menerima</td>
                </tr>
                <tr><td height="50px"></td><td></td></tr>
                <tr>
                    <td colspan="2">(........................................)</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>