<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalGuru;

class JadwalGuruApiController extends Controller
{
    // List semua jadwal / filter by guru_id & hari
    public function index(Request $request)
    {
        $query = JadwalGuru::with('guru')->orderBy('hari')->orderBy('jam_mulai');

        if ($request->has('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }
        if ($request->has('hari')) {
            $query->where('hari', $request->hari);
        }

        return response()->json([
            'status' => true,
            'data' => $query->get()
        ]);
    }

    // Tambah jadwal
    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'hari' => 'required',
            'mata_pelajaran' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruangan' => 'nullable|string'
        ]);

        $jadwal = JadwalGuru::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Jadwal guru berhasil ditambahkan',
            'data' => $jadwal
        ]);
    }

    // Update jadwal
    public function update(Request $request, $id)
    {
        $jadwal = JadwalGuru::findOrFail($id);

        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'hari' => 'required',
            'mata_pelajaran' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruangan' => 'nullable|string'
        ]);

        $jadwal->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Jadwal guru berhasil diperbarui',
            'data' => $jadwal
        ]);
    }

    // Hapus jadwal
    public function destroy($id)
    {
        JadwalGuru::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Jadwal guru berhasil dihapus'
        ]);
    }
}