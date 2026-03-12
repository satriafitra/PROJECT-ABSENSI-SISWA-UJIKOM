@extends('layouts.admin')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
            <div>
                <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight flex items-center">
                    <i data-lucide="clipboard-check" class="w-10 h-10 mr-4 text-orange-500"></i>
                    Evaluasi Siswa
                </h2>
                <p class="text-slate-500 mt-1 font-medium italic">Menampilkan siswa yang <span class="text-orange-500 underline decoration-orange-300">sudah dinilai</span></p>
            </div>
            <a href="{{ route('guru.assessment.create') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-bold rounded-2xl shadow-xl shadow-orange-200 hover:shadow-orange-300 hover:-translate-y-1 transition-all duration-300 group">
                <i data-lucide="plus-circle" class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform"></i>
                Tambah Penilaian Baru
            </a>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
        <div class="bg-orange-50 border-2 border-orange-100 text-orange-600 p-4 rounded-2xl mb-6 flex items-center shadow-sm animate-bounce">
            <i data-lucide="party-popper" class="w-5 h-5 mr-2"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Siswa</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Progress Karakter</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Status Emote</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Periode</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($students as $student)
                        @php
                            $progress = $student->progress ?? 0;
                            // Logika Penentuan Icon & Warna berdasarkan Progress
                            if($progress <= 20) { $icon = 'frown'; $color = 'text-red-500'; $bg = 'bg-red-50'; $label = 'Perlu Bimbingan'; }
                            elseif($progress <= 45) { $icon = 'meh'; $color = 'text-amber-500'; $bg = 'bg-amber-50'; $label = 'Cukup'; }
                            elseif($progress <= 70) { $icon = 'smile'; $color = 'text-orange-500'; $bg = 'bg-orange-50'; $label = 'Baik'; }
                            elseif($progress <= 85) { $icon = 'laugh'; $color = 'text-emerald-500'; $bg = 'bg-emerald-50'; $label = 'Sangat Baik'; }
                            else { $icon = 'award'; $color = 'text-indigo-600'; $bg = 'bg-indigo-50'; $label = 'Istimewa'; }
                        @endphp
                        <tr class="hover:bg-orange-50/30 transition-all group">
                            {{-- Identitas Siswa --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center text-white font-black text-lg mr-4 shadow-lg group-hover:scale-110 transition-transform">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-700 leading-tight">{{ $student->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1 italic">
                                            {{ $student->class->name ?? 'Tanpa Kelas' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Progress Bar --}}
                            <td class="px-8 py-6">
                                <div class="w-full max-w-[160px]">
                                    <div class="flex justify-between mb-2">
                                        <span class="text-[10px] font-black {{ $color }} uppercase">
                                            {{ number_format($progress, 0) }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                                        <div class="h-full rounded-full transition-all duration-1000 ease-out" 
                                             style="width: {{ $progress }}%; background-color: currentColor;"></div>
                                    </div>
                                </div>
                            </td>

                            {{-- Status Emote Dinamis --}}
                            <td class="px-8 py-6">
                                <span class="inline-flex items-center px-4 py-2 {{ $bg }} {{ $color }} rounded-xl text-[10px] font-black uppercase tracking-widest border border-current/10">
                                    <i data-lucide="{{ $icon }}" class="w-4 h-4 mr-2"></i>
                                    {{ $label }}
                                </span>
                            </td>

                            {{-- Periode --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center text-slate-600 font-bold text-xs">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 mr-2 text-slate-400"></i>
                                    {{ $student->assessments_received->last()->period ?? '-' }}
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-8 py-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('guru.assessment.show', $student->id) }}" 
                                       class="h-10 w-10 inline-flex items-center justify-center text-orange-500 bg-orange-50 hover:bg-orange-500 hover:text-white rounded-xl transition-all shadow-sm active:scale-90">
                                        <i data-lucide="eye" class="w-5 h-5"></i>
                                    </a>

                                    @if($student->assessments_received->last())
                                    <a href="{{ route('guru.assessment.edit', $student->assessments_received->last()->id) }}" 
                                       class="h-10 w-10 inline-flex items-center justify-center text-amber-600 bg-amber-50 hover:bg-amber-600 hover:text-white rounded-xl transition-all shadow-sm active:scale-90">
                                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mb-6">
                                        <i data-lucide="ghost" class="w-10 h-10 text-slate-300"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Penilaian</h3>
                                    <p class="text-slate-400 max-w-xs mx-auto italic font-medium text-sm">Berikan penilaian pertama Anda hari ini untuk melihat perkembangan karakter siswa! ✨</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection