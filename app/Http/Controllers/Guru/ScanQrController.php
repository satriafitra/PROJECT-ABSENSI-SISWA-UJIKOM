<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;

class ScanQrController extends Controller
{
    /**
     * Halaman scan QR (guru login)
     */
    public function index()
    {
        $user = Auth::user();

        // pastikan role guru
        if ($user->role !== 'guru') {
            abort(403, 'Akses ditolak');
        }

        // ambil data guru dari tabel guru berdasarkan email
        $guru = Guru::where('email', $user->email)->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan');
        }

        // data QR REAL (bukan dummy)
        $qrData = json_encode([
            'id_guru' => $guru->id,
            'nama'    => $guru->nama,
            'email'   => $guru->email,
            'nip'     => $guru->nip,
            'role'    => 'guru'
        ]);

        return view('guru.scan-qr', compact('qrData', 'guru'));
    }

    /**
     * Proses absensi guru (dipanggil setelah scan)
     */
    public function absen(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'guru') {
            return response()->json([
                'status' => false,
                'message' => 'Bukan akun guru'
            ], 403);
        }

        $guru = Guru::where('email', $user->email)->first();

        if (!$guru) {
            return response()->json([
                'status' => false,
                'message' => 'Data guru tidak ditemukan'
            ], 404);
        }

        // 🔥 NANTI DI SINI:
        // simpan ke tabel absensi_guru
        // tanggal, jam, status hadir

        return response()->json([
            'status'  => true,
            'message' => 'Absensi guru berhasil',
            'guru'    => $guru->nama,
            'waktu'   => now()->format('Y-m-d H:i:s')
        ]);
    }
}
