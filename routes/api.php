<?php

use App\Http\Controllers\Api\GuruController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/guru')->controller(GuruController::class)
    ->group(function () {
        Route::post('/login', 'login');
        Route::post('/auto_login', 'auto_login');
        Route::post('/get_siswa', 'get_siswa');
        Route::post('/get_kelas', 'get_kelas');
        Route::post('/get_absen_siswa', 'get_absen_siswa');
        Route::post('/add_absen_siswa', 'add_absen_siswa');
        Route::post('/add_absen_guru', 'add_absen_guru');
        Route::post('/get_absen_guru', 'get_absen_guru');
        Route::post('/update_profile', 'update_profile');
        Route::post('/update_password', 'update_password');
    });
