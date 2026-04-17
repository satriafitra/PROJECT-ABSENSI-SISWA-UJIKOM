<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalGuru;
use App\Models\Guru;

class JadwalGuruController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $guruId = $request->query('guru_id');
        $hari = $request->query('hari');

        $jadwals = JadwalGuru::with('guru')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('guru', fn($sq) => $sq->where('nama', 'like', "%{$search}%"))
                        ->orWhere('mata_pelajaran', 'like', "%{$search}%");
                });
            })
            ->when($guruId, function ($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->when($hari, function ($query) use ($hari) {
                $query->where('hari', $hari);
            })
            // Mengurutkan berdasarkan urutan hari sekolah, bukan abjad
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->paginate(10)
            ->withQueryString();

        $gurus = Guru::orderBy('nama')->get();
        $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('admin.jadwal.index', compact('jadwals', 'gurus', 'daftarHari'));
    }

    public function create()
    {
        $gurus = Guru::orderBy('nama')->get();
        return view('admin.jadwal.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id'         => 'required|exists:guru,id',
            'hari'            => 'required',
            'mata_pelajaran'  => 'required',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required|after:jam_mulai',
            'batas_telat'     => 'required|integer|min:1',
            'ruangan'         => 'nullable|string'
        ]);

        JadwalGuru::create($request->all());

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal guru berhasil ditambahkan secara aman!');
    }

    public function edit($id)
    {
        $jadwal = JadwalGuru::findOrFail($id);
        $gurus  = Guru::orderBy('nama')->get();
        return view('admin.jadwal.edit', compact('jadwal', 'gurus'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalGuru::findOrFail($id);

        $request->validate([
            'guru_id'         => 'required|exists:guru,id',
            'hari'            => 'required',
            'mata_pelajaran'  => 'required',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required|after:jam_mulai',
            'batas_telat'     => 'required|integer|min:1',
            'ruangan'         => 'nullable|string'
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Perubahan jadwal telah berhasil disimpan');
    }

    public function destroy($id)
    {
        JadwalGuru::findOrFail($id)->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Data jadwal telah dihapus dari sistem');
    }
}
