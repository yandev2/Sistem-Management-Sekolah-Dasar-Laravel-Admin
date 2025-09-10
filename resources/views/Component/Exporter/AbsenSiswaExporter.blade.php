<div class="body">
    @php
    use Carbon\Carbon;
$bulan = Carbon::parse($json['bulan'])->format('m');
$tahun = Carbon::parse($json['bulan'])->format('Y');
    $jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth; // jumlah hari di bulan
    $mingguList = [];

    for ($day = 1; $day <= $jumlahHari; $day++) { $tanggal=Carbon::createFromDate($tahun, $bulan, $day); if ($tanggal->
        format('D') == 'Sun') {
        $mingguList[] = $day;
        }
        }
        @endphp

        <div class="header">
            <h1>DAFTAR HADIR SISWA</h1>
            <h2>SEKOLAH DASAR NEGERI 30 TALANG UBI</h2>
            <h3>TAHUN PELAJARAN {{ $json['tahun_ajaran'] }}</h3>
        </div>

        <div class="navbar">
            <table>
                <tr>
                    <td>KELAS</td>
                    <td>:</td>
                    <td>{{ $json['kelas']}}</td>
                </tr>
                <tr>
                    <td>NAMA WALI KELAS</td>
                    <td>:</td>
                    <td>{{ $json['wali_kelas']['nama']}}</td>
                </tr>
            </table>
        </div>

        <div class="spacing"></div>

        <div class="content">
            <table border="1" cellspacing="0" cellpadding="2" style="width: 100%">
                <thead>
                    <tr>
                        <th colspan="3">NOMOR</th>
                        <th style="width: 100px;" rowspan="3">NAMA</th>
                        <th rowspan="3">L/P</th>
                        <th colspan={{ $jumlahHari }}>BULAN</th>
                        <th colspan="4" rowspan="2">JUMLAH</th>
                    </tr>

                    <tr>
                        <th style="width: 17px;" rowspan="2">NO</th>
                        <th rowspan="2">NOMOR INDUK</th>
                        <th rowspan="2">NISN</th>
                        <th colspan={{ $jumlahHari }}>TANGGAL</th>
                    </tr>

                    <tr>
                        @for ($i = 1; $i <= $jumlahHari; $i++) @php $isMinggu=in_array($i, $mingguList); @endphp <th
                            style="width: 15px; {{ $isMinggu ? 'background-color: #ffcccc; color: red;' : '' }} ">
                            {{ $i }}
                            </th>
                            @endfor
                            <th style="width: 15px;">H</th>
                            <th style="width: 15px;">I</th>
                            <th style="width: 15px;">S</th>
                            <th style="width: 15px;">A</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($json['siswa'] as $siswa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $siswa['nis'] }}</td>
                        <td>{{ $siswa['nisn'] }}</td>
                        <td>{{ $siswa['nama_siswa'] }}</td>
                        <td style=" text-align: center;">{{ $siswa['jk'] }}</td>
                        @for($i = 1; $i <= $jumlahHari; $i++) @php $currentDate=Carbon::createFromDate($tahun, $bulan,
                            $i)->
                            format('Y-m-d');
                            $index = array_search($currentDate, $siswa['tanggal']);
                            $status = ($index !== false) ? $siswa['status'][$index] : '';
                            $isMinggu = in_array($i, $mingguList);
                            @endphp
                            <td
                                style=" text-align: center; {{ $isMinggu ? 'background-color: #ffcccc; color: red;' : '' }}">
                                {{ $status}}
                            </td>
                            @endfor
                            @php
                            // Hitung jumlah tiap status
                            $statusCount = array_count_values($siswa['status']);
                            $hadir = $statusCount['H'] ?? 0;
                            $izin = $statusCount['I'] ?? 0;
                            $sakit = $statusCount['S'] ?? 0;
                            $alpa = $statusCount['A'] ?? 0;
                            @endphp
                            <td style=" text-align: center;">{{ $hadir }}</td>
                            <td style=" text-align: center;">{{ $izin }}</td>
                            <td style=" text-align: center;">{{ $sakit }}</td>
                            <td style=" text-align: center;">{{ $alpa }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="spacing"></div>

        <div class="footer">
            <table style="width:100%;">
                <td style="width:15%">
                    <table border="1" cellspacing="0" cellpadding="4">
                        <thead>
                            <tr>
                                <th colspan="2">KETERANGAN</th>
                                <th colspan="1">JUMLAH</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>A</td>
                                <td>Alfa</td>
                                <td>{{ $json['total']['A']}}</td>
                            </tr>
                            <tr>
                                <td>S</td>
                                <td>Sakit</td>
                                <td>{{ $json['total']['S']}}</td>
                            </tr>
                            <tr>
                                <td>I</td>
                                <td>Izin</td>
                                <td>{{ $json['total']['I']}}</td>
                            </tr>
                            <tr>
                                <td>H</td>
                                <td>Hadir</td>
                                <td>{{ $json['total']['H']}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>

                <td style="width:15%; ">
                    <div class="sign-box" style="margin-left: 20px">
                        <p>MENGETAHUI</p>
                        <p>WALI KELAS</p>
                        <div class="spacer"></div>
                        <p>{{ $json['wali_kelas']['nama'] }}</p>
                        <p>NIP: {{ $json['wali_kelas']['nip']}}</p>
                    </div>
                </td>
                <td></td>
                <td style="width:15%; ">
                    <div class="sign-box">
                        <p>MENGETAHUI</p>
                        <p>KEPALA SEKOLAH</p>
                        <div class="spacer"></div>
                        <p>{{ $json['kepala_sekolah']['nama'] }}</p>
                        <p>NIP: {{ $json['kepala_sekolah']['nip']}}</p>
                    </div>
                </td>
            </table>
        </div>
</div>

<style>
    @page {
        size: A4 landscape;
        margin: 5mm;
    }

    .body {
        font-family: 'Times New Roman', Times, serif;
    }

    .header {
        width: 100%;
        text-align: center;
        font-size: 10px
    }

    .header h1,
    .header h2,
    .header h3 {
        margin: 2px 0;
    }

    .navbar {
        font-size: 10px;
    }

    .sign-box p {
        margin: 2px 0;
    }

    .spacer {
        height: 60px;
    }

    .spacer1 {
        height: 80px;
    }

    .spacing {
        height: 10px;
    }

    .content td {
        font-size: 10px;
    }

    .content th {
        text-align: center;
        font-size: 10px;
    }

    .content td {
        font-size: 10px;
    }

    .footer th {
        text-align: center;
        font-size: 10px;
    }

    .footer td {
        text-align: center;
        font-size: 10px;
    }

    .footer p {
        text-align: center;
        font-size: 10px;
    }

    thead {
        background-color: rgb(0, 255, 255);
    }

    tbody {
        background-color: rgba(0, 255, 255, 0.034);
    }
</style>
<!--php artisan make:view Component/Exporter/AbsenSiswaExporter