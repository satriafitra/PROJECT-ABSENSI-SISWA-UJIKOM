@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<script src="https://unpkg.com/lucide@latest"></script>

<div class="min-h-screen bg-[#f8fafc] py-12 px-6 lg:px-12 font-['Plus_Jakarta_Sans'] animate__animated animate__fadeIn">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-2">
                <span class="bg-gradient-to-r from-[#FF8C00] to-[#F27121] bg-clip-text text-transparent">Gamification</span> Console
            </h1>
            <p class="text-slate-500 text-lg">Pantau performa dan atur mekanisme poin secara real-time.</p>
        </div>
        
        <div class="bg-white px-6 py-3 rounded-full shadow-sm border border-slate-100 flex items-center gap-3 animate__animated animate__pulse animate__infinite animate__slow">
            <i data-lucide="clock" class="text-orange-500 w-5 h-5"></i>
            <span id="realtime-clock" class="font-extrabold text-slate-800 tracking-widest text-lg">00:00:00</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-4">
            <div class="bg-white/80 backdrop-blur-md rounded-[2rem] p-8 border border-white shadow-xl shadow-slate-200/50 sticky top-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 shadow-inner">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xl">Rule Engine</h4>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Automated Logic</p>
                    </div>
                </div>

                <form action="{{ route('admin.gamifikasi.rule.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider ml-1">
                            <i data-lucide="tag" class="w-3 h-3"></i> Nama Aturan
                        </label>
                        <input type="text" name="rule_name" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 focus:outline-none focus:border-orange-500 focus:bg-white transition-all font-semibold" placeholder="Misal: Early Bird Reward" required>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider ml-1">
                                <i data-lucide="settings-2" class="w-3 h-3"></i> Kondisi
                            </label>
                            <select name="condition_operator" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 focus:outline-none focus:border-orange-500 focus:bg-white transition-all font-semibold">
                                <option value="<">Check-in Sebelum</option>
                                <option value=">">Terlambat Setelah</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider ml-1">
                                <i data-lucide="clock" class="w-3 h-3"></i> Target Waktu
                            </label>
                            <input type="time" name="condition_value" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 focus:outline-none focus:border-orange-500 focus:bg-white transition-all font-semibold" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider ml-1">
                            <i data-lucide="sparkles" class="w-3 h-3"></i> Nominal Poin
                        </label>
                        <div class="flex">
                            <input type="number" name="point_modifier" class="w-full bg-slate-50 border-2 border-slate-100 rounded-l-2xl p-4 focus:outline-none focus:border-orange-500 focus:bg-white transition-all font-semibold" placeholder="10" required>
                            <span class="bg-slate-900 text-white px-6 flex items-center font-bold rounded-r-2xl">PTS</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-[#FF8C00] to-[#F27121] hover:scale-[1.02] active:scale-[0.98] text-white font-bold py-4 rounded-2xl shadow-lg shadow-orange-200 transition-all flex items-center justify-center gap-3 mt-4">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i> Simpan Aturan
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                            <i data-lucide="trophy" class="w-6 h-6"></i>
                        </div>
                        <h4 class="font-extrabold text-slate-800 text-xl">Top Integrity Leaderboard</h4>
                    </div>
                    <a href="{{ route('admin.gamifikasi.leaderboard') }}" class="bg-slate-50 hover:bg-slate-100 text-slate-600 px-6 py-2 rounded-full font-bold text-sm transition-colors flex items-center gap-2">
                        Full Ranking <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="p-4 sm:p-8 space-y-4">
                    @forelse($leaderboard as $index => $data)
                    <div class="group bg-white hover:bg-orange-50/50 border border-slate-100 hover:border-orange-200 p-4 rounded-3xl transition-all duration-300 flex items-center gap-5 animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.1 }}s;">
                        
                        <div class="relative shrink-0">
                            <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center text-orange-500 font-black text-xl overflow-hidden">
                                {{ substr($data->student->name ?? '?', 0, 1) }}
                            </div>
                            @if($index < 3)
                            <div class="absolute -top-2 -left-2 w-7 h-7 rounded-lg flex items-center justify-center text-[10px] font-black shadow-sm text-white
                                {{ $index == 0 ? 'bg-yellow-400' : ($index == 1 ? 'bg-slate-400' : 'bg-orange-700') }}">
                                #{{ $index + 1 }}
                            </div>
                            @endif
                        </div>

                        <div class="flex-grow min-w-0">
                            <div class="flex justify-between items-end mb-2">
                                <div>
                                    <h6 class="font-bold text-slate-800 truncate leading-tight">{{ $data->student->name ?? 'Student' }}</h6>
                                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-tighter">Grade Achievement</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="block text-2xl font-black text-orange-500 leading-none">{{ number_format($data->total_points) }}</span>
                                    <span class="text-[9px] font-extrabold text-slate-400 uppercase">Points</span>
                                </div>
                            </div>
                            
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $index == 0 ? 'bg-yellow-400' : 'bg-emerald-400' }}" 
                                     style="width: {{ 100 - ($index * 10) }}%"></div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-20">
                        <i data-lucide="ghost" class="w-16 h-16 text-slate-200 mx-auto mb-4"></i>
                        <p class="text-slate-400 font-medium tracking-tight text-lg">Belum ada aktivitas poin yang tercatat.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        document.getElementById('realtime-clock').innerText = now.toLocaleTimeString('id-ID', options);
    }
    setInterval(updateClock, 1000);
    updateClock();

    lucide.createIcons();
</script>
@endsection