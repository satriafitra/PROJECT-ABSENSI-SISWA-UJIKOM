<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lokasi;

class LokasiController extends Controller
{
    // =========================
    // TAMPILKAN DATA LOKASI
    // =========================
    public function index()
    {
        $lokasi = Lokasi::latest()->get();
        return view('admin.lokasi.index', compact('lokasi'));
    }

    // =========================
    // FORM TAMBAH LOKASI
    // =========================
    public function create()
    {
        return view('admin.lokasi.create');
    }

    // =========================
    // SIMPAN LOKASI
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required|numeric'
        ]);

        Lokasi::create([
            'nama_lokasi' => $request->nama_lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius
        ]);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan');
    }

    // =========================
    // FORM EDIT
    // =========================
    public function edit($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        return view('admin.lokasi.edit', compact('lokasi'));
    }

    // =========================
    // UPDATE LOKASI
    // =========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lokasi' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required|numeric'
        ]);

        $lokasi = Lokasi::findOrFail($id);

        $lokasi->update([
            'nama_lokasi' => $request->nama_lokasi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius
        ]);

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil diperbarui');
    }

    // =========================
    // HAPUS LOKASI
    // =========================
    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus');
    }
}