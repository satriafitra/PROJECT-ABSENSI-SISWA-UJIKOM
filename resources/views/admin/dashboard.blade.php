@extends('layouts.admin')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

<div class="p-8 bg-[#f8fafc] min-h-screen font-sans">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">
                Dashboard <span class="text-orange-500">Overview</span>
            </h1>
            <p class="text-slate-500 mt-1 italic">Selamat datang kembali, Admin. Berikut ringkasan data hari ini.</p>
        </div>
        <div class="flex gap-3">
            <button class="flex items-center gap-2 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm hover:bg-slate-50 transition">
                <i data-lucide="calendar" class="w-4 h-4 text-orange-500"></i>
                <span class="text-sm font-medium text-slate-600">{{ date('d M Y') }}</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
        @php
        $cards = [
            ['title'=>'Total Siswa', 'value'=>$totalSiswa, 'icon'=>'users', 'color'=>'from-orange-400 to-orange-600'],
            ['title'=>'Total Guru', 'value'=>$totalGuru, 'icon'=>'graduation-cap', 'color'=>'from-amber-400 to-orange-500'],
            ['title'=>'Total Kelas', 'value'=>$totalKelas, 'icon'=>'door-open', 'color'=>'from-orange-500 to-red-500'],
            ['title'=>'Hadir Hari Ini', 'value'=>$hadir, 'icon'=>'check-circle', 'color'=>'from-orange-600 to-orange-800'],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="group relative bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-2">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            
            <div class="relative z-10 flex flex-col gap-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-gradient-to-br {{ $card['color'] }} shadow-lg shadow-orange-200 text-white">
                    <i data-lucide="{{ $card['icon'] }}" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-slate-500 font-medium text-sm tracking-wide uppercase">{{ $card['title'] }}</h2>
                    <p class="text-4xl font-black text-slate-800 mt-1 tracking-tight">{{ number_format($card['value']) }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Analitik Absensi</h2>
                    <p class="text-sm text-slate-400">Distribusi status kehadiran hari ini</p>
                </div>
                <i data-lucide="bar-chart-3" class="text-orange-400 w-6 h-6"></i>
            </div>
            <div class="relative h-[300px]">
                <canvas id="absensiChart"></canvas>
            </div>
        </div>

        <div class="bg-slate-900 rounded-3xl shadow-2xl p-8 relative overflow-hidden group">
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg width="100%" height="100%"><rect width="100%" height="100%" fill="url(#grid-pattern)" /></svg>
            </div>

            <h2 class="text-2xl font-bold text-white mb-6 relative z-10">Akses Cepat</h2>
            
            <div class="grid grid-cols-1 gap-4 relative z-10">
                @php
                $actions = [
                    ['name' => 'Data Siswa', 'icon' => 'user-plus'],
                    ['name' => 'Data Guru', 'icon' => 'briefcase'],
                    ['name' => 'Data Kelas', 'icon' => 'layout'],
                    ['name' => 'Rekap Absen', 'icon' => 'file-text'],
                ];
                @endphp

                @foreach($actions as $action)
                <a href="#" class="flex items-center justify-between bg-white/10 backdrop-blur-md border border-white/10 p-4 rounded-2xl hover:bg-orange-500 transition-all duration-300 group/item">
                    <div class="flex items-center gap-4">
                        <div class="p-2 rounded-lg bg-orange-500/20 text-orange-400 group-hover/item:bg-white/20 group-hover/item:text-white transition">
                            <i data-lucide="{{ $action['icon'] }}" class="w-5 h-5"></i>
                        </div>
                        <span class="text-white font-medium">{{ $action['name'] }}</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-500 group-hover/item:text-white transform group-hover/item:translate-x-1 transition"></i>
                </a>
                @endforeach
            </div>

            <div class="mt-8 p-4 rounded-2xl bg-gradient-to-r from-orange-500 to-amber-500 text-white relative z-10">
                <p class="text-xs font-light opacity-90">Sistem Manajemen Sekolah v2.0</p>
                <p class="text-sm font-bold">Semua sistem berjalan normal.</p>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide Icons
        lucide.createIcons();

        const ctx = document.getElementById('absensiChart');
        if (!ctx) return;

        // Custom Gradient for Chart
        const gradient1 = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient1.addColorStop(0, '#f97316');
        gradient1.addColorStop(1, '#fb923c');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Alpha'],
                datasets: [{
                    data: [120, 15, 8],
                    backgroundColor: [
                        '#f97316', // Primary Orange
                        '#fdba74', // Muted Orange
                        '#1e293b'  // Dark Navy for contrast
                    ],
                    hoverBackgroundColor: ['#ea580c', '#fb923c', '#0f172a'],
                    borderWidth: 8,
                    borderColor: '#ffffff',
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 25,
                            font: {
                                size: 14,
                                weight: '600',
                                family: "'Plus Jakarta Sans', sans-serif"
                            },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        padding: 16,
                        backgroundColor: '#1e293b',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 12,
                        displayColors: true
                    }
                }
            }
        });
    });
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
</style>
@endsection