<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\AssessmentCategory;
use App\Models\AssessmentDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function index()
    {
        // Menggunakan whereHas agar hanya siswa yang SUDAH memiliki penilaian yang muncul
        $students = Student::has('assessments_received')
            ->with(['class', 'assessments_received.details'])
            ->get()
            ->map(function ($student) {
                $lastAssessment = $student->assessments_received->last();

                $progress = 0;
                if ($lastAssessment && $lastAssessment->details->count() > 0) {
                    $average = $lastAssessment->details->avg('score');
                    // Skala 100
                    $progress = ($average / 100) * 100;
                }

                $student->progress = $progress;
                $student->is_evaluated = true; // Karena sudah di-filter via has(), pasti true
                return $student;
            });

        return view('guru.assessment.index', compact('students'));
    }

    public function create()
    {
        // Mengambil data siswa asli
        $students = Student::with('class')->orderBy('name', 'asc')->get();

        // MENGAMBIL DATA ASLI DARI DATABASE (PENTING!)
        $categories = AssessmentCategory::where('is_active', true)->get();

        return view('guru.assessment.create', compact('students', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'evaluatee_id' => 'required|exists:students,id',
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:1|max:100', // Skala diubah ke 100
            'period' => 'required|string',
            'general_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $assessment = Assessment::create([
                'evaluator_id' => Auth::id(),
                'evaluatee_id' => $request->evaluatee_id,
                'assessment_date' => now(),
                'period' => $request->period,
                'general_notes' => $request->general_notes
            ]);

            foreach ($request->scores as $categoryId => $score) {
                AssessmentDetail::create([
                    'assessment_id' => $assessment->id,
                    'category_id' => $categoryId,
                    'score' => $score
                ]);
            }

            DB::commit();
            return redirect()->route('guru.assessment.index')
                ->with('success', 'Penilaian Berhasil Disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
