@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }
    
    /* Gold, Silver, Bronze Themes */
    .theme-gold { --glow-color: rgba(250, 204, 21, 0.4); }
    .theme-silver { --glow-color: rgba(148, 163, 184, 0.4); }
    .theme-bronze { --glow-color: rgba(180, 83, 9, 0.3); }

    .glow-effect {
        box-shadow: 0 15px 40px var(--glow-color);
    }
    
    /* Smooth floating animation */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    .float-anim {
        animation: float 6s ease-in-out infinite;
    }
</style>

<div class="min-h-screen bg-[#f8fafc] py-10 px-4 sm:px-6 lg:px-8 font-jakarta">
    
    {{-- Header --}}
    <div class="text-center max-w-3xl mx-auto mb-20 relative">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-orange-400/20 rounded-full blur-[80px] -z-10"></div>
        <h1 class="text-5xl md:text-6xl font-black mb-4 tracking-tighter">
            🏆 <span class="bg-gradient-to-br from-orange-500 via-orange-400 to-amber-500 bg-clip-text text-transparent drop-shadow-sm">Leaderboard</span>
        </h1>
        <p class="text-slate-500 text-lg font-medium tracking-wide">
            Apresiasi tertinggi untuk siswa berprestasi dengan kedisiplinan dan poin terbanyak.
        </p>
    </div>

    <div class="max-w-6xl mx-auto">
        
        {{-- Podium Top 3 --}}
        <div class="flex flex-col md:flex-row items-center justify-center gap-6 md:gap-8 mb-24 px-4 items-end">
            @php $top3 = $leaderboard->take(3); @endphp

            {{-- RANK 2: SILVER --}}
            @if(isset($top3[1]))
            <div class="order-2 md:order-1 w-full md:w-[280px] animate__animated animate__fadeInUp theme-silver" style="animation-delay: 0.2s;">
                <div class="glass-card rounded-[2.5rem] p-8 text-center relative group hover:-translate-y-2 transition-all duration-500 glow-effect border-b-8 border-slate-300">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-slate-300 to-slate-400 text-white text-[11px] font-black px-6 py-1.5 rounded-full shadow-lg shadow-slate-300/50 uppercase tracking-widest border border-white/50">Rank 2</div>
                    
                    <div class="relative w-24 h-24 mx-auto mb-5 float-anim" style="animation-delay: 1s;">
                        <div class="absolute inset-0 bg-slate-200 rounded-full blur-md opacity-50 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative w-full h-full bg-gradient-to-br from-slate-50 to-slate-200 rounded-[2rem] flex items-center justify-center text-slate-500 text-4xl font-black border-4 border-white shadow-inner transform rotate-3 group-hover:rotate-6 transition-transform">
                            {{ strtoupper(substr($top3[1]->student->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <h5 class="font-extrabold text-slate-800 text-lg truncate mb-2">{{ $top3[1]->student->name }}</h5>
                    <div class="inline-flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                        <i data-lucide="zap" class="w-4 h-4 text-slate-400 fill-slate-400"></i>
                        <span class="text-2xl font-black text-slate-600">{{ number_format($top3[1]->total_points) }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- RANK 1: GOLD --}}
            @if(isset($top3[0]))
            <div class="order-1 md:order-2 w-full md:w-[340px] z-20 animate__animated animate__fadeInUp theme-gold">
                <div class="glass-card rounded-[3rem] p-10 text-center relative group hover:-translate-y-3 transition-all duration-500 glow-effect border-b-8 border-yellow-400">
                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 flex flex-col items-center animate__animated animate__bounce animate__infinite animate__slower">
                        <i data-lucide="crown" class="w-14 h-14 text-yellow-400 fill-yellow-400 drop-shadow-[0_10px_10px_rgba(250,204,21,0.6)]"></i>
                    </div>
                    
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-yellow-400 to-amber-500 text-white text-xs font-black px-8 py-2 rounded-full shadow-lg shadow-yellow-400/50 uppercase tracking-widest border border-white/50 z-10 whitespace-nowrap">MVP • Rank 1</div>
                    
                    <div class="relative w-32 h-32 mx-auto mb-6 mt-4 float-anim">
                        <div class="absolute inset-0 bg-yellow-300 rounded-full blur-xl opacity-40 group-hover:opacity-70 transition-opacity"></div>
                        <div class="relative w-full h-full bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-[2.5rem] flex items-center justify-center text-amber-600 text-5xl font-black border-4 border-white shadow-xl transform -rotate-3 group-hover:rotate-0 transition-transform">
                            {{ strtoupper(substr($top3[0]->student->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <h4 class="text-2xl font-black text-slate-900 mb-3">{{ $top3[0]->student->name }}</h4>
                    <div class="inline-flex flex-col items-center bg-gradient-to-b from-orange-50 to-white px-8 py-3 rounded-2xl border border-orange-100 shadow-sm">
                        <span class="text-[10px] text-orange-400 font-bold uppercase tracking-widest mb-1">Total Points</span>
                        <div class="flex items-center gap-2">
                            <i data-lucide="flame" class="w-6 h-6 text-orange-500 fill-orange-500"></i>
                            <span class="text-4xl font-black bg-gradient-to-r from-orange-500 to-amber-500 bg-clip-text text-transparent">{{ number_format($top3[0]->total_points) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- RANK 3: BRONZE --}}
            @if(isset($top3[2]))
            <div class="order-3 w-full md:w-[280px] animate__animated animate__fadeInUp theme-bronze" style="animation-delay: 0.4s;">
                <div class="glass-card rounded-[2.5rem] p-8 text-center relative group hover:-translate-y-2 transition-all duration-500 glow-effect border-b-8 border-amber-600/60">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-600 to-amber-700 text-white text-[11px] font-black px-6 py-1.5 rounded-full shadow-lg shadow-amber-700/40 uppercase tracking-widest border border-white/50">Rank 3</div>
                    
                    <div class="relative w-24 h-24 mx-auto mb-5 float-anim" style="animation-delay: 2s;">
                        <div class="absolute inset-0 bg-amber-600 rounded-full blur-md opacity-20 group-hover:opacity-50 transition-opacity"></div>
                        <div class="relative w-full h-full bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-[2rem] flex items-center justify-center text-amber-700 text-4xl font-black border-4 border-white shadow-inner transform -rotate-3 group-hover:-rotate-6 transition-transform">
                            {{ strtoupper(substr($top3[2]->student->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <h5 class="font-extrabold text-slate-800 text-lg truncate mb-2">{{ $top3[2]->student->name }}</h5>
                    <div class="inline-flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                        <i data-lucide="zap" class="w-4 h-4 text-amber-600 fill-amber-600"></i>
                        <span class="text-2xl font-black text-slate-600">{{ number_format($top3[2]->total_points) }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Remaining Ranks Table --}}
        <div class="bg-white rounded-[3rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
            <div class="px-6 md:px-10 py-8 border-b border-slate-50 bg-gradient-to-r from-slate-50 to-white flex justify-between items-center">
                <h3 class="font-black text-slate-800 text-2xl flex items-center gap-4 tracking-tight">
                    <div class="w-12 h-12 rounded-2xl bg-orange-100 flex items-center justify-center shadow-inner">
                        <i data-lucide="list-ordered" class="text-orange-500 w-6 h-6"></i>
                    </div>
                    Peringkat Siswa Lainnya
                </h3>
            </div>
            
            <div class="p-4 md:p-8">
                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">
                                <th class="px-8 py-5 w-24 text-center">Pos</th>
                                <th class="px-8 py-5">Identitas Siswa</th>
                                <th class="px-8 py-5 text-right">Skor Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($leaderboard->skip(3) as $index => $data)
                            <tr class="group hover:bg-slate-50/50 transition-colors duration-300">
                                <td class="px-8 py-4 text-center">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 font-black text-base group-hover:bg-orange-500 group-hover:text-white group-hover:border-orange-500 group-hover:shadow-lg group-hover:shadow-orange-200 transition-all duration-300 transform group-hover:-translate-y-1">
                                        {{ $index + 4 }}
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-slate-100 to-slate-200 border-2 border-white shadow-sm flex items-center justify-center text-slate-500 font-bold text-lg group-hover:from-orange-100 group-hover:to-orange-200 group-hover:text-orange-600 transition-colors">
                                            {{ strtoupper(substr($data->student->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-base group-hover:text-orange-600 transition-colors">{{ $data->student->name }}</div>
                                            <div class="text-xs text-slate-400 font-medium mt-0.5">Siswa Aktif</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-100 shadow-sm rounded-xl group-hover:bg-orange-50 group-hover:border-orange-200 transition-colors">
                                        <span class="font-black text-slate-700 text-lg group-hover:text-orange-600 transition-colors">{{ number_format($data->total_points) }}</span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-orange-400 transition-colors">PTS</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($leaderboard->skip(3)->isEmpty())
                <div class="py-16 text-center bg-slate-50/50 rounded-3xl border border-dashed border-slate-200 mt-4">
                    <div class="w-20 h-20 rounded-full bg-white shadow-sm flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="users" class="text-slate-300 w-10 h-10"></i>
                    </div>
                    <h4 class="text-slate-500 font-bold text-lg">Belum Ada Data</h4>
                    <p class="text-slate-400 text-sm">Siswa lain belum memiliki poin gamifikasi yang cukup.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection