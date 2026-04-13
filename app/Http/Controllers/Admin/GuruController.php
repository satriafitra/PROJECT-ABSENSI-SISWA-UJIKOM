<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Digunakan untuk mengenkripsi password agar aman

class GuruController extends Controller
{
    /**
     * TAMPILAN INDEX: Menampilkan daftar semua guru
     */
    public function index(Request $request)
    {
        // 1. Mengambil kata kunci pencarian dari URL (misal: ?search=budi)
        $search = $request->query('search');

        // 2. Query ke database dengan kondisi opsional
        $gurus = Guru::when($search, function ($query) use ($search) {
                // JIKA ada input search, cari nama yang mirip (%keyword%)
                $query->where('nama', 'like', "%{$search}%");
            })
            ->orderBy('nama') // Urutkan alfabetis A-Z
            ->paginate(10)    // Batasi 10 data per halaman (Pagination)
            ->withQueryString(); // Menjaga parameter search tetap ada saat klik halaman 2, 3, dst.

        // 3. Lempar data ke view guru index
        return view('admin.guru.index', compact('gurus', 'search'));
    }

    /**
     * TAMPILAN CREATE: Hanya menampilkan form kosong
     */
    public function create()
    {
        return view('admin.guru.create');
    }

    /**
     * LOGIKA STORE: Proses penyimpanan data ke dua tabel (Guru & Users)
     */
    public function store(Request $request)
    {
        // --- TAHAP 1: VALIDASI KETAT ---
        $request->validate([
            'nama'     => 'required',
            'email'    => 'required|email|unique:guru,email|unique:users,email',
            'password' => 'required|min:6', // Minimal 6 karakter demi keamanan
            'status'   => 'required'
        ]);

        // --- TAHAP 2: SIMPAN KE TABEL GURU ---
        // Ini untuk menyimpan profil informasi kepegawaian guru
        $guru = Guru::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'nip'      => $request->nip,
            'status'   => $request->status,
            'password' => Hash::make($request->password), 
        ]);

        // --- TAHAP 3: SIMPAN KE TABEL USERS ---
        // Ini agar guru tersebut punya akun untuk login ke sistem
        User::create([
            'name'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru', // Mengunci role sebagai guru
        ]);

        // Kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.guru.index')
            ->with('success', 'Guru & akun login berhasil dibuat');
    }

    /**
     * TAMPILAN EDIT: Mengambil data lama berdasarkan ID untuk ditampilkan di form
     */
    public function edit($id)
    {
        // Cari data guru, jika ID tidak ketemu maka otomatis error 404
        $guru = Guru::findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    /**
     * LOGIKA UPDATE: Memperbarui data di tabel Guru dan User secara bersamaan
     */
    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        // --- TAHAP 1: VALIDASI ---
        $request->validate([
            'nama'   => 'required',
            // Unique kecuali untuk ID guru itu sendiri (agar tidak error saat email tidak diubah)
            'email'  => 'required|email|unique:guru,email,' . $guru->id,
            'status' => 'required',
        ]);

        // --- TAHAP 2: UPDATE PROFIL GURU ---
        // Mengambil email lama sebelum di-update untuk mencari akun User-nya nanti
        $oldEmail = $guru->email;
        $guru->update($request->only('nama', 'email', 'nip', 'status'));

        // --- TAHAP 3: SINKRONISASI KE TABEL USERS ---
        // Cari user yang email-nya sama dengan email guru (sebelum di-update)
        $user = User::where('email', $oldEmail)->first();

        if ($user) {
            // Update nama dan email di tabel login (agar sinkron)
            $user->update([
                'name'  => $request->nama,
                'email' => $request->email,
            ]);

            // Cek apakah Admin mengisi kolom password baru?
            if ($request->filled('password')) {
                // Jika diisi, maka update password akun login-nya
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }
        }

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru diperbarui');
    }

    /**
     * LOGIKA DELETE: Menghapus data guru
     */
    public function destroy($id)
    {
        // Cari data, lalu hapus permanen dari database
        Guru::findOrFail($id)->delete();

        // Catatan: Idealnya akun User-nya juga dihapus jika guru ini dihapus
        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus');
    }
}