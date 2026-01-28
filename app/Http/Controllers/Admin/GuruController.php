<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $gurus = Guru::orderBy('nama')->get();
        return view('admin.guru.index', compact('gurus'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'nip'    => 'nullable|unique:guru,nip',
            'email'  => 'nullable|email|unique:guru,email',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Guru::create([
            'nama'   => $request->nama,
            'nip'    => $request->nip,
            'email'  => $request->email,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan');
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => [
                'nullable',
                Rule::unique('guru', 'nip')->ignore($id),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('guru', 'email')->ignore($id),
            ],
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $guru = Guru::findOrFail($id);
        $guru->update($request->only([
            'nama',
            'nip',
            'email',
            'status'
        ]));

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui');
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
