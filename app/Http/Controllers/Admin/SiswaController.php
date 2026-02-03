<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    // ===============================
    // INDEX
    // ===============================
    public function index(Request $request)
    {
        $search = $request->query('search');

        $students = Student::with('class')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        if ($request->query('json') == 1) {
            return response()->json([
                'status' => 'success',
                'current_page' => $students->currentPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total(),
                'last_page' => $students->lastPage(),
                'data' => $students->items(),
            ]);
        }

        return view('admin.siswa.index', compact('students', 'search'));
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
            // boleh kirim nis ATAU nisn
            'nis'     => 'nullable|string|max:20|unique:students,nis',
            'nisn'    => 'nullable|string|max:20|unique:students,nis',
            'name'    => 'required|string|max:255',
            'class_id'=> 'nullable|exists:classes,id',
        ]);

        Student::create([
            // PRIORITAS: nisn → nis → null
            'nis' => $request->nisn ?? $request->nis,
            'name' => $request->name,
            'class_id' => $request->class_id,
            'qr_token' => Str::uuid(),
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
            'nis'     => 'nullable|string|max:20|unique:students,nis,' . $student->id,
            'nisn'    => 'nullable|string|max:20|unique:students,nis,' . $student->id,
            'name'    => 'required|string|max:255',
            'class_id'=> 'nullable|exists:classes,id',
        ]);

        $student->update([
            'nis' => $request->nisn ?? $request->nis,
            'name' => $request->name,
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
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Siswa berhasil dihapus!');
    }
}
