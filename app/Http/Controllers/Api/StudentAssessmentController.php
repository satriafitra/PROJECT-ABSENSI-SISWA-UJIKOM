<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment; // Sesuaikan nama model penilaianmu
use Illuminate\Http\Request;

class StudentAssessmentController extends Controller
{
    public function getLatest($id)
    {
        try {
            // Mengambil penilaian terbaru berdasarkan student_id (evaluatee_id)
            // Sertakan relasi 'details.category' agar data di Flutter lengkap
            $assessment = Assessment::with(['details.category'])
                ->where('evaluatee_id', $id) 
                ->latest()
                ->first();

            if (!$assessment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Belum ada data penilaian untuk siswa ini.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $assessment
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}