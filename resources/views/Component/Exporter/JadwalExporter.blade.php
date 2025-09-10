<h1> JADWAL PELAJARAN <br> KELAS {{ $json['kelas'] }}</h1>

<table border="1" cellspacing="0" cellpadding="5" style="text-align: center; width:100%;">
    <thead>
        <tr>
            <th>SENIN</th>
            <th>SELASA</th>
            <th>RABU</th>
            <th>KAMIS</th>
            <th>JUMAT</th>
            <th>SABTU</th>
        </tr>
    </thead>
    <tbody>
        @php
        // ubah jadwal jadi associative array: ['SENIN' => [...], 'SELASA' => [...]]
        $byHari = collect($json['jadwal'])->keyBy('hari');
        $maxRows = $byHari->max(fn($h) => count($h['mapel']));
        @endphp

        {{-- Loop baris sesuai jumlah mapel terbanyak --}}
        @for ($i = 0; $i < $maxRows; $i++) <tr>
            @foreach (['SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU'] as $hari)
            <td>
                @if (isset($byHari[$hari]['mapel'][$i]))
                {{ $byHari[$hari]['mapel'][$i]['nama'] }} <br>
                <small>
                    {{ $byHari[$hari]['mapel'][$i]['jam_masuk'] }}
                    -
                    {{ $byHari[$hari]['mapel'][$i]['jam_keluar'] }}
                </small>
                @endif
            </td>
            @endforeach
            </tr>
            @endfor
    </tbody>
</table>

<style>
    @page {
        size: A4 landscape;
        margin: 5mm;
    }

    .body {
        font-family: 'Times New Roman', Times, serif;
    }

    h1 {
        text-align: center;
        font-size: 20px
    }

    thead {
        background-color: aqua;
        font-size: 15px;
    }

    tbody{
        font-size: 12px;
    }
</style>
<!--php artisan make:view Component/Exporter/AbsenSiswaExporter