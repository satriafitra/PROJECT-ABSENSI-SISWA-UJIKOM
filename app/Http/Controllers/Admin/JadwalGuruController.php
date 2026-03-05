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
        $searchGuru = $request->query('guru');

        $jadwals = JadwalGuru::with('guru')
            ->when($searchGuru, function($query) use ($searchGuru) {
                $query->whereHas('guru', fn($q) => $q->where('nama', 'like', "%{$searchGuru}%"));
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(10)
            ->withQueryString();

        $gurus = Guru::orderBy('nama')->get();

        return view('admin.jadwal.index', compact('jadwals', 'gurus', 'searchGuru'));
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
            'ruangan'         => 'nullable|string'
        ]);

        JadwalGuru::create($request->all());

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal guru berhasil ditambahkan');
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
            'ruangan'         => 'nullable|string'
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal guru berhasil diperbarui');
    }

    public function destroy($id)
    {
        JadwalGuru::findOrFail($id)->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal guru berhasil dihapus');
    }
}