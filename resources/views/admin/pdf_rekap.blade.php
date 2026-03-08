<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        /* PDF Styling */
        @page { margin: 0.5cm; }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            line-height: 1.5;
        }

        /* Header Mewah */
        .header {
            position: relative;
            border-bottom: 3px solid #f97316;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0;
            color: #f97316;
            font-weight: bold;
            font-size: 14px;
        }
        .info-meta {
            position: absolute;
            right: 0;
            top: 10px;
            text-align: right;
            font-size: 11px;
            color: #64748b;
        }

        /* Tabel Design */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
        }
        th {
            background-color: #0f172a;
            color: #ffffff;
            padding: 12px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            border: none;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            color: #334155;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Badge Status */
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .hadir { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .izin { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .sakit { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .alpha { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        /* Typography Helper */
        .text-bold { font-weight: bold; color: #0f172a; }
        .text-muted { color: #64748b; font-size: 10px; }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #94a3b8;
        }
        .signature-space {
            margin-top: 50px;
            width: 200px;
            float: right;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #1e293b;
            padding-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>AkvaScan</h1>
        <p>Digital Student Attendance Report</p>
        
        <div class="info-meta">
            Laporan Tanggal: <span class="text-bold">{{ $date }}</span><br>
            Dicetak: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Siswa</th>
                <th width="15%">Kelas</th>
                <th width="15%">Waktu Masuk</th>
                <th width="10%">Status</th>
                <th width="25%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $item)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td>
                    <div class="text-bold">{{ $item->student->name ?? 'N/A' }}</div>
                    <div class="text-muted">ID: {{ $item->student->nis ?? '-' }}</div>
                </td>
                <td>{{ $item->student->class->name ?? '-' }}</td>
                <td align="center" style="font-family: 'Courier New', Courier, monospace;">
                    {{ $item->check_in ?? '--:--' }}
                </td>
                <td align="center">
                    <span class="badge {{ strtolower($item->status) }}">
                        {{ $item->status }}
                    </span>
                </td>
                <td>{{ $item->notes ?? $item->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" align="center" style="padding: 20px;">Tidak ada data absensi untuk tanggal ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-space">
        <p>Mengetahui,</p>
        <p style="margin-top: 5px;">Kepala Sekolah / Admin</p>
        <div class="signature-line">
            ( .................................... )
        </div>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        * Dokumen ini dihasilkan secara otomatis oleh Sistem AkvaScan dan merupakan bukti kehadiran resmi.
    </div>

</body>
</html>