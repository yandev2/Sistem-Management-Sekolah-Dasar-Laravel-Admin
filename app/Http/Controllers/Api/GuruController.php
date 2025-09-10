<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArrayResource;
use App\Models\AbsenGuruModel;
use App\Models\AbsenSiswaModel;
use App\Models\JadwalModel;
use App\Models\KelasModel;
use App\Models\MapelModel;
use App\Models\SiswaModel;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Str;

class GuruController extends Controller
{
    public function login(Request $request)
    {
        $validator = FacadesValidator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => ['required'],
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return new ArrayResource(false, $validator->messages()->all(), null);
        }

        $email = $request->email;
        $password = $request->password;
        $data = User::where('email', $email)->first();

        if ($data == null) {
            return new ArrayResource(false, 'email anda tidak valid', null);
        }
        if (!Hash::check($password, $data->password)) {
            return new ArrayResource(false, 'password anda tidak valid', null);
        }
        if ($request->id_device != null) {
            $data->update([
                'id_device' => $request->id_device
            ]);
        }
        if (!$data->token) {
            $data->token = Str::random(60);
            $data->save();
        }
        $data->foto = $data->foto != null ? url('storage/' . $data->foto) : null;

        $data_jadwal = $data->kelas->jadwal->groupBy('hari')->map(function ($items, $hari) {
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
            "nama" => $data->name,
            "email" => $data->email,
            "token" => $data->token,
            "nip" => $data->nip,
            "nomor_hp" => $data->nomor_hp,
            "jenis_kelamin" => $data->jenis_kelamin,
            "alamat" => $data->alamat,
            "foto" => $data->foto,
            "role" => $data->getRoleNames()->toArray(),
            "kelas" => $data->kelas->nama_kelas,
            "jadwal" => $data_jadwal
        ];
        return new ArrayResource(true, 'Berhasil melakukan login', $json);
    }

    public function auto_login(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $device_id = $request->device_id;

            $data = User::where('token', $token)->first();

            if ($data) {

                $data->update([
                    'device_id' => $device_id ?? $data->device_id,
                ]);
                $data->foto = $data->foto != null ? url('storage/' . $data->foto) : null;

                $data_jadwal = $data->kelas->jadwal->groupBy('hari')->map(function ($items, $hari) {
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
                    "nama" => $data->name,
                    "email" => $data->email,
                    "token" => $data->token,
                    "nip" => $data->nip,
                    "nomor_hp" => $data->nomor_hp,
                    "jenis_kelamin" => $data->jenis_kelamin,
                    "alamat" => $data->alamat,
                    "foto" => $data->foto,
                    "role" => $data->getRoleNames()->toArray(),
                    "kelas" => $data->kelas->nama_kelas,
                    "jadwal" => $data_jadwal
                ];
                return new ArrayResource(true, 'Berhasil melakukan login', $json);
            }
            return new ArrayResource(false, 'Sesi login anda telah berakhir, silahkan login kembali', null);
        } catch (\Throwable $th) {
            return new ArrayResource(false, 'Error',  $th->getMessage());
        }
    }
    public function update_profile(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $alamat = $request->alamat;
            $nomor_hp = $request->nomor_hp;

            $guru = User::where('token', $token)->first();

            if ($guru) {
                $new_image = null;
                if ($request->hasFile('foto')) {
                    $image = $request->file('foto');
                    $image->storeAs('guru', $image->hashName(), 'public');
                    if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
                        Storage::disk('public')->delete($guru->foto);
                    }
                    $new_image = 'guru/' . $image->hashName();
                }
                $guru->update([
                    'alamat' => $alamat ?? $guru->alamat,
                    'nomor_hp' => $nomor_hp ?? $guru->nomor_hp,
                    'foto' => $new_image != null ? $new_image : $guru->foto

                ]);
                return new ArrayResource(true, 'Data profile anda berhasil di perbarui', $guru);
            }
            return new ArrayResource(false, 'Token anda tidak valid', null);
        } catch (\Throwable $th) {
            return new ArrayResource(false, 'Error',  $th->getMessage());
        }
    }
    public function update_password(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $password = $request->password;
            $new_password = $request->new_password;

            $guru = User::where('token', $token)->first();

            if ($guru) {
                if (!Hash::check($password, $guru->password)) {
                    return new ArrayResource(false, 'tidak dapat merubah password. password lama anda tidak valid', null);
                }

                $guru->update([
                    'password' => $new_password
                ]);
                $guru->save();
                return new ArrayResource(true, 'Password anda berhasil di perbarui', $guru);
            }
            return new ArrayResource(false, 'Token anda tidak valid', null);
        } catch (\Throwable $th) {
            return new ArrayResource(false, 'Error',  $th->getMessage());
        }
    }
    public function get_kelas(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $guru = User::where('token', $token)->first();

            if ($guru) {
                $kelas = KelasModel::withCount('siswa')->get();
                $json = $kelas->map(function ($k) {
                    return [
                        "id" => $k->id,
                        "id_tingkat" => $k->tingkatKelas->id,
                        "nama_kelas" => $k->nama_kelas,
                        'wali_kelas' => $k->waliKelas->name,
                        "jumlah_siswa" => $k->siswa_count,
                    ];
                });
                return new ArrayResource(true, 'Data kelas', $json);
            }
            return new ArrayResource(false, 'Token anda tidak valid', null);
        } catch (\Throwable $th) {
            return new ArrayResource(false, 'Error',  $th->getMessage());
        }
    }
    public function get_siswa(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $id_kelas = $request->id_kelas;
            $guru =  User::where('token', $token)->first();
            if ($guru) {
                $siswa =  SiswaModel::where('id_kelas', $id_kelas)->get();
                $json = $siswa->map(function ($k) {
                    return [
                        "jumlah_siswa" => $k->count(),
                        "siswa" => [
                            "id_siswa" => $k->id,
                            "nama_siswa" => $k->nama_siswa,
                            "jenis_kelamin" => $k->jenis_kelamin,
                            "alamat" => $k->alamat,
                        ]
                    ];
                });
                return new ArrayResource(true, 'Berhasil mengambil data siswa', $json);
            }
            return new ArrayResource(false, 'Token anda tidak valid', null);
        } catch (\Throwable $th) {
            return new ArrayResource(false, 'Error',  $th->getMessage());
        }
    }
    public function get_absen_siswa(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $id_kelas = $request->id_kelas;
            $tanggal = $request->tanggal;
            $guru =  User::where('token', $token)->first();
            if ($guru) {
                $absen = AbsenSiswaModel::whereHas('siswa', function ($q) use ($id_kelas) {
                    $q->where('id_kelas', $id_kelas);
                })->with('siswa')->whereDate('created_at', $tanggal)->get();
                $json = $absen->map(function ($k) {
                    return  [
                        "nama_siswa" => $k->siswa->nama_siswa,
                        "tanggal" => $k->tanggal,
                        "status" => $k->status,
                        "keterangan" => $k->keterangan ?? ""
                    ];
                });
                return new ArrayResource(true, 'Berhasil mengambil data absen siswa', $json);
            }
            return new ArrayResource(false, 'Token anda tidak valid', null);
        } catch (\Throwable $th) {
            return new ArrayResource(false, 'Error',  $th->getMessage());
        }
    }
    public function add_absen_siswa(Request $request)
    {
        try {
            $token = $request->bearerToken();

            $id_siswa = $request->id_siswa;
            $tanggal = $request->tanggal;
            $status = $request->status;
            $keterangan = $request->keterangan;

            $guru =  User::where('token', $token)->first();
            if ($guru) {
                $absen = AbsenSiswaModel::where('id_siswa',  $id_siswa)->whereDate('tanggal', $tanggal)->first();
                if ($absen) {
                    $absen->update([
                        'id_siswa' => $id_siswa,
                        'tanggal' => $tanggal,
                        'status' => $status,
                        'keterangan' => $keterangan ?? null,
                    ]);
                    return new ArrayResource(true, 'Berhasil Memperbarui data absen Siswa', $absen);
                } else {
                    $absen = AbsenSiswaModel::create([
                        'id_siswa' => $id_siswa,
                        'status' => $status,
                        'tanggal' => $tanggal,
                        'keterangan' => $keterangan ?? null,
                    ]);
                    return new ArrayResource(true, 'Berhasil Menambahkan data absen siswa', $absen);
                }
            }
            return new ArrayResource(false, 'Token anda tidak valid', null);
        } catch (\Throwable $th) {
            return new ArrayResource(false, 'Error',  $th->getMessage());
        }
    }
    public function add_absen_guru(Request $request)
    {
        try {
            $token = $request->bearerToken();

            $tanggal = $request->tanggal;
            $absen_masuk = $request->absen_masuk;
            $absen_keluar = $request->absen_keluar;
            $keterangan = $request->keterangan;
            $device_id = $request->device_id;
            $now = now()->format('Y-m-d');

            $guru =  User::where('token', $token)->first();
            if ($guru) {
                $absen = AbsenGuruModel::where('id_guru',  $guru->id)->whereDate('tanggal', $tanggal)->first();
                if ($tanggal < $now) {
                    return new ArrayResource(true, 'Tidak bisa melakukan absen keluar tanggal sudah lewat', null);
                }
                if ($absen) {
                    if (!is_null($absen->getOriginal('absen_keluar'))) {
                        return new ArrayResource(true, 'Anda sudah melakukan absen keluar', null);
                    }

                    if ($absen->tanggal != $now) {
                        return new ArrayResource(true, 'Tidak bisa melakukan absen keluar tanggal sudah lewat', null);
                    }
                    $absen->update([
                        'id_guru' => $guru->id,
                        'device_id' => $device_id ?? null,
                        'tanggal' => $absen->tanggal,
                        'absen_masuk' => $absen->absen_masuk,
                        'absen_keluar' => $absen_keluar,
                        'keterangan' => $keterangan ?? null,
                    ]);
                    $absen->id_guru = $guru->name;
                    return new ArrayResource(true, 'Berhasil melakukan absen keluar', $absen);
                } else {
                    $absen = AbsenGuruModel::create([
                        'device_id' => $device_id ?? null,
                        'id_guru' => $guru->id,
                        'tanggal' => $now,
                        'absen_masuk' => $absen_masuk,
                        'absen_keluar' => null,
                        'keterangan' => $keterangan ?? null,
                    ]);
                    $absen->id_guru = $guru->name;
                    return new ArrayResource(true, 'Berhasil melakukan absen masuk', $absen);
                }
            }
            return new ArrayResource(false, 'Token anda tidak valid', null);
        } catch (\Throwable $th) {
            return new ArrayResource(false, 'Error',  $th->getMessage());
        }
    }
    public function get_absen_guru(Request $request)
    {
        try {
            $token = $request->bearerToken();

            $guru =  User::where('token', $token)->first();
            if ($guru) {
                $absen = AbsenGuruModel::where('id_guru',  $guru->id)->orderBy('created_at', 'desc')->paginate(10);
                $json = $absen->getCollection()->map(function ($k) {
                    return [
                        "id" => $k->id,
                        'id_guru' => $k->guru->name,
                        'device_id' => $k->device_id ?? null,
                        'tanggal' => $k->tanggal,
                        'absen_masuk' => $k->absen_masuk,
                        'absen_keluar' => $k->absen_keluar,
                        'keterangan' => $k->keterangan ?? null,
                    ];
                });
                return new ArrayResource(true, 'Data absensi guru', [
                    "current_page" => $absen->currentPage(),
                    "last_page"    => $absen->lastPage(),
                    "data"         => $json,
                ]);
            }
            return new ArrayResource(false, 'Token anda tidak valid', null);
        } catch (\Throwable $th) {
            return new ArrayResource(false, 'Error',  $th->getMessage());
        }
    }
}
