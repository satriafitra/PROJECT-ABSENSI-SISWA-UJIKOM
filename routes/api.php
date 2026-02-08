<?php

use App\Models\Student;
use App\Models\Guru;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AttendanceController;


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
