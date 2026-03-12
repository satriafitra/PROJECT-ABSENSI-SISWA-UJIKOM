@extends('layouts.admin')

@section('content')
<div class="p-4 md:p-8 bg-[#fdfdfd] min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header --}}
        <div class="flex flex-col md:row md:items-center justify-between mb-10 gap-4">
            <div>
                <h1 class="text-4xl font-black text-slate-800 tracking-tight">
                    Analytics <span class="text-orange-500">Karakter</span>
                </h1>
                <p class="text-slate-500 font-medium">Performa rata-rata siswa berdasarkan kategori penilaian.</p>
            </div>
            <div class="flex gap-3">
                <button class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm shadow-sm hover:bg-slate-50 transition-all flex items-center">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i> Report Kategori
                </button>
            </div>
        </div>

        {{-- Chart Section (Kuantitas Kategori) --}}
        <div class="mb-10">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-800">Statistik Kategori</h3>
                        <p class="text-sm text-slate-400 font-medium">Rata-rata nilai total seluruh siswa per kategori</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-orange-500 rounded-full animate-pulse"></span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Global Analytics</span>
                    </div>
                </div>
                <div class="h-[350px] w-full">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Ranking & Student List --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Table Detail Siswa --}}
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="text-xl font-black text-slate-800">Daftar Penilaian Siswa</h3>
                    <span class="text-xs font-bold text-orange-500 bg-orange-50 px-3 py-1 rounded-lg">Total: {{ $students->count() }} Siswa</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Rata-rata</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Periode</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($students as $s)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm mr-4 group-hover:bg-orange-500 group-hover:text-white transition-all">
                                            {{ substr($s->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-700">{{ $s->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-medium">{{ $s->class->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-black group-hover:bg-orange-50 group-hover:text-orange-600">
                                        {{ number_format($s->avg_score, 1) }}%
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-xs font-bold text-slate-500">
                                    {{ $s->last_period }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="#" class="text-orange-500 hover:text-orange-700 font-bold text-xs uppercase tracking-tighter transition-all">
                                        View Detail →
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Summary Sidebar --}}
            <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl flex flex-col text-white">
                <h3 class="text-xl font-black mb-8 flex items-center text-orange-400">
                    <i data-lucide="zap" class="w-6 h-6 mr-2"></i> Quick Insight
                </h3>
                
                <div class="space-y-8 flex-grow">
                    <div class="relative pl-6 border-l-2 border-orange-500">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Skor Tertinggi Siswa</p>
                        <p class="text-2xl font-black">{{ number_format($students->max('avg_score'), 1) }}%</p>
                    </div>
                    
                    <div class="relative pl-6 border-l-2 border-slate-700">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Kategori Terkuat</p>
                        <p class="text-2xl font-black text-orange-400">
                            {{ $chartLabels[array_search(max($chartScores), $chartScores)] ?? '-' }}
                        </p>
                    </div>

                    <div class="relative pl-6 border-l-2 border-slate-700">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Kategori Perlu Perbaikan</p>
                        <p class="text-2xl font-black text-red-400">
                            {{ $chartLabels[array_search(min($chartScores), $chartScores)] ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 p-6 bg-slate-800 rounded-3xl border border-slate-700">
                    <p class="text-[10px] font-black text-slate-500 uppercase mb-3">Tips Untuk Admin</p>
                    <p class="text-xs text-slate-300 leading-relaxed font-medium">
                        Kategori dengan skor di bawah 70% memerlukan perhatian khusus dalam kurikulum karakter bulan depan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        
        // Gradient Orange ala Crypto Dashboard
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, '#f97316');   // Orange-500
        gradient.addColorStop(1, 'rgba(249, 115, 22, 0.1)'); // Transparan

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Rata-rata Skor Kategori',
                    data: {!! json_encode($chartScores) !!},
                    backgroundColor: gradient,
                    borderColor: '#f97316',
                    borderWidth: 2,
                    borderRadius: 15,
                    borderSkipped: false,
                    barThickness: 40,
                    hoverBackgroundColor: '#ea580c'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 },
                        padding: 15,
                        displayColors: false,
                        callbacks: {
                            label: (context) => ` Rata-rata: ${context.parsed.y}%`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: '#f8fafc', drawBorder: false },
                        ticks: {
                            font: { weight: 'bold' },
                            color: '#94a3b8',
                            callback: (value) => value + '%'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { weight: 'bold' },
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection