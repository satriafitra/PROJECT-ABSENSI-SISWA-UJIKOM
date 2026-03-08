<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function rekapAbsensi(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $search = $request->input('search');

        $attendances = Attendance::with(['student.class', 'guru'])
            ->whereDate('date', $date)
            ->when($search, function($query) use ($search) {
                $query->whereHas('student', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); 

        return view('admin.rekapabsensi', compact('attendances', 'date'));
    }

    public function exportExcel(Request $request)
    {
        // Tetap gunakan filter yang sama agar data yang didownload sinkron
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        return Excel::download(new AttendanceExport($date), "Rekap-Absensi-{$date}.xlsx");
    }

    public function exportPdf(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $search = $request->input('search');

        // Ambil data tanpa pagination untuk PDF
        $attendances = Attendance::with(['student.class', 'guru'])
            ->whereDate('date', $date)
            ->when($search, function($query) use ($search) {
                $query->whereHas('student', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->get();

        $pdf = Pdf::loadView('admin.pdf_rekap', [
            'attendances' => $attendances,
            'date' => Carbon::parse($date)->translatedFormat('d F Y'),
            'title' => 'LAPORAN REKAP ABSENSI SISWA'
        ]);

        return $pdf->setPaper('a4', 'portrait')->download("Rekap-Absensi-{$date}.pdf");
    }
}