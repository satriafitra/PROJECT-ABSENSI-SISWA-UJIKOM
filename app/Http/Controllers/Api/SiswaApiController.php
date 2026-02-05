<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;

class SiswaApiController extends Controller
{
    /**
     * GET semua data siswa
     */
    public function index()
    {
        $students = Student::with('class')->get();

        return response()->json([
            'status' => 'success',
            'total'  => $students->count(),
            'data'   => $students,
        ]);
    }
}
