<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Assessment;
use App\Models\AssessmentCategory;
use App\Models\AssessmentDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    /**
     * Tampilan Utama: Daftar Siswa (Dashboard Penilai)
     */
    public function index()
    {
        // Mengambil siswa (role 'siswa') dan cek apakah sudah dinilai
        $students = User::where('role', 'siswa')
            ->withExists(['assessments_received as is_evaluated'])
            ->get();

        return view('guru.assessment.index', compact('students'));
    }

    /**
     * Form Input Penilaian Baru (Tombol Tambah Penilaian Baru)
     */
    public function create()
    {
        // Ambil semua siswa untuk dropdown
        $students = User::where('role', 'siswa')->get();
        
        // Ambil kategori penilaian yang aktif
        $categories = AssessmentCategory::where('is_active', true)->get();

        // Mengirim data ke view create
        return view('guru.assessment.create', compact('students', 'categories'));
    }

    /**
     * Menampilkan Form Penilaian Spesifik (Tombol Aksi di Tabel)
     */
    public function show($id)
    {
        // Mencari siswa spesifik yang diklik
        $student = User::findOrFail($id);
        
        // Ambil kategori
        $categories = AssessmentCategory::where('is_active', true)->get();
        
        // Ambil semua siswa (jika di form ingin ganti siswa lain)
        $students = User::where('role', 'siswa')->get();

        // Kita kirim variabel 'student' sebagai siswa yang terpilih otomatis
        return view('guru.assessment.create', [
            'selectedStudent' => $student,
            'students' => $students,
            'categories' => $categories
        ]);
    }

    /**
     * Simpan Transaksi Penilaian
     */
    public function store(Request $request)
    {
        $request->validate([
            'evaluatee_id' => 'required|exists:users,id',
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:1|max:5',
            'period' => 'required|string',
            'general_notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Simpan Header Penilaian
            $assessment = Assessment::create([
                'evaluator_id' => Auth::id(),
                'evaluatee_id' => $request->evaluatee_id,
                'assessment_date' => now(),
                'period' => $request->period,
                'general_notes' => $request->general_notes
            ]);

            // Simpan Detail per Kategori (Skala Likert/Poin)
            foreach ($request->scores as $categoryId => $score) {
                AssessmentDetail::create([
                    'assessment_id' => $assessment->id,
                    'category_id' => $categoryId,
                    'score' => $score
                ]);
            }

            DB::commit();
            return redirect()->route('guru.assessment.index')->with('success', 'Penilaian siswa berhasil disimpan!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Form Edit Penilaian (Jika ingin mengubah yang sudah ada)
     */
    public function edit($id)
    {
        // Mencari data penilaian berdasarkan ID Assessment-nya
        $assessment = Assessment::with(['details.category', 'evaluatee'])
            ->findOrFail($id);

        return view('guru.assessment.edit', compact('assessment'));
    }

    /**
     * Update Penilaian
     */
    public function update(Request $request, $id)
    {
        $assessment = Assessment::findOrFail($id);

        DB::beginTransaction();
        try {
            $assessment->update([
                'general_notes' => $request->general_notes,
                'assessment_date' => now(),
                'period' => $request->period,
            ]);

            // Update skor di tabel detail
            foreach ($request->scores as $detailId => $score) {
                AssessmentDetail::where('id', $detailId)->update(['score' => $score]);
            }

            DB::commit();
            return redirect()->route('guru.assessment.index')->with('success', 'Penilaian berhasil diperbarui!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
}