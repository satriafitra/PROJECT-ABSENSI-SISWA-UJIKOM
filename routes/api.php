<?php

use App\Models\Student;
use App\Models\Guru;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Guru\ScanQrController;
use App\Http\Controllers\Api\ScheduleController;



Route::get('/siswa', function () {
    // ambil semua siswa dengan relasi kelas
    $students = Student::with('class')->get();

    return response()->json([
        'status' => 'success',
        'data' => $students
    ]);
});

Route::post('/login-siswa', [AuthController::class, 'loginSiswa']);

// =====================
// API GURU (CEK HASIL FETCH)
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

Route::get('/schedule/class/{class_id}', [ScheduleController::class, 'byClass']);
Route::get('/schedule/today/{class_id}', [ScheduleController::class, 'todayByClass']);

Route::get('/debug/guru-token', function () {
    return response()->json([
        'status' => true,
        'data' => \App\Models\Guru::select('id', 'nama', 'qr_token')->get()
    ]);
});
