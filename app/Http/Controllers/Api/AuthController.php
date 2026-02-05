<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * LOGIN SISWA
     * NISN + PASSWORD
     */
    public function loginSiswa(Request $request)
    {
        // ✅ VALIDASI
        $request->validate([
            'nisn'     => 'required|string',
            'password' => 'required|string',
        ]);

        // 🔍 CARI SISWA BERDASARKAN NISN
        $student = Student::with('class')
            ->where('nis', $request->nisn)
            ->first();

        // ❌ JIKA TIDAK ADA SISWA
        if (!$student) {
            return response()->json([
                'status'  => 'error',
                'message' => 'NISN tidak terdaftar',
            ], 401);
        }

        // ❌ CEK PASSWORD
        if (!Hash::check($request->password, $student->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Password salah',
            ], 401);
        }

        // ✅ LOGIN BERHASIL
        return response()->json([
            'status'  => 'success',
            'message' => 'Login berhasil',
            'data'    => [
                'id'        => $student->id,
                'nisn'      => $student->nis,
                'name'      => $student->name,
                'class_id'  => $student->class_id,
                'class'     => $student->class?->name,
                'qr_token'  => $student->qr_token,
            ],
        ], 200);
    }
}
