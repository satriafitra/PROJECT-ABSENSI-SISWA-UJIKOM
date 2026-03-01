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
    <div class="flex flex-row items-center justify-between gap-4 mb-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-orange-100 text-orange-600 text-[10px] font-bold mb-1 uppercase tracking-wider">
                <span class="relative flex h-1.5 w-1.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-orange-500"></span>
                </span>
                Live Report
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                Rekap <span class="text-orange-500">Absensi</span>
            </h1>
        </div>
        
        <div class="glass-panel px-4 py-2 rounded-2xl shadow-md flex items-center gap-3 border-l-4 border-orange-500">
            <div class="orange-gradient-compact p-2 rounded-xl">
                <i data-lucide="users" class="text-white w-4 h-4"></i>
            </div>
            <div>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter leading-none">Total</p>
                <p class="text-lg font-extrabold text-slate-800 leading-none">{{ $attendances->total() }}</p>
            </div>
        </div>
    </div>

    <div class="glass-panel rounded-[1.5rem] shadow-xl shadow-slate-200/50 overflow-hidden">
        
        <div class="px-5 py-4 bg-white/50 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100">
            <form method="GET" class="flex items-center gap-2">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input 
                        type="date" 
                        name="date" 
                        value="{{ $date }}"
                        class="pl-9 pr-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-orange-100 focus:border-orange-500 outline-none w-40 transition-all shadow-sm"
                    >
                </div>
                <button type="submit" class="orange-gradient-compact hover:opacity-90 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i> Filter
                </button>
            </form>

            <button class="flex items-center gap-1.5 px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-semibold hover:bg-slate-700 transition-all shadow-sm">
                <i data-lucide="download" class="w-3.5 h-3.5 text-orange-400"></i> Export
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                        <th class="px-5 py-3 border-b border-slate-100 text-center">No</th>
                        <th class="px-5 py-3 border-b border-slate-100">Tanggal</th>
                        <th class="px-5 py-3 border-b border-slate-100">Siswa & Kelas</th>
                        <th class="px-5 py-3 border-b border-slate-100">Guru</th>
                        <th class="px-5 py-3 border-b border-slate-100">Masuk</th>
                        <th class="px-5 py-3 border-b border-slate-100 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($attendances as $item)
                        <tr class="row-hover">
                            <td class="px-5 py-3 text-center text-xs font-medium text-slate-400">
                                {{ $loop->iteration + ($attendances->currentPage()-1)*$attendances->perPage() }}
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-semibold text-slate-700">{{ \Carbon\Carbon::parse($item->date)->format('d M y') }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full orange-gradient-compact flex items-center justify-center text-white font-bold text-[10px] shadow-sm border border-white/20">
                                        {{ substr($item->student->name ?? '?', 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 leading-tight">{{ $item->student->name ?? '-' }}</div>
                                        <div class="text-[9px] text-slate-400 font-medium tracking-tight">{{ $item->student->class->name ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-xs text-slate-600 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="user-check" class="w-3.5 h-3.5 text-slate-300"></i>
                                    {{ Str::limit($item->guru->nama ?? '-', 15) }}
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-mono font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded">
                                    {{ $item->check_in ?? '00:00' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-3 py-1 rounded-lg text-[9px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    Hadir
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center">
                                <i data-lucide="info" class="w-8 h-8 text-slate-200 mx-auto mb-2"></i>
                                <p class="text-xs text-slate-400 font-medium">Data tidak ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex justify-center scale-90">
            {{ $attendances->links() }}
        </div>
    </div>
</div>

<script>
    lucide.createIcons({
        strokeWidth: 2
    });
</script>
@endsection