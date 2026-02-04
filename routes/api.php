<?php

use App\Models\Student;
use App\Models\Guru;
use Illuminate\Support\Facades\Route;

Route::get('/siswa', function () {
    // ambil semua siswa dengan relasi kelas
    $students = Student::with('class')->get();

    return response()->json([
        'status' => 'success',
        'data' => $students
    ]);
});

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
