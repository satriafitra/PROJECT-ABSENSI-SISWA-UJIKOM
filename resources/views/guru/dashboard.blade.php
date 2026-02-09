@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<div class="p-8 min-h-screen bg-[#f8fafc] font-['Poppins']">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">
                Panel <span class="text-orange-500">Guru</span>
            </h1>
            <p class="text-slate-500 mt-1">Kelola absensi siswa dengan cepat dan efisien.</p>
        </div>
        <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                <i data-lucide="calendar-check-2" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Hari Ini</p>
                <p class="text-sm font-bold text-slate-700">{{ date('l, d M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        
        <a href="#" class="group relative overflow-hidden bg-gradient-to-br from-orange-500 to-amber-600 p-8 rounded-[2rem] shadow-xl shadow-orange-200 transition-all duration-500 hover:-translate-y-2">
            <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-colors"></div>
            
            <div class="relative z-10 flex items-start justify-between">
                <div class="space-y-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white border border-white/30">
                        <i data-lucide="qr-code" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white uppercase tracking-wide">Scan QR Absensi</h2>
                        <p class="text-orange-50 text-sm font-light">Mulai sesi absensi otomatis sekarang.</p>
                    </div>
                </div>
                <div class="text-white/50 group-hover:text-white transition-colors">
                    <i data-lucide="arrow-up-right" class="w-8 h-8"></i>
                </div>
            </div>
        </a>

        <a href="#" class="group relative bg-white p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 transition-all duration-500 hover:-translate-y-2">
            <div class="flex items-start justify-between relative z-10">
                <div class="space-y-4">
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 transition-colors group-hover:bg-orange-500 group-hover:text-white">
                        <i data-lucide="book-open-check" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 uppercase tracking-wide">Absensi Hari Ini</h2>
                        <p class="text-slate-400 text-sm font-light">Lihat rekapitulasi data kehadiran siswa.</p>
                    </div>
                </div>
                <div class="text-slate-200 group-hover:text-orange-500 transition-colors">
                    <i data-lucide="bar-chart-horizontal" class="w-8 h-8"></i>
                </div>
            </div>
        </a>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 flex flex-col items-center">
            <div class="w-full flex items-center justify-between mb-8">
                <h3 class="font-bold text-slate-800">Status Kehadiran</h3>
                <i data-lucide="pie-chart" class="text-slate-300 w-5 h-5"></i>
            </div>
            
            <div class="relative w-full max-w-[240px] aspect-square">
                <canvas id="absensiChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-3xl font-black text-slate-800">120</span>
                    <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Total Siswa</span>
                </div>
            </div>

            <div class="mt-8 w-full grid grid-cols-3 gap-2">
                <div class="text-center p-3 rounded-2xl bg-orange-50">
                    <p class="text-[10px] font-bold text-orange-400 uppercase">Hadir</p>
                    <p class="font-bold text-orange-700">105</p>
                </div>
                <div class="text-center p-3 rounded-2xl bg-amber-50">
                    <p class="text-[10px] font-bold text-amber-500 uppercase">Izin</p>
                    <p class="font-bold text-amber-700">10</p>
                </div>
                <div class="text-center p-3 rounded-2xl bg-slate-50">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Alpha</p>
                    <p class="font-bold text-slate-700">5</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 flex flex-col">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="font-bold text-slate-800">Laporan Mingguan</h3>
                    <p class="text-xs text-slate-400">Statistik kehadiran 5 hari terakhir</p>
                </div>
                <div class="flex gap-2">
                     <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span> Hadir
                     </span>
                </div>
            </div>

            <div class="flex-1 min-h-[300px]">
                <canvas id="mingguanChart"></canvas>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();

    const absensiCtx = document.getElementById('absensiChart').getContext('2d');
    const mingguanCtx = document.getElementById('mingguanChart').getContext('2d');

    // Doughnut Chart
    new Chart(absensiCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Izin', 'Alpha'],
            datasets: [{
                data: [105, 10, 5],
                backgroundColor: ['#f97316', '#fbbf24', '#e2e8f0'],
                borderWidth: 6,
                borderColor: '#ffffff',
                hoverOffset: 15
            }]
        },
        options: {
            cutout: '80%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Create Gradient for Bar Chart
    const gradient = mingguanCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(249, 115, 22, 1)');
    gradient.addColorStop(1, 'rgba(251, 146, 60, 0.3)');

    // Bar Chart
    new Chart(mingguanCtx, {
        type: 'bar',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            datasets: [{
                label: 'Siswa Hadir',
                data: [100, 102, 98, 105, 103],
                backgroundColor: gradient,
                hoverBackgroundColor: '#ea580c',
                borderRadius: 12,
                borderSkipped: false,
                barThickness: 35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    bodyFont: { family: 'Poppins' },
                    cornerRadius: 10
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { family: 'Poppins', size: 11 }, color: '#94a3b8' }
                },
                x: {
                    border: { display: false },
                    grid: { display: false },
                    ticks: { font: { family: 'Poppins', size: 11, weight: '600' }, color: '#64748b' }
                }
            }
        }
    });
});
</script>

<style>
    /* Halus scrollbar */
    canvas { filter: drop-shadow(0 10px 8px rgba(0,0,0,0.02)); }
    .group:hover .group-hover\:rotate-6 { transform: rotate(6deg); }
</style>
@endsection