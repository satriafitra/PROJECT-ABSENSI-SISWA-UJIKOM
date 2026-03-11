@extends('layouts.admin')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
            <div>
                <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight">Evaluasi Siswa</h2>
                <p class="text-slate-500 mt-1 font-medium italic">Menampilkan siswa yang <span class="text-blue-500 underline">sudah dinilai</span></p>
            </div>
            <a href="{{ route('guru.assessment.create') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold rounded-2xl shadow-xl shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-1 transition-all duration-300 group">
                <i data-lucide="plus" class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform"></i>
                Tambah Penilaian Baru
            </a>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="bg-green-50 border-2 border-green-100 text-green-600 p-4 rounded-2xl mb-6 flex items-center shadow-sm animate-bounce">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
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
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Status</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Periode</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($students as $student)
                        <tr class="hover:bg-blue-50/30 transition-all group">
                            {{-- Kolom Nama Siswa --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-black text-lg mr-4 shadow-lg shadow-blue-100 group-hover:scale-110 transition-transform">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-700 leading-tight">{{ $student->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1 italic">
                                            {{ $student->class->name ?? 'Kelas Tidak Ditemukan' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Progress Bar --}}
                            <td class="px-8 py-6">
                                <div class="w-full max-w-[160px]">
                                    <div class="flex justify-between mb-2">
                                        <span class="text-[10px] font-black text-blue-600 uppercase">
                                            {{ number_format($student->progress ?? 0, 0) }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-1000 ease-out" 
                                             style="width: {{ $student->progress ?? 0 }}%"></div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Status --}}
                            <td class="px-8 py-6">
                                <span class="inline-flex px-4 py-2 bg-green-50 text-green-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-green-100">
                                    Sudah Dinilai
                                </span>
                            </td>

                            {{-- Kolom Periode --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center text-slate-600 font-bold text-xs">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 mr-2 text-blue-400"></i>
                                    {{ $student->assessments_received->last()->period ?? '-' }}
                                </div>
                            </td>

                            {{-- Kolom Tombol Aksi --}}
                            <td class="px-8 py-6 text-center">
                                <a href="{{ route('guru.assessment.show', $student->id) }}" 
                                   class="h-11 w-11 inline-flex items-center justify-center text-blue-500 bg-blue-50 hover:bg-blue-500 hover:text-white rounded-2xl transition-all shadow-sm hover:shadow-md active:scale-95">
                                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mb-6">
                                        <i data-lucide="search-x" class="w-10 h-10 text-slate-300"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Penilaian</h3>
                                    <p class="text-slate-400 max-w-xs mx-auto italic font-medium">Klik tombol "Tambah Penilaian Baru" di atas untuk mulai memberikan evaluasi karakter siswa.</p>
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