<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Guru;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'guru') {
            abort(403);
        }

        $guru = Guru::where('email', $user->email)->firstOrFail();

        $date = $request->query('date');

        $attendances = Attendance::with(['student.class'])
            ->where('guru_id', $guru->id) // 🔥 HANYA DATA GURU INI
            ->when($date, function ($q, $date) {
                $q->where('date', $date);
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('guru.rekap-absensi', compact('attendances', 'date'));
    }
}
