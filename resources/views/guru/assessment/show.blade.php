@extends('layouts.admin')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('guru.assessment.index') }}" class="group inline-flex items-center text-slate-400 hover:text-orange-600 transition-colors font-bold uppercase text-xs tracking-widest">
                <i data-lucide="chevron-left" class="w-5 h-5 mr-1 group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Daftar
            </a>
        </div>

        <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-white">
            {{-- Profile Header --}}
            <div class="p-10 bg-gradient-to-br from-slate-900 to-slate-800 text-white relative overflow-hidden">
                <i data-lucide="zap" class="absolute -right-10 -top-10 w-64 h-64 text-orange-500/10 rotate-12"></i>
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 relative z-10">
                    <div class="flex items-center gap-8">
                        <div class="h-24 w-24 rounded-[2rem] bg-gradient-to-tr from-orange-400 to-amber-600 flex items-center justify-center text-white font-black text-4xl shadow-2xl shadow-orange-500/40 border-4 border-white/20">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-4xl font-black tracking-tight">{{ $student->name }}</h2>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="bg-orange-500/20 text-orange-400 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-orange-500/30">
                                    {{ $student->class->name ?? 'N/A' }}
                                </span>
                                <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
                                    Periode {{ $assessment->period }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-xl p-6 rounded-[2rem] border border-white/10 flex flex-col items-center min-w-[160px]">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Skor Rata-rata</p>
                        <p class="text-5xl font-black text-orange-500">{{ number_format($assessment->details->avg('score'), 0) }}<span class="text-2xl ml-1 text-orange-400/50">%</span></p>
                    </div>
                </div>
            </div>

            {{-- Details Content --}}
            <div class="p-10 lg:p-14">
                <div class="grid grid-cols-1 gap-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-black text-slate-800 flex items-center tracking-tight">
                            <i data-lucide="activity" class="w-8 h-8 mr-4 text-orange-500 p-1.5 bg-orange-50 rounded-lg"></i>
                            Rincian Parameter
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($assessment->details as $detail)
                        <div class="p-8 rounded-[2.5rem] bg-slate-50 border border-slate-100 hover:shadow-xl hover:shadow-orange-100/50 transition-all group">
                            <div class="flex justify-between items-start mb-6">
                                <div class="max-w-[70%]">
                                    <p class="font-black text-slate-700 uppercase tracking-widest text-xs mb-1">{{ $detail->category->name }}</p>
                                    <p class="text-xs text-slate-400 font-medium italic line-clamp-2">{{ $detail->category->description }}</p>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-2xl font-black text-orange-600">{{ $detail->score }}</span>
                                    <span class="text-[8px] font-black text-slate-300 uppercase">Poin</span>
                                </div>
                            </div>
                            
                            {{-- Visual Score Bar --}}
                            <div class="relative w-full h-4 bg-white rounded-full p-1 shadow-inner overflow-hidden border border-slate-100">
                                <div class="h-full rounded-full transition-all duration-1000 bg-gradient-to-r from-orange-400 to-amber-500 shadow-sm" 
                                     style="width: {{ $detail->score }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Teacher's Note Card --}}
                    @if($assessment->general_notes)
                    <div class="mt-4 p-10 bg-gradient-to-br from-orange-50 to-amber-50 rounded-[3rem] border-2 border-dashed border-orange-200 relative overflow-hidden">
                        <i data-lucide="quote" class="absolute -right-4 -bottom-4 w-32 h-32 text-orange-200/40"></i>
                        <div class="flex items-center gap-3 mb-6 relative z-10">
                            <div class="p-2 bg-orange-500 rounded-xl">
                                <i data-lucide="message-circle" class="w-5 h-5 text-white"></i>
                            </div>
                            <h4 class="font-black text-orange-900 uppercase tracking-widest text-xs">Catatan Evaluasi Guru</h4>
                        </div>
                        <p class="text-orange-900/80 font-bold italic text-lg leading-relaxed relative z-10">
                            "{{ $assessment->general_notes }}"
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endsection