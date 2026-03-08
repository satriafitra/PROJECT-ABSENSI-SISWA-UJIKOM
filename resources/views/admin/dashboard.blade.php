@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfd; }
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
    }
    .orange-glow {
        box-shadow: 0 10px 30px -5px rgba(249, 115, 22, 0.3);
    }
    .chart-container {
        filter: drop-shadow(0px 20px 30px rgba(0, 0, 0, 0.05));
    }
    /* Animasi masuk untuk elemen dashboard */
    .fade-up {
        animation: fadeUp 0.8s ease-out forwards;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="p-8 min-h-screen">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
        <div class="animate-in fade-in slide-in-from-left duration-700">
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                Dashboard <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-600">Overview</span>
            </h1>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Monitoring AkvaScan • Status Sistem Optimal
            </p>
        </div>
        <div class="flex items-center gap-4">
            <div class="glass-card px-5 py-3 rounded-2xl shadow-sm flex items-center gap-3">
                <div class="p-2 bg-orange-50 rounded-lg">
                    <i data-lucide="calendar" class="w-5 h-5 text-orange-500"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Tanggal</p>
                    <p class="text-sm font-bold text-slate-700">{{ date('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @php
        $stats = [
            ['label'=>'Total Siswa', 'value'=>$totalSiswa, 'icon'=>'users', 'gradient'=>'from-orange-500 to-amber-500'],
            ['label'=>'Total Guru', 'value'=>$totalGuru, 'icon'=>'graduation-cap', 'gradient'=>'from-slate-800 to-slate-900'],
            ['label'=>'Total Kelas', 'value'=>$totalKelas, 'icon'=>'door-open', 'gradient'=>'from-orange-600 to-red-600'],
            ['label'=>'Hadir Hari Ini', 'value'=>$hadir, 'icon'=>'check-circle', 'gradient'=>'from-emerald-500 to-teal-600'],
        ];
        @endphp

        @foreach ($stats as $index => $stat)
        <div class="fade-up group bg-white rounded-[2.5rem] p-7 shadow-xl shadow-slate-200/40 border border-slate-100 relative overflow-hidden transition-all duration-500 hover:-translate-y-3" style="animation-delay: {{ $index * 0.1 }}s">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-slate-50 rounded-full group-hover:bg-orange-50 transition-colors duration-500"></div>
            
            <div class="relative z-10">
                <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br {{ $stat['gradient'] }} text-white mb-6 orange-glow">
                    <i data-lucide="{{ $stat['icon'] }}" class="w-7 h-7"></i>
                </div>
                <h3 class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">{{ $stat['label'] }}</h3>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-slate-800 tracking-tighter">{{ number_format($stat['value']) }}</span>
                    <span class="text-xs font-bold text-slate-400">Jiwa</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Chart Section --}}
        <div class="lg:col-span-2 bg-white rounded-[3rem] shadow-2xl shadow-slate-200/60 border border-slate-50 p-10 relative overflow-hidden fade-up" style="animation-delay: 0.4s">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Analitik Kehadiran</h2>
                    <p class="text-slate-400 text-sm">Persentase distribusi status siswa</p>
                </div>
                <button class="p-4 bg-slate-50 rounded-2xl text-slate-400 hover:text-orange-500 transition">
                    <i data-lucide="layout-grid" class="w-5 h-5"></i>
                </button>
            </div>
            
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="relative w-64 h-64 chart-container">
                    <canvas id="absensiChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl font-black text-slate-800" id="presencePercentageDisplay">
                            0%
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Hadir</span>
                    </div>
                </div>

                <div class="flex-1 grid grid-cols-1 gap-4 w-full">
                    @php
                        $details = [
                            ['label' => 'Hadir', 'val' => $hadir, 'color' => 'bg-orange-500'],
                            ['label' => 'Izin', 'val' => $izin, 'color' => 'bg-amber-300'],
                            ['label' => 'Sakit', 'val' => $sakit, 'color' => 'bg-slate-400'],
                            ['label' => 'Alpha', 'val' => $alpha, 'color' => 'bg-slate-900'],
                        ];
                    @endphp
                    @foreach($details as $det)
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50/50 border border-slate-100 hover:bg-white hover:shadow-md transition-all duration-300">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full {{ $det['color'] }}"></span>
                            <span class="text-sm font-bold text-slate-600">{{ $det['label'] }}</span>
                        </div>
                        <span class="text-sm font-black text-slate-800">{{ number_format($det['val']) }} Siswa</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sidebar Section --}}
        <div class="flex flex-col gap-6 fade-up" style="animation-delay: 0.5s">
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden group shadow-2xl shadow-slate-900/20">
                <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500/20 rounded-full blur-3xl group-hover:bg-orange-500/40 transition duration-700"></div>
                
                <h2 class="text-xl font-bold mb-6 relative z-10">Navigasi Cepat</h2>
                <div class="space-y-3 relative z-10">
                    @php
                    $navLinks = [
                        ['name' => 'Data Siswa', 'icon' => 'users', 'route' => '#'],
                        ['name' => 'Data Guru', 'icon' => 'briefcase', 'route' => '#'],
                        ['name' => 'Rekap Absen', 'icon' => 'file-text', 'route' => '#'],
                    ];
                    @endphp
                    @foreach($navLinks as $link)
                    <a href="{{ $link['route'] }}" class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 hover:bg-orange-500 transition-all duration-300 group/item">
                        <div class="flex items-center gap-3">
                            <i data-lucide="{{ $link['icon'] }}" class="w-5 h-5 text-orange-400 group-hover/item:text-white"></i>
                            <span class="text-sm font-medium">{{ $link['name'] }}</span>
                        </div>
                        <i data-lucide="arrow-right" class="w-4 h-4 opacity-0 group-hover/item:opacity-100 translate-x-[-10px] group-hover/item:translate-x-0 transition-all"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-orange-200">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-white/20 rounded-xl">
                        <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
                    </div>
                    <h3 class="font-bold leading-tight">Data Terenkripsi</h3>
                </div>
                <p class="text-xs text-orange-50 mt-2 opacity-80 leading-relaxed">Seluruh data absensi telah diverifikasi dan diamankan oleh protokol keamanan AkvaScan.</p>
            </div>
        </div>
    </div>
</div>

{{-- Script Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        const ctx = document.getElementById('absensiChart');
        if (!ctx) return;

        // Ambil data dari PHP
        const hadir = {{ (int)$hadir }};
        const izin = {{ (int)$izin }};
        const sakit = {{ (int)$sakit }};
        const alpha = {{ (int)$alpha }};
        const total = {{ (int)$totalSiswa }};

        // Hitung persentase kehadiran dengan presisi lebih tinggi jika data sangat kecil
        let presencePercent = 0;
        if (total > 0) {
            presencePercent = (hadir / total) * 100;
            
            // Logika: Jika ada yang hadir tapi persen sangat kecil (misal 0.001), 
            // tampilkan 1 desimal agar tidak terlihat 0%
            const displayPercent = presencePercent > 0 && presencePercent < 1 
                ? presencePercent.toFixed(1) 
                : Math.round(presencePercent);
            
            document.getElementById('presencePercentageDisplay').innerText = displayPercent + '%';
        }

        const dataAbsen = [hadir, izin, sakit, alpha];
        const hasData = dataAbsen.reduce((a, b) => a + b, 0) > 0;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
                datasets: [{
                    // Jika data 0 semua, tampilkan placeholder Alpha
                    data: hasData ? dataAbsen : [0, 0, 0, 1],
                    backgroundColor: [
                        '#f97316', // Orange 500
                        '#fbbf24', // Amber 400
                        '#94a3b8', // Slate 400
                        '#0f172a'  // Slate 900
                    ],
                    borderWidth: 0,
                    hoverOffset: 15,
                    borderRadius: hasData ? 8 : 0,
                    spacing: hasData ? 5 : 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1500,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        padding: 12,
                        backgroundColor: '#1e293b',
                        titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                        cornerRadius: 10,
                        callbacks: {
                            label: function(info) {
                                return ` ${info.label}: ${info.raw} Siswa`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection