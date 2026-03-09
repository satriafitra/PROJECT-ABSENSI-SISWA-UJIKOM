<?php

use App\Models\Student;
use App\Models\Guru;
use App\Models\JadwalGuru;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // <-- import Request
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Guru\ScanQrController;
use App\Http\Controllers\Api\JadwalGuruApiController;

// =====================
// API SISWA
// =====================
Route::get('/siswa', function () {
    $students = Student::with('class')->get();

    return response()->json([
        'status' => 'success',
        'data' => $students
    ]);
});

Route::post('/login-siswa', [AuthController::class, 'loginSiswa']);

// =====================
// API GURU
// =====================
Route::get('/guru', function () {
    $gurus = Guru::orderBy('nama')->get();

    return response()->json([
        'status' => 'success',
        'total'  => $gurus->count(),
        'data'   => $gurus
    ]);
});

Route::post('/attendance', [AttendanceController::class, 'store']);
Route::get('/attendance/{student_id}', [AttendanceController::class, 'history']);

Route::post('/absen', [ScanQrController::class, 'absen']);
Route::post('/absen-manual', [AttendanceController::class, 'storeManual']);


// =====================
// API JADWAL GURU
// =====================

// List semua jadwal guru / filter by guru_id & hari

Route::get('/jadwal-guru', [JadwalGuruApiController::class, 'index']);

// Tambah jadwal guru baru
Route::post('/jadwal-guru', [JadwalGuruApiController::class, 'store']);

// Update jadwal guru
Route::put('/jadwal-guru/{id}', [JadwalGuruApiController::class, 'update']);

// Hapus jadwal guru
Route::delete('/jadwal-guru/{id}', [JadwalGuruApiController::class, 'destroy']);

// =====================
// Debug QR Token Guru
// =====================
Route::get('/debug/guru-token', function () {
    return response()->json([
        'status' => true,
        'data' => Guru::select('id', 'nama', 'qr_token')->get()
    ]);
});