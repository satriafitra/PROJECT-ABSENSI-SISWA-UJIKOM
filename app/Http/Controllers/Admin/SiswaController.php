<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    // ===============================
    // INDEX
    // ===============================
    public function index(Request $request)
    {
        $search = $request->query('search');
        $major  = $request->query('major');

        $students = Student::with('class')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                });
            })
            ->when($major, function ($query, $major) {
                $query->whereHas('class', function ($q) use ($major) {
                    $q->where('name', 'LIKE', "%{$major}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.siswa.index', compact('students', 'search', 'major'));
    }


    // ===============================
    // CREATE
    // ===============================
    public function create()
    {
        $classes = Classes::all();
        return view('admin.siswa.create', compact('classes'));
    }

    // ===============================
    // STORE
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'nis'      => 'nullable|string|max:20|unique:students,nis',
            'nisn'     => 'nullable|string|max:20|unique:students,nis',
            'name'     => 'required|string|max:255',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $nis = $request->nisn ?? $request->nis;

        Student::create([
            'nis'      => $nis,
            'name'     => $request->name,
            'class_id' => $request->class_id,
            'qr_token' => Str::uuid(),
            'password' => Hash::make($nis ?? 'password123'),
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }

    // ===============================
    // EDIT
    // ===============================
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $classes = Classes::all();

        return view('admin.siswa.edit', compact('student', 'classes'));
    }

    // ===============================
    // UPDATE
    // ===============================
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'nis'      => 'nullable|string|max:20|unique:students,nis,' . $student->id,
            'nisn'     => 'nullable|string|max:20|unique:students,nis,' . $student->id,
            'name'     => 'required|string|max:255',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $student->update([
            'nis'      => $request->nisn ?? $request->nis,
            'name'     => $request->name,
            'class_id' => $request->class_id,
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil diupdate!');
    }

    // ===============================
    // DELETE
    // ===============================
    public function destroy($id)
    {
        Student::findOrFail($id)->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil dihapus!');
    }
}
