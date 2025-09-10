<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('export/export-absen-siswa', [ExportController::class, 'export_absen_siswa'])
    ->name('export.absen-siswa');
Route::get('export/export-absen-guru', [ExportController::class, 'export_absen_guru'])
    ->name('export.absen-guru');
Route::get('export/export-nilai', [ExportController::class, 'export_nilai'])
    ->name('export.nilai');
Route::get('export/export-jadwal', [ExportController::class, 'export_jadwal'])
    ->name('export.jadwal');
Route::get('export/export-siswa', [ExportController::class, 'export_siswa'])
    ->name('export.siswa');
