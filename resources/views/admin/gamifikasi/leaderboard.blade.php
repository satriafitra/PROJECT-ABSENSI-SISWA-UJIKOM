@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<script src="https://unpkg.com/lucide@latest"></script>

<div class="min-h-screen bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-12 font-['Plus_Jakarta_Sans'] animate__animated animate__fadeIn">
    
    <div class="text-center max-w-2xl mx-auto mb-16">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 tracking-tight">
            🏆 <span class="bg-gradient-to-r from-[#FF8C00] to-[#FF4500] bg-clip-text text-transparent">Hall of Fame</span>
        </h1>
        <p class="text-slate-500 text-lg font-medium">Apresiasi khusus untuk siswa dengan integritas dan semangat tertinggi.</p>
    </div>

    <div class="max-w-6xl mx-auto">
        
        <div class="flex flex-col md:flex-row items-center md:items-end justify-center gap-6 mb-20 px-4">
            @php $top3 = $leaderboard->take(3); @endphp

            @if(isset($top3[1]))
            <div class="order-2 md:order-1 w-full md:w-64 animate__animated animate__fadeInLeft" style="animation-delay: 0.3s;">
                <div class="bg-white rounded-[2.5rem] p-8 text-center shadow-xl shadow-slate-200/50 border border-slate-100 relative group hover:-translate-y-2 transition-transform duration-500">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-slate-300 text-white text-[10px] font-black px-4 py-1 rounded-full shadow-sm">RANK #2</div>
                    <div class="w-20 h-20 bg-slate-50 rounded-2xl mx-auto mb-4 flex items-center justify-center text-slate-400 text-3xl font-black border-2 border-white shadow-inner">
                        {{ substr($top3[1]->student->name, 0, 1) }}
                    </div>
                    <h5 class="font-bold text-slate-800 truncate mb-1">{{ $top3[1]->student->name }}</h5>
                    <div class="text-2xl font-black bg-gradient-to-r from-slate-400 to-slate-600 bg-clip-text text-transparent italic">
                        {{ number_format($top3[1]->total_points) }} <span class="text-[10px] text-slate-400 not-italic uppercase tracking-tighter">pts</span>
                    </div>
                </div>
                <div class="hidden md:block h-32 w-full bg-gradient-to-b from-slate-100 to-transparent rounded-t-3xl mt-4 opacity-50"></div>
            </div>
            @endif

            @if(isset($top3[0]))
            <div class="order-1 md:order-2 w-full md:w-72 z-10 animate__animated animate__fadeInUp">
                <div class="bg-white rounded-[3rem] p-10 text-center shadow-2xl shadow-orange-200/50 border-2 border-orange-100 relative group hover:-translate-y-4 transition-transform duration-500 overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-100/50 rounded-full blur-3xl group-hover:bg-orange-200/50 transition-colors"></div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-center mb-6 animate__animated animate__bounce animate__infinite animate__slow">
                            <i data-lucide="crown" class="w-12 h-12 text-yellow-400 fill-yellow-400 drop-shadow-[0_0_15px_rgba(250,204,21,0.5)]"></i>
                        </div>
                        <div class="w-24 h-24 bg-orange-50 rounded-[2rem] mx-auto mb-6 flex items-center justify-center text-[#FF8C00] text-4xl font-black border-4 border-white shadow-lg shadow-orange-100">
                            {{ substr($top3[0]->student->name, 0, 1) }}
                        </div>
                        <h4 class="text-xl font-extrabold text-slate-900 mb-2">{{ $top3[0]->student->name }}</h4>
                        <div class="bg-orange-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full inline-block mb-4 tracking-widest uppercase">Grand Champion</div>
                        <div class="text-4xl font-black bg-gradient-to-r from-[#FF8C00] to-[#FF4500] bg-clip-text text-transparent italic leading-tight">
                            {{ number_format($top3[0]->total_points) }}
                            <span class="block text-xs text-slate-400 not-italic uppercase font-bold tracking-[0.2em] mt-1">Total Points</span>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block h-48 w-full bg-gradient-to-b from-orange-50 to-transparent rounded-t-3xl mt-4 opacity-70"></div>
            </div>
            @endif

            @if(isset($top3[2]))
            <div class="order-3 w-full md:w-64 animate__animated animate__fadeInRight" style="animation-delay: 0.3s;">
                <div class="bg-white rounded-[2.5rem] p-8 text-center shadow-xl shadow-slate-200/50 border border-slate-100 relative group hover:-translate-y-2 transition-transform duration-500">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-[#CD7F32] text-white text-[10px] font-black px-4 py-1 rounded-full shadow-sm">RANK #3</div>
                    <div class="w-20 h-20 bg-orange-50/30 rounded-2xl mx-auto mb-4 flex items-center justify-center text-[#A0522D] text-3xl font-black border-2 border-white shadow-inner">
                        {{ substr($top3[2]->student->name, 0, 1) }}
                    </div>
                    <h5 class="font-bold text-slate-800 truncate mb-1">{{ $top3[2]->student->name }}</h5>
                    <div class="text-2xl font-black bg-gradient-to-r from-[#8B4513] to-[#CD7F32] bg-clip-text text-transparent italic">
                        {{ number_format($top3[2]->total_points) }} <span class="text-[10px] text-slate-400 not-italic uppercase tracking-tighter">pts</span>
                    </div>
                </div>
                <div class="hidden md:block h-24 w-full bg-gradient-to-b from-orange-50/50 to-transparent rounded-t-3xl mt-4 opacity-40"></div>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
            <div class="px-8 py-6 border-b border-slate-50">
                <h3 class="font-extrabold text-slate-800 flex items-center gap-3">
                    <i data-lucide="list-ordered" class="text-orange-500"></i>
                    Peringkat Selanjutnya
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">
                            <th class="px-8 py-5">Peringkat</th>
                            <th class="px-8 py-5">Nama Siswa</th>
                            <th class="px-8 py-5 text-right">Total Skor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($leaderboard->skip(3) as $index => $data)
                        <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                            <td class="px-8 py-6">
                                <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 font-black text-sm group-hover:bg-white group-hover:shadow-sm transition-all">
                                    #{{ $index + 4 }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                                        {{ substr($data->student->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-700">{{ $data->student->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <span class="inline-flex items-center bg-orange-50 text-orange-600 font-black px-4 py-2 rounded-full text-sm">
                                    {{ number_format($data->total_points) }} PTS
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($leaderboard->skip(3)->isEmpty())
            <div class="py-20 text-center">
                <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="users" class="text-slate-300 w-10 h-10"></i>
                </div>
                <p class="text-slate-400 font-medium italic">Belum ada peringkat tambahan.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection