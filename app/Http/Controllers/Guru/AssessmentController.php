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
        // Menampilkan siswa yang sudah memiliki penilaian
        $students = Student::has('assessments_received')
            ->with(['class', 'assessments_received.details'])
            ->get()
            ->map(function ($student) {
                $lastAssessment = $student->assessments_received->last();

                $progress = 0;
                if ($lastAssessment && $lastAssessment->details->count() > 0) {
                    $progress = $lastAssessment->details->avg('score');
                }

                $student->progress = $progress;
                $student->is_evaluated = true;
                return $student;
            });

        return view('guru.assessment.index', compact('students'));
    }

    public function create()
    {
        $students = Student::with('class')->orderBy('name', 'asc')->get();
        $categories = AssessmentCategory::where('is_active', true)->get();
        return view('guru.assessment.create', compact('students', 'categories'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'evaluatee_id' => 'required|exists:students,id',
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:0|max:100', // Slider mulai dari 0
            'period' => 'required|string',
            'general_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Header Assessment
            $assessment = Assessment::create([
                'evaluator_id' => Auth::id(),
                'evaluatee_id' => $request->evaluatee_id,
                'assessment_date' => now(),
                'period' => $request->period,
                'general_notes' => $request->general_notes
            ]);

            // 2. Logika Pengelompokan Skor Pertanyaan ke Kategori
            $categoryScores = [];

            foreach ($request->scores as $questionId => $score) {
                // Ambil category_id dari tabel questions (sesuaikan nama tabel Anda)
                $question = DB::table('assessment_questions')->where('id', $questionId)->first();

                if ($question) {
                    // Kelompokkan nilai berdasarkan kategori
                    $categoryScores[$question->category_id][] = $score;
                }
            }

            // 3. Hitung Rata-rata & Simpan ke AssessmentDetail
            foreach ($categoryScores as $categoryId => $scoresArray) {
                $average = count($scoresArray) > 0 ? array_sum($scoresArray) / count($scoresArray) : 0;

                AssessmentDetail::create([
                    'assessment_id' => $assessment->id,
                    'category_id' => $categoryId,
                    'score' => round($average) // Simpan nilai bulat
                ]);
            }

            DB::commit();
            return redirect()->route('guru.assessment.index')
                ->with('success', 'Penilaian Berhasil Disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $student = Student::with(['class', 'assessments_received' => function ($query) {
            $query->latest()->with('details.category');
        }])->findOrFail($id);

        $assessment = $student->assessments_received->first();

        if (!$assessment) {
            return redirect()->route('guru.assessment.index')->with('error', 'Belum ada data penilaian untuk siswa ini.');
        }

        return view('guru.assessment.show', compact('student', 'assessment'));
    }

    public function edit($id)
    {
        // Menggunakan relasi 'evaluatee' sesuai Model Assessment
        $assessment = Assessment::with(['details.category', 'evaluatee.class'])->findOrFail($id);
        $student = $assessment->evaluatee;

        return view('guru.assessment.edit', compact('assessment', 'student'));
    }

    /**
     * FUNGSI UPDATE DATA PENILAIAN
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:1|max:100',
            'period' => 'required|string',
            'general_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $assessment = Assessment::findOrFail($id);

            // 1. Update Tabel Utama (Assessment)
            $assessment->update([
                'period' => $request->period,
                'general_notes' => $request->general_notes,
                'assessment_date' => now(),
            ]);

            // 2. Update Tabel Detail (Skor per Kategori)
            // $detailId adalah ID dari tabel assessment_details
            foreach ($request->scores as $detailId => $score) {
                AssessmentDetail::where('id', $detailId)
                    ->where('assessment_id', $assessment->id)
                    ->update(['score' => $score]);
            }

            DB::commit();
            return redirect()->route('guru.assessment.index')
                ->with('success', 'Data Penilaian Berhasil Diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }
}
