<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointRule;   // Tabel point_rules
use App\Models\PointLedger; // Tabel point_ledgers
use App\Models\Siswa;       // Model Siswa kamu
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointManagerController extends Controller
{
    public function index()
    {
        // Sesuaikan 'user_id' menjadi 'student_id' sesuai migration kamu
        $leaderboard = PointLedger::with('student')
            ->select('student_id', DB::raw('SUM(amount) as total_points'))
            ->groupBy('student_id')
            ->orderBy('total_points', 'desc')
            ->take(10)
            ->get();

        $rules = PointRule::latest()->get();

        return view('admin.gamifikasi.index', compact('leaderboard', 'rules'));
    }

    // Method lainnya (storeRule, leaderboard) juga harus menggunakan student_id
    public function leaderboard()
    {
        $leaderboard = PointLedger::with('student')
            ->select('student_id', DB::raw('SUM(amount) as total_points'))
            ->groupBy('student_id')
            ->orderBy('total_points', 'desc')
            ->get();

        return view('admin.gamifikasi.leaderboard', compact('leaderboard'));
    }

    public function storeRule(Request $request)
    {
        $request->validate([
            'rule_name' => 'required|string|max:255',
            'condition_operator' => 'required|in:<,>,BETWEEN',
            'condition_value' => 'required',
            'point_modifier' => 'required|integer',
        ]);

        PointRule::create([
            'rule_name' => $request->rule_name,
            'target_role' => 'Siswa', // Default sesuai skema gambar
            'condition_operator' => $request->condition_operator,
            'condition_value' => $request->condition_value,
            'point_modifier' => $request->point_modifier,
        ]);

        return back()->with('success', 'Aturan gamifikasi berhasil disimpan!');
    }
}
