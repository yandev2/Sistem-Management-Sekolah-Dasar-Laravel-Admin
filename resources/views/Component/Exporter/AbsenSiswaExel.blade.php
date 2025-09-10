@php
use Carbon\Carbon;
$bulan = Carbon::parse($json['bulan'])->format('m');
$tahun = Carbon::parse($json['bulan'])->format('Y');

$jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth; // jumlah hari di bulan
$mingguList = [];
$borderHeader = "border: 2px solid black;";
for ($day = 1; $day <= $jumlahHari; $day++) { $tanggal=Carbon::createFromDate($tahun, $bulan, $day); if ($tanggal->
    format('D') == 'Sun') {
    $mingguList[] = $day;
    }
    }
    @endphp

    <table cellspacing="0" cellpadding="2" border="1">
        <thead>
            <tr>
                <th colspan={{ 9+$jumlahHari }} style="text-align:center; font-size:15px; height: 140px; border: 0; ">
                    LAPORAN ABSENSI SISWA <br> SEKOLAH DASAR NEGERI 30 TALANG UBI<br> TAHUN PELAJARAN {{
                    $json['tahun_ajaran'] }} <br>
                </th>
            </tr>
            <tr>
                <th style="text-align:left; height: 80px; border: 0; align-items: center; " colspan={{ 9+$jumlahHari }}>
                    <br>
                    Kelas : {{
                    $json['kelas']}} <br>Nama
                    wali kelas : {{
                    $json['wali_kelas']['nama']}} <br> Tanggal : {{ $json['bulan'] }}<br>
                </th>
            </tr>
            <tr>
                <th colspan="3" style="text-align:center;">NOMOR</th>
                <th rowspan="2" style="width: 150px; text-align:center;">NAMA</th>
                <th rowspan="2" style="width: 25px; text-align:center;">JK</th>
                <th colspan={{ $jumlahHari }} style="text-align:center;">TANGGAL</th>
                <th colspan="4" style="text-align:center;">JUMLAH</th>
            </tr>
            <tr>
                <th style="width: 20px; text-align:center;">NO</th>
                <th style="width: 150px; text-align:center;">NOMOR INDUK</th>
                <th style="width: 150px; text-align:center;">NISN</th>


                @for ($i = 1; $i <= $jumlahHari; $i++) @php $isMinggu=in_array($i, $mingguList); @endphp <th
                    style=" text-align: center; width: 25px; {{ $isMinggu ? 'background-color: #ffcccc; color: red;' : '' }} ">
                    {{ $i }}
                    </th>
                    @endfor
                    <th style="width: 20px;">H</th>
                    <th style="width: 20px;">I</th>
                    <th style="width: 20px;">S</th>
                    <th style="width: 20px;">A</th>

            </tr>
        </thead>
        <tbody>
            @foreach($json['siswa'] as $siswa)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td style="text-align: left;">{{ $siswa['nis'] }}</td>
                <td style="text-align: left;">{{ $siswa['nisn'] }}</td>
                <td style="text-align:left;">{{ $siswa['nama_siswa'] }}</td>
                <td style="text-align: center;">{{ $siswa['jk']=='Perempuan' ? 'P' :'L' }}</td>
                @for($i = 1; $i <= $jumlahHari; $i++) @php $currentDate=Carbon::createFromDate($tahun, $bulan, $i)->
                    format('Y-m-d');
                    $index = array_search($currentDate, $siswa['tanggal']);
                    $status = ($index !== false) ? $siswa['status'][$index] : '';
                    $isMinggu = in_array($i, $mingguList);
                    @endphp
                    <td style="text-align: center; {{ $isMinggu ? 'background-color: #ffcccc; color: red;' : '' }}">
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
    <br>
    <table style="border: 2" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th style=" text-align: center;" colspan={{ 5+$jumlahHari }}>TOTAL KESELURUHAN</th>
                <th style=" text-align: center;">{{ $json['total']['H']}}</th>
                <th style=" text-align: center;">{{ $json['total']['I']}}</th>
                <th style=" text-align: center;">{{ $json['total']['S']}}</th>
                <th style=" text-align: center;">{{ $json['total']['A']}}</th>
            </tr>
        </thead>
    </table>