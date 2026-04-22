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
                Pusat <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-600">Aduan</span>
            </h1>
            <p class="text-slate-500 mt-2 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Sistem Ticketing & Laporan Siswa
            </p>
        </div>
        <div>
            <a href="{{ route('admin.tickets.create') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-orange-500 text-white font-bold shadow-xl shadow-orange-500/30 hover:bg-orange-600 transition-all hover:-translate-y-1">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Tambah Tiket
            </a>
        </div>
    </div>

    {{-- Stats Cards & KPI --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
            $stats = [
                ['label'=>'Tiket Masuk', 'value'=>$totalOpen, 'icon'=>'inbox', 'gradient'=>'from-slate-800 to-slate-900'],
                ['label'=>'Sedang Diproses', 'value'=>$totalInProgress, 'icon'=>'loader', 'gradient'=>'from-amber-500 to-orange-500'],
                ['label'=>'Tiket Selesai', 'value'=>$totalClosed, 'icon'=>'check-circle', 'gradient'=>'from-emerald-500 to-teal-600'],
            ];
            @endphp

            @foreach ($stats as $index => $stat)
            <div class="fade-up group bg-white rounded-[2.5rem] p-7 shadow-xl shadow-slate-200/40 border border-slate-100 relative overflow-hidden transition-all duration-500 hover:-translate-y-3" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="relative z-10">
                    <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br {{ $stat['gradient'] }} text-white mb-6">
                        <i data-lucide="{{ $stat['icon'] }}" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">{{ $stat['label'] }}</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-slate-800 tracking-tighter">{{ number_format($stat['value']) }}</span>
                        <span class="text-xs font-bold text-slate-400">Tiket</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- KPI Response Time --}}
        <div class="bg-white rounded-[2.5rem] p-7 shadow-xl shadow-slate-200/40 border border-slate-100 fade-up" style="animation-delay: 0.3s">
            <h3 class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                <i data-lucide="clock" class="w-4 h-4"></i> Rata-rata Waktu Balas
            </h3>
            <div class="space-y-4">
                @forelse($responses as $resp)
                <div class="flex items-center justify-between border-b border-slate-50 pb-3 last:border-0 last:pb-0">
                    <span class="text-sm font-bold text-slate-700">{{ $resp->operator_name }}</span>
                    <span class="text-sm font-black text-orange-500">
                        {{ number_format($resp->avg_response_minutes, 1) }} <span class="text-xs font-medium text-slate-400">Menit</span>
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-500 italic">Belum ada data balasan dari operator.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Advanced Analytics Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        {{-- Chart Kepuasan Pelapor --}}
        <div class="bg-white rounded-[2.5rem] p-7 shadow-xl shadow-slate-200/40 border border-slate-100 fade-up relative overflow-hidden" style="animation-delay: 0.4s">
            <!-- Decorative gradient -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-orange-500/10 rounded-full blur-3xl"></div>
            
            <h3 class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-6 flex items-center gap-2 relative z-10">
                <i data-lucide="pie-chart" class="w-4 h-4 text-orange-500"></i> Tingkat Kepuasan Pelapor
            </h3>
            
            <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                <!-- Average Score Display -->
                <div class="flex flex-col items-center justify-center bg-slate-50 border border-slate-100 p-6 rounded-[2rem] min-w-[140px]">
                    <span class="text-5xl font-black text-slate-800 tracking-tighter">{{ number_format($averageRating, 1) }}</span>
                    <div class="flex items-center gap-1 mt-2 mb-1 text-orange-500">
                        @for($i = 1; $i <= 5; $i++)
                            <i data-lucide="star" class="w-4 h-4 {{ $i <= round($averageRating) ? 'fill-orange-500' : 'text-slate-300' }}"></i>
                        @endfor
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">{{ $totalRatings }} Total Ulasan</span>
                </div>
                
                <!-- Chart Canvas -->
                <div class="relative h-56 w-full flex-1">
                    @if($totalRatings > 0)
                        <canvas id="ratingChart"></canvas>
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400">
                            <i data-lucide="bar-chart-2" class="w-10 h-10 mb-2 opacity-50"></i>
                            <p class="text-xs font-medium">Belum ada data rating</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top Active Students --}}
        <div class="bg-white rounded-[2.5rem] p-7 shadow-xl shadow-slate-200/40 border border-slate-100 fade-up" style="animation-delay: 0.5s">
            <h3 class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4"></i> Pelapor Teraktif
            </h3>
            <div class="space-y-4 mt-4">
                @forelse($topStudents as $index => $student)
                <div class="flex items-center gap-4 border-b border-slate-50 pb-3 last:border-0 last:pb-0">
                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 font-black flex items-center justify-center">
                        #{{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-slate-800">{{ $student->name }}</h4>
                        <p class="text-xs text-slate-500">NIS: {{ $student->nis }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-black text-slate-700">{{ $student->tickets_count }}</span>
                        <p class="text-[10px] text-slate-400 uppercase font-bold">Tiket</p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-500 italic">Belum ada siswa yang membuat aduan.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Filter & Daftar Tiket --}}
    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-slate-50 p-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-slate-800">Daftar Tiket</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 rounded-xl font-medium text-sm {{ !$status ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition">Semua</a>
                <a href="{{ route('admin.tickets.index', ['status'=>'Open']) }}" class="px-4 py-2 rounded-xl font-medium text-sm {{ $status == 'Open' ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition">Open</a>
                <a href="{{ route('admin.tickets.index', ['status'=>'In-Progress']) }}" class="px-4 py-2 rounded-xl font-medium text-sm {{ $status == 'In-Progress' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition">In-Progress</a>
                <a href="{{ route('admin.tickets.index', ['status'=>'Closed']) }}" class="px-4 py-2 rounded-xl font-medium text-sm {{ $status == 'Closed' ? 'bg-slate-800 text-white shadow-lg shadow-slate-800/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition">Closed</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs uppercase text-slate-400 border-b border-slate-100">
                        <th class="py-4 px-4 font-bold">Tiket ID</th>
                        <th class="py-4 px-4 font-bold">Pelapor</th>
                        <th class="py-4 px-4 font-bold">Subjek</th>
                        <th class="py-4 px-4 font-bold">Prioritas</th>
                        <th class="py-4 px-4 font-bold">Status</th>
                        <th class="py-4 px-4 font-bold">Tanggal</th>
                        <th class="py-4 px-4 font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr class="border-b border-slate-50 hover:bg-slate-50 transition group">
                        <td class="py-4 px-4 text-sm font-bold text-slate-600">#TK-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-4 px-4">
                            <p class="text-sm font-bold text-slate-800">{{ $ticket->reporter->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-slate-400">NIS: {{ $ticket->reporter->nis ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-4 max-w-xs">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $ticket->subject }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ Str::limit($ticket->description, 40) }}</p>
                        </td>
                        <td class="py-4 px-4">
                            @if($ticket->priority == 'High')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-xs font-bold">High</span>
                            @elseif($ticket->priority == 'Mid')
                                <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-600 text-xs font-bold">Mid</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">Low</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($ticket->status == 'Open')
                                <span class="px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-blue-600 text-xs font-bold">Open</span>
                            @elseif($ticket->status == 'In-Progress')
                                <span class="px-3 py-1 rounded-full border border-amber-200 bg-amber-50 text-amber-600 text-xs font-bold">In-Progress</span>
                            @else
                                <span class="px-3 py-1 rounded-full border border-slate-200 bg-slate-100 text-slate-500 text-xs font-bold">Closed</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-sm text-slate-500">{{ $ticket->created_at->format('d M Y, H:i') }}</td>
                        <td class="py-4 px-4">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="inline-flex items-center justify-center p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-orange-500 hover:text-white transition">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            Tidak ada tiket aduan yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // Chart.js untuk Rating
        const chartCanvas = document.getElementById('ratingChart');
        if (chartCanvas) {
            const ctx = chartCanvas.getContext('2d');
            const ratingsData = @json(array_values($ratings));
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['5 Bintang', '4 Bintang', '3 Bintang', '2 Bintang', '1 Bintang'],
                    datasets: [{
                        data: ratingsData,
                        backgroundColor: [
                            '#10b981', // 5 star - emerald
                            '#3b82f6', // 4 star - blue
                            '#f59e0b', // 3 star - amber
                            '#f97316', // 2 star - orange
                            '#ef4444'  // 1 star - red
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: {
                                    family: "'Plus Jakarta Sans', sans-serif",
                                    size: 11,
                                    weight: 'bold'
                                },
                                usePointStyle: true,
                                padding: 15,
                                color: '#64748b'
                            }
                        }
                    },
                    cutout: '75%'
                }
            });
        }
    });
</script>
@endsection
