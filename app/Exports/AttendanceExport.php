<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Mengambil data beserta relasinya agar tidak lambat (Eager Loading)
        return Attendance::with(['student.class', 'guru'])->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Siswa', 'Kelas', 'Guru/Pengawas', 'Waktu', 'Status', 'Keterangan'];
    }

    public function map($attendance): array
    {
        static $no = 1;
        return [
            $no++,
            $attendance->student->name ?? '-',
            $attendance->student->class->name ?? '-',
            $attendance->guru->nama ?? 'Sistem',
            $attendance->check_in,
            $attendance->status,
            $attendance->notes ?? '-',
        ];
    }
}