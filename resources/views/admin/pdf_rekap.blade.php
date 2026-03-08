<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        /* PDF Basic Setup */
        @page { 
            margin: 0; 
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* Dekorasi Samping (Aksen Mewah) */
        .page-accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 8px;
            height: 100%;
            background: #f97316;
        }

        .container {
            padding: 40px 50px;
        }

        /* Header Styling */
        .header {
            position: relative;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .brand-section h1 {
            margin: 0;
            font-size: 32px;
            color: #0f172a;
            font-weight: 800;
            letter-spacing: -1px;
        }
        
        .brand-section h1 span {
            color: #f97316;
        }

        .brand-section p {
            margin: 2px 0 0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #64748b;
            font-weight: 600;
        }

        .meta-section {
            position: absolute;
            right: 0;
            top: 5px;
            text-align: right;
        }

        .report-title {
            display: inline-block;
            background-color: #fff7ed;
            color: #c2410c;
            padding: 6px 15px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .meta-info {
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.6;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
        }

        th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 12px;
            border-top: 1px solid #e2e8f0;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }

        td {
            padding: 15px 12px;
            font-size: 11px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        /* Student Info Style */
        .student-name {
            font-weight: 700;
            color: #0f172a;
            font-size: 12px;
            display: block;
        }
        
        .student-nis {
            color: #94a3b8;
            font-size: 10px;
            margin-top: 2px;
        }

        .class-badge {
            color: #f97316;
            font-weight: 700;
        }

        /* Time Style */
        .time-box {
            font-family: 'Courier', monospace;
            font-weight: 700;
            color: #1e293b;
        }

        /* Status Badges (Premium Look) */
        .badge {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 9px;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
        }
        .hadir { background-color: #ecfdf5; color: #059669; }
        .izin { background-color: #eff6ff; color: #2563eb; }
        .sakit { background-color: #fffbeb; color: #d97706; }
        .alpha { background-color: #fef2f2; color: #dc2626; }

        /* Signature Section */
        .footer-sign {
            margin-top: 60px;
            width: 100%;
        }

        .sign-box {
            float: right;
            width: 220px;
            text-align: center;
        }

        .sign-date {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 15px;
        }

        .sign-title {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 60px;
        }

        .sign-name {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            border-bottom: 1px solid #0f172a;
            display: inline-block;
            padding-bottom: 2px;
        }

        /* Note Section */
        .document-footer {
            position: absolute;
            bottom: 40px;
            left: 50px;
            right: 50px;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
            font-size: 9px;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="page-accent"></div>

    <div class="container">
        <div class="header">
            <div class="brand-section">
                <h1>Akva<span>Scan</span></h1>
                <p>Digital Attendance System</p>
            </div>
            
            <div class="meta-section">
                <div class="report-title">LAPORAN KEHADIRAN RESMI</div>
                <div class="meta-info">
                    Tanggal Laporan: <strong>{{ $date }}</strong><br>
                    Waktu Cetak: {{ now()->format('d M Y, H:i') }}
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%" style="text-align: center;">No</th>
                    <th width="35%">Informasi Siswa</th>
                    <th width="15%">Kelas</th>
                    <th width="15%">Waktu</th>
                    <th width="12%" style="text-align: center;">Status</th>
                    <th width="18%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $index => $item)
                <tr>
                    <td align="center" style="color: #94a3b8; font-weight: bold;">{{ $index + 1 }}</td>
                    <td>
                        <span class="student-name">{{ $item->student->name ?? 'N/A' }}</span>
                        <span class="student-nis">NIS: {{ $item->student->nis ?? '-' }}</span>
                    </td>
                    <td><span class="class-badge">{{ $item->student->class->name ?? '-' }}</span></td>
                    <td class="time-box">{{ $item->check_in ?? '--:--' }}</td>
                    <td align="center">
                        <span class="badge {{ strtolower($item->status) }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td style="color: #64748b;">{{ $item->notes ?? $item->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" align="center" style="padding: 40px; color: #94a3b8;">
                        Tidak ada data absensi ditemukan untuk tanggal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer-sign">
            <div class="sign-box">
                <div class="sign-date">Dicetak pada: {{ now()->translatedFormat('d F Y') }}</div>
                <div class="sign-title">Petugas Administrasi,</div>
                <div class="sign-name">__________________________</div>
                <div style="font-size: 10px; color: #64748b; margin-top: 5px;">Sistem AkvaScan Terverifikasi</div>
            </div>
        </div>

        <div class="document-footer">
            * Laporan ini dihasilkan secara otomatis oleh infrastruktur digital AkvaScan. Data yang tercantum bersifat sah dan merupakan rekam medis kehadiran siswa pada tanggal yang ditentukan.
        </div>
    </div>
</body>
</html>