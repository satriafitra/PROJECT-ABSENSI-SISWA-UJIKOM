<?php

use App\Models\Student;
use Illuminate\Support\Facades\Route;

Route::get('/siswa', function () {
    // ambil semua siswa dengan relasi kelas
    $students = Student::with('class')->get();

    return response()->json([
        'status' => 'success',
        'data' => $students
    ]);
});
