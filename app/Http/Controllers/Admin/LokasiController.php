<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lokasi;
use Exception;

class LokasiController extends Controller
{
    /**
     * Tampilkan halaman utama (Daftar Lokasi & Map)
     */
    public function index()
    {
        // Mengambil data terbaru agar muncul paling atas di list interaktif
        $lokasi = Lokasi::latest()->get();
        return view('admin.lokasi.index', compact('lokasi'));
    }

    /**
     * Simpan data lokasi baru dari form/klik map
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'radius'      => 'required|numeric|min:5'
        ], [
            'nama_lokasi.required' => 'Nama lokasi/instansi tidak boleh kosong',
            'latitude.required'    => 'Silahkan tentukan titik koordinat pada peta',
            'radius.min'           => 'Radius minimal adalah 5 meter'
        ]);

        try {
            Lokasi::create([
                'nama_lokasi' => $request->nama_lokasi,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'radius'      => $request->radius
            ]);

            return redirect()->route('admin.lokasi.index')
                ->with('success', 'Titik lokasi berhasil didaftarkan ke sistem!');
                
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Form edit (jika diperlukan halaman terpisah)
     */
    public function edit($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        return view('admin.lokasi.edit', compact('lokasi'));
    }

    /**
     * Update data lokasi
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'radius'      => 'required|numeric'
        ]);

        try {
            $lokasi = Lokasi::findOrFail($id);
            $lokasi->update($request->all());

            return redirect()->route('admin.lokasi.index')
                ->with('success', 'Konfigurasi lokasi berhasil diperbarui');
                
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus lokasi
     */
    public function destroy($id)
    {
        try {
            $lokasi = Lokasi::findOrFail($id);
            $lokasi->delete();

            return redirect()->route('admin.lokasi.index')
                ->with('success', 'Lokasi berhasil dihapus dari daftar');
                
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menghapus data');
        }
    }
}