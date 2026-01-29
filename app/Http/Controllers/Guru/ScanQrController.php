<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScanQrController extends Controller
{
    public function index()
    {
        // contoh data siswa (nanti bisa dari DB)
        $qrData = json_encode([
            'nis'  => '123456',
            'nama' => 'Budi Santoso',
            'kelas'=> '6A'
        ]);

        return view('guru.scan-qr', compact('qrData'));
    }

    public function export()
    {
        return response()->json([
            'message' => 'Export absensi berhasil (dummy)'
        ]);
    }
}
