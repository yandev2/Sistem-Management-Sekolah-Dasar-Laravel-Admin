<?php

namespace App\Http\Controllers;

use App\Exports\AbsenGuruExport;
use App\Exports\AbsenSiswaExport;
use App\Models\AbsenGuruModel;
use App\Models\AbsenSiswaModel;
use App\Models\JadwalModel;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export_absen_siswa(Request $request)
    {

        $type = urldecode($request->type);

        $idsJson = urldecode($request->ids);
        $id = json_decode($idsJson, true);
        $data = AbsenSiswaModel::with('siswa')->whereIn('id', $id)->get();
        $grouped = $data->groupBy(fn($item) => $item->siswa->id);
        $siswa = $data->toArray();

        //=>hitung total semua kehadiran

        $status = ['H', 'I', 'S', 'A'];
        $total_kehadiran = collect($status)->mapWithKeys(function ($status) use ($siswa) {
            return [$status => collect($siswa)->where('status', $status)->count()];
        });

        //=>ambil tahun ajaran yang sedang di export

        $tanggal = Carbon::parse($siswa[0]['tanggal']);
        $tahun = $tanggal->year;
        $bulans = $tanggal->month;

        if ($bulans < 7) {
            $tahun_ajaran = ($tahun - 1) . '/' . $tahun;
        } else {
            $tahun_ajaran = $tahun . '/' . ($tahun + 1);
        }

        //=>ambil data absen dan walikelas
        $data_absen = [];
        $data_kelas = [];
        $grouped->each(function ($item) use (&$data_absen, &$data_kelas) {
            $data = $item->first()->siswa;
            $data_kelas = [
                "wali_kelas" =>  [
                    "nama" => $data->kelas->waliKelas->name,
                    "nip" => $data->kelas->waliKelas->nip,
                ],
                "kelas" =>  $data->kelas->nama_kelas,
            ];
            $data_absen[] = [
                "nama_siswa" => $item[0]->siswa->nama_siswa,
                "nis" => $item[0]->siswa->nis,
                "nisn" => $item[0]->siswa->nisn,
                "jk" => $item[0]->siswa->jenis_kelamin,
                'tanggal' => $item->pluck('tanggal')->toArray(),
                'status' => $item->pluck('status')->toArray(),
                'keterangan' => $item->pluck('keterangan')->toArray(),
            ];
        });

        //=>ambil data kepala sekolah

        $kepsek = User::role('kepsek', 'api')->first();

        //=>maping data final
        $json = [
            "kelas" => $data_kelas['kelas'],
            "wali_kelas" => $data_kelas['wali_kelas'],
            "bulan" => $tanggal,
            "total" => $total_kehadiran,
            "tahun_ajaran" => $tahun_ajaran,
            "kepala_sekolah" => [
                "nama" => $kepsek->name,
                "nip" => $kepsek->nip
            ],
            "siswa" => $data_absen,
        ];

        if ($type == 'pdf') {
            $pdf = Pdf::loadView('component.exporter.AbsenSiswaExporter', compact('json'))
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ])
                ->setPaper('A4', 'landscape');

            return response()->stream(
                fn() => print($pdf->output()),
                200,
                [
                    "Content-Type" => "application/pdf",
                    "Content-Disposition" => "inline; filename=absensi_siswa.pdf"
                ]
            );
        } else {
            return Excel::download(new AbsenSiswaExport($json), 'absen_siswa.xlsx');
        }
    }

    public function export_absen_guru(Request $request)
    {
        $type = urldecode($request->type);
        $idsJson = urldecode($request->ids);
        $id = json_decode($idsJson, true);
        $data = AbsenGuruModel::with('guru')->whereIn('id', $id)->get();
        $grouped = $data->groupBy(fn($item) => $item->guru->id);
        $guru = $data->toArray();

        //=>hitung total semua kehadiran

        $status = ['H', 'I', 'S', 'A'];
        $total_kehadiran = collect($status)->mapWithKeys(function ($absen_masuk) use ($guru) {
            return [$absen_masuk => collect($guru)->where('absen_masuk', $absen_masuk)->count()];
        });

        //=>ambil tahun ajaran yang sedang di export

        $tanggal = Carbon::parse($guru[0]['tanggal']);
        $tahun = $tanggal->year;
        $bulans = $tanggal->month;

        if ($bulans < 7) {
            $tahun_ajaran = ($tahun - 1) . '/' . $tahun;
        } else {
            $tahun_ajaran = $tahun . '/' . ($tahun + 1);
        }

        //=>ambil data absen dan walikelas
        $data_absen = [];
        $grouped->each(function ($item) use (&$data_absen) {
            $durasiList = $item->map(function ($row) {
                if ($row->absen_masuk && $row->absen_keluar) {
                    $start = Carbon::parse($row->created_at);
                    $end   = Carbon::parse($row->updated_at);

                    $minutes = $start->diffInMinutes($end);
                    $hours   = floor($minutes / 60);
                    $mins    = $minutes % 60;

                    return "{$hours} jam {$mins} menit";
                }
                return "-";
            })->toArray();
            $data_absen[] = [
                "nama_guru" => $item[0]->guru->name,
                "nip" => $item[0]->guru->nip,
                "jk" => $item[0]->guru->jenis_kelamin,
                "absen_masuk" => $item->pluck('absen_masuk')->toArray(),
                "absen_keluar" => $item->pluck('absen_keluar')->toArray(),
                'tanggal' => $item->pluck('tanggal')->toArray(),
                'durasi' => $durasiList,
                'keterangan' => $item->pluck('keterangan')->toArray(),
            ];
        });

        //=>ambil data kepala sekolah

        $kepsek = User::role('kepsek', 'api')->first();

        $json = [
            "bulan" => $tanggal,
            "tahun_ajaran" => $tahun_ajaran,
            "guru" => $data_absen,
            "total" => $total_kehadiran,
            "kepala_sekolah" => [
                "nama" => $kepsek->name,
                "nip" => $kepsek->nip
            ],
        ];


        if ($type == 'pdf') {
            $pdf = Pdf::loadView('component.exporter.AbsenGuruExporter', compact('json'))
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ])
                ->setPaper('A4', 'landscape');
            return response()->stream(
                fn() => print($pdf->output()),
                200,
                [
                    "Content-Type" => "application/pdf",
                    "Content-Disposition" => "inline; filename=absensi_guru.pdf"
                ]
            );
        } else {
            return Excel::download(new AbsenGuruExport($json), 'absen_guru.xlsx');
        }
    }

    public function export_nilai(Request $request)
    {
        return $request;
    }

    public function export_jadwal(Request $request)
    {
        $idsJson = urldecode($request->ids);
        $id = json_decode($idsJson, true);
        $data = JadwalModel::with(['mapel', 'kelas'])
            ->whereIn('id', $id)
            ->get()                          // sekarang $data = Collection
            ->groupBy('hari');               // masih Collection, key = hari

        $data_jadwal = $data->map(function ($items, $hari) {
            return [
                'hari'  => $hari,
                'mapel' => $items->map(fn($i) => [
                    'nama'       => $i->mapel->kode_mapel,
                    'jam_masuk'  => $i->jam_masuk,
                    'jam_keluar' => $i->jam_keluar,
                ])->toArray(),
            ];
        })->values()->toArray();

        $json = [
            'kelas'  => $data->first()->first()->kelas->nama_kelas ?? null,
            'jadwal' => $data_jadwal,
        ];

      
        $pdf = Pdf::loadView('component.exporter.JadwalExporter', compact('json'))
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ])
            ->setPaper('A4', 'landscape');


        return response()->stream(
            fn() => print($pdf->output()),
            200,
            [
                "Content-Type" => "application/pdf",
                "Content-Disposition" => "inline; filename=jadwal_pelajaran.pdf"
            ]
        );
    }

    public function export_siswa(Request $request)
    {
        return $request;
    }
}
