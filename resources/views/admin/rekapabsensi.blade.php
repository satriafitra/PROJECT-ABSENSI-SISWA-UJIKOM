@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    body { 
        font-family: 'Poppins', sans-serif; 
        background-color: #f8fafc; 
    }
    .main-container {
        margin-top: -20px; 
    }
    .glass-panel { 
        background: rgba(255, 255, 255, 0.9); 
        backdrop-filter: blur(8px); 
        border: 1px solid rgba(255, 255, 255, 0.5); 
    }
    .orange-gradient-compact { 
        background: linear-gradient(135deg, #ff8c00 0%, #f97316 100%); 
    }
    .row-hover { 
        transition: all 0.2s ease; 
    }
    .row-hover:hover { 
        background-color: #fffaf5;
    }
    /* Memperkecil pagination default Laravel */
    .pagination svg { width: 1.2rem; }
    .pagination nav div:first-child { display: none; }
</style>

<div class="p-6 main-container">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-orange-100 text-orange-600 text-[10px] font-bold mb-1 uppercase tracking-wider">
                <span class="relative flex h-1.5 w-1.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-orange-500"></span>
                </span>
                Admin Panel
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                Rekap <span class="text-orange-500">Absensi Seluruh Siswa</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Pantau kehadiran siswa secara real-time dari seluruh kelas.</p>
        </div>
        
        <div class="flex gap-4">
            <div class="glass-panel px-4 py-2 rounded-2xl shadow-md flex items-center gap-3 border-l-4 border-orange-500">
                <div class="orange-gradient-compact p-2 rounded-xl">
                    <i data-lucide="clipboard-check" class="text-white w-4 h-4"></i>
                </div>
                <div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter leading-none">Total Log</p>
                    <p class="text-lg font-extrabold text-slate-800 leading-none">{{ $attendances->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="glass-panel rounded-[1.5rem] shadow-xl shadow-slate-200/50 overflow-hidden">
        
        <div class="px-5 py-4 bg-white/50 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100">
            <form method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input 
                        type="date" 
                        name="date" 
                        value="{{ request('date', date('Y-m-d')) }}"
                        class="pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-orange-100 focus:border-orange-500 outline-none w-40 transition-all shadow-sm"
                    >
                </div>

                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Cari Siswa..."
                        value="{{ request('search') }}"
                        class="pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-orange-100 focus:border-orange-500 outline-none w-48 transition-all shadow-sm"
                    >
                </div>

                <button type="submit" class="orange-gradient-compact hover:opacity-90 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-md shadow-orange-200">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i> Terapkan
                </button>
            </form>

            <div class="flex items-center gap-2">
                <button class="flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-semibold hover:bg-emerald-700 transition-all shadow-sm">
                    <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-white"></i> Excel
                </button>
                <button class="flex items-center gap-1.5 px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-semibold hover:bg-slate-700 transition-all shadow-sm">
                    <i data-lucide="download" class="w-3.5 h-3.5 text-orange-400"></i> PDF
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                        <th class="px-5 py-3 border-b border-slate-100 text-center">No</th>
                        <th class="px-5 py-3 border-b border-slate-100">Info Siswa</th>
                        <th class="px-5 py-3 border-b border-slate-100">Guru/Pengawas</th>
                        <th class="px-5 py-3 border-b border-slate-100">Waktu</th>
                        <th class="px-5 py-3 border-b border-slate-100">Keterangan</th>
                        <th class="px-5 py-3 border-b border-slate-100 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($attendances as $item)
                        <tr class="row-hover">
                            <td class="px-5 py-4 text-center text-xs font-medium text-slate-400">
                                {{ $loop->iteration + ($attendances->currentPage()-1)*$attendances->perPage() }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full orange-gradient-compact flex items-center justify-center text-white font-bold text-xs shadow-sm border border-white/20">
                                        {{ substr($item->student->name ?? '?', 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 leading-tight">{{ $item->student->name ?? '-' }}</div>
                                        <div class="text-[10px] text-orange-500 font-bold tracking-tight mt-0.5">{{ $item->student->class->name ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-semibold text-slate-700">{{ $item->guru->nama ?? 'Sistem' }}</span>
                                    <span class="text-[9px] text-slate-400 font-medium">Verified System</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="p-1.5 bg-slate-100 rounded-lg text-slate-600">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <div class="text-[11px] font-mono font-bold text-slate-700">
                                        {{ $item->check_in ?? '--:--' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-[10px] text-slate-500 italic max-w-[150px] truncate">
                                    {{ $item->notes ?? 'Tanpa keterangan' }}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                {{-- PERBAIKAN LOGIKA STATUS DI SINI --}}
                                @php
                                    $status = strtolower($item->status);
                                @endphp

                                @if($status == 'hadir')
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        Hadir
                                    </span>
                                @elseif($status == 'izin')
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                                        Izin
                                    </span>
                                @elseif($status == 'sakit')
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-bold uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-100">
                                        Sakit
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-bold uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-100">
                                        {{ $item->status ?? 'Alpha' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                        <i data-lucide="search-x" class="w-8 h-8 text-slate-200"></i>
                                    </div>
                                    <p class="text-sm text-slate-400 font-medium">Belum ada data absensi untuk kriteria ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex justify-between items-center">
            <p class="text-[10px] text-slate-400 font-medium">Menampilkan {{ $attendances->count() }} data dari total {{ $attendances->total() }}</p>
            <div class="scale-90 origin-right">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    // Inisialisasi icon Lucide
    lucide.createIcons({
        strokeWidth: 2
    });
</script>
@endsection