<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $search = $request->query('search');

        $gurus = Guru::when($search, function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.guru.index', compact('gurus', 'search'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.guru.create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required',
            'email'    => 'required|email|unique:guru,email|unique:users,email',
            'password' => 'required|min:6',
            'status'   => 'required'
        ]);

        $guru = Guru::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'nip'      => $request->nip,
            'status'   => $request->status,
            'password' => Hash::make($request->password),
        ]);

        User::create([
            'name'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Guru & akun login berhasil dibuat');
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nama'   => 'required',
            'email'  => 'required|email|unique:guru,email,' . $guru->id,
            'status' => 'required',
        ]);

        $guru->update($request->only('nama', 'email', 'nip', 'status'));

        $user = User::where('email', $guru->email)->first();

        if ($user) {
            $user->update([
                'name'  => $request->nama,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }
        }

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru diperbarui');
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        Guru::findOrFail($id)->delete();

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus');
    }
}
