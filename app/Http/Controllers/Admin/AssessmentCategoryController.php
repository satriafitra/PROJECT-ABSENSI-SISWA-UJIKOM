<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssessmentCategory;
use App\Models\AssessmentQuestion; // Pastikan ini di-import

class AssessmentCategoryController extends Controller
{
    public function index()
    {
        // Mengambil kategori beserta jumlah pertanyaannya agar admin tahu mana yang masih kosong
        $categories = AssessmentCategory::withCount('questions')->orderBy('name', 'asc')->get();
        return view('admin.assessment.category', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:assessment_categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        AssessmentCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('admin.assessment-category.index')
            ->with('success', 'Kategori Penilaian Berhasil Ditambahkan!');
    }

    public function manageQuestions($id)
    {
        // Menampilkan halaman khusus input pertanyaan asli
        $category = AssessmentCategory::with('questions')->findOrFail($id);
        return view('admin.assessment.manage_questions', compact('category'));
    }

    public function storeQuestion(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required|string|max:255'
        ]);

        $category = AssessmentCategory::findOrFail($id);
        $category->questions()->create([
            'question_text' => $request->question_text
        ]);

        return back()->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    public function destroyQuestion($id)
    {
        $question = AssessmentQuestion::findOrFail($id);
        $question->delete();
        return back()->with('success', 'Pertanyaan berhasil dihapus!');
    }

    public function destroy($id)
    {
        try {
            $category = AssessmentCategory::findOrFail($id);
            if ($category->questions()->count() > 0) {
                return back()->with('error', 'Hapus semua pertanyaan di dalam kategori ini terlebih dahulu.');
            }
            $category->delete();
            return redirect()->route('admin.assessment-category.index')->with('success', 'Kategori Berhasil Dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}